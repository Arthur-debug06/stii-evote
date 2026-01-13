<?php

namespace App\Traits;

use App\Models\ActionLog;
use App\Models\Notification;
use App\Models\set_signatory;
use App\Models\User;
use App\Models\students;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

/**
 * Enhanced Document Management Trait
 *
 * This trait provides comprehensive document management functionality including:
 * - Polymorphic relations with zero repetition
 * - Document release and signatory management
 * - Notifications and action logging
 * - PDF preview capabilities
 * - Timeline tracking
 *
 * USAGE INSTRUCTIONS:
 * ------------------
 * 1. Add the trait to your model:
 *    ```php
 *    use App\Traits\DocumentTraits;
 *
 *    class YourModel extends Model
 *    {
 *        use DocumentTraits;
 *    }
 *    ```
 *
 * 2. Configure morph relations in your model:
 *    ```php
     *    protected array $morphRelations = [
     *        'signatories' => [
     *            'type' => 'morphMany',
     *            'related' => set_signatory::class,
     *            'morph_name' => 'signatoryable',
     *        ],
     *        'actionLogs' => [
     *            'type' => 'morphMany',
     *            'related' => ActionLog::class,
     *            'morph_name' => 'trackable',
     *        ],
     *    ];
 *    ```
 *
 * 3. Release a document with signatories:
 *    ```php
 *    $signatories = [
 *        [
 *            'user_id' => 1,
 *            'role' => 'Reviewed by Creator',
 *            'status' => 'approved',
 *            'printable' => true,
 *        ],
 *        [
 *            'user_id' => 2,
 *            'role' => 'Approver',
 *            'status' => 'pending',
 *            'printable' => false,
 *        ]
 *    ];
 *
 *    $document->releaseDocument($signatories, true); // Trail release
 *    ```
 *
 * 4. Take signatory action:
 *    ```php
 *    $document->takeAction(
 *        signatoryId: 2,
 *        action: 'approved',
 *        remarks: 'Approved by Approver',
 *        useESignature: true
 *    );
 *    ```
 */
trait DocumentTraits
{
    /**
     * Internal storage for morph relations managed by the trait.
     *
     * We avoid declaring a protected property named $morphRelations here to
     * prevent property redeclaration/type-compatibility issues when the
     * consuming model also defines the same property. Instead, the trait
     * keeps its own private storage and merges it with any model-defined
     * $morphRelations at runtime. Model-defined relations take precedence.
     */
    private array $documentTraitMorphRelations = [];

    /**
     * Get merged morph relations (model-defined + trait-managed).
     * Model-defined relations take precedence over trait-managed ones.
     */
    protected function getAllMorphRelations(): array
    {
        $modelRelations = [];
        if (property_exists($this, 'morphRelations')) {
            // Cast to array in case the consumer used a different type/hint
            $modelRelations = (array) $this->morphRelations;
        }

        // Merge trait-managed relations first, then model relations so the
        // model overrides any keys defined by the trait.
        return array_merge($this->documentTraitMorphRelations, $modelRelations);
    }

    /**
     * Handle dynamic method calls for morph relations
     */
    public function __call($method, $parameters)
    {
        // Check if this is a morph relation method
        if ($this->isMorphRelation($method)) {
            return $this->getMorphRelation($method);
        }

        // Fall back to parent __call for other methods
        return parent::__call($method, $parameters);
    }

    /**
     * Check if the method is a configured morph relation
     */
    protected function isMorphRelation(string $method): bool
    {
        $all = $this->getAllMorphRelations();
        return array_key_exists($method, $all);
    }

    /**
     * Get the morph relation based on configuration
     */
    protected function getMorphRelation(string $method)
    {
        $all = $this->getAllMorphRelations();
        if (!array_key_exists($method, $all)) {
            throw new \InvalidArgumentException("Morph relation not configured: {$method}");
        }

        $config = $all[$method];
        
        switch ($config['type']) {
            case 'morphTo':
                return $this->morphTo($method, $config['morph_type'] ?? null, $config['morph_id'] ?? null);
            
            case 'morphMany':
                return $this->morphMany($config['related'], $config['morph_name']);
            
            case 'morphOne':
                return $this->morphOne($config['related'], $config['morph_name']);
            
            case 'morphToMany':
                return $this->morphToMany(
                    $config['related'], 
                    $config['morph_name'], 
                    $config['table'] ?? null,
                    $config['foreign_pivot_key'] ?? null,
                    $config['related_pivot_key'] ?? null,
                    $config['parent_key'] ?? null,
                    $config['related_key'] ?? null,
                    $config['inverse'] ?? false
                );
            
            case 'morphedByMany':
                return $this->morphedByMany(
                    $config['related'], 
                    $config['morph_name'], 
                    $config['table'] ?? null,
                    $config['foreign_pivot_key'] ?? null,
                    $config['related_pivot_key'] ?? null,
                    $config['parent_key'] ?? null,
                    $config['related_key'] ?? null
                );
            
            case 'belongsTo':
                return $this->belongsTo($config['related'], $config['foreign_key'] ?? null, $config['owner_key'] ?? null);
            
            case 'hasMany':
                return $this->hasMany($config['related'], $config['foreign_key'] ?? null, $config['local_key'] ?? null);
            
            case 'hasOne':
                return $this->hasOne($config['related'], $config['foreign_key'] ?? null, $config['local_key'] ?? null);
            
            case 'belongsToMany':
                return $this->belongsToMany(
                    $config['related'], 
                    $config['table'] ?? null,
                    $config['foreign_pivot_key'] ?? null,
                    $config['related_pivot_key'] ?? null,
                    $config['parent_key'] ?? null,
                    $config['related_key'] ?? null
                );
            
            default:
                throw new \InvalidArgumentException("Unsupported morph relation type: {$config['type']}");
        }
    }

    /**
     * Add a morph relation configuration
     */
    public function addMorphRelation(string $method, array $config): self
    {
        // Add to trait-managed storage
        $this->documentTraitMorphRelations[$method] = $config;

        // If the consuming model has a morphRelations property, also add there
        if (property_exists($this, 'morphRelations')) {
            $modelRelations = (array) $this->morphRelations;
            $modelRelations[$method] = $config;
            $this->morphRelations = $modelRelations;
        }

        return $this;
    }

    /**
     * Get all configured morph relations
     */
    public function getMorphRelations(): array
    {
        return $this->getAllMorphRelations();
    }

    /**
     * Check if a morph relation exists
     */
    public function hasMorphRelation(string $method): bool
    {
        return $this->isMorphRelation($method);
    }

    /**
     * Remove a morph relation configuration
     */
    public function removeMorphRelation(string $method): self
    {
        // Remove from trait-managed storage
        if (array_key_exists($method, $this->documentTraitMorphRelations)) {
            unset($this->documentTraitMorphRelations[$method]);
        }

        // Also remove from model-defined storage if present
        if (property_exists($this, 'morphRelations')) {
            $modelRelations = (array) $this->morphRelations;
            if (array_key_exists($method, $modelRelations)) {
                unset($modelRelations[$method]);
                $this->morphRelations = $modelRelations;
            }
        }

        return $this;
    }

    /**
     * Clear all morph relation configurations
     */
    public function clearMorphRelations(): self
    {
        $this->documentTraitMorphRelations = [];
        if (property_exists($this, 'morphRelations')) {
            $this->morphRelations = [];
        }
        return $this;
    }

    /**
     * Get morph relation configuration
     */
    public function getMorphRelationConfig(string $method): ?array
    {
        $all = $this->getAllMorphRelations();
        return $all[$method] ?? null;
    }

    /**
     * Boot the trait
     */
    protected static function bootDocumentTraits()
    {
        // You can add any boot logic here if needed
    }

    /**
     * Get user name from either User or students model
     */
    protected function getUserName($userId): string
    {
        // Try to find in User model first (admin/staff)
        $user = User::find($userId);
        if ($user) {
            return $user->name ?? 'Unknown User';
        }

        // Try to find in students model
        $student = students::find($userId);
        if ($student) {
            $name = trim($student->first_name . ' ' . $student->middle_name . ' ' . $student->last_name);
            $suffix = $student->suffix ? ', ' . $student->suffix : '';
            return $name . $suffix ?: 'Unknown Student';
        }

        return 'Unknown User';
    }

    /**
     * Get user model (User or students) by ID
     */
    protected function getUserModel($userId)
    {
        // Try User model first
        $user = User::find($userId);
        if ($user) {
            return $user;
        }

        // Try students model
        return students::find($userId);
    }

    /**
     * Get current logged-in user ID from either guard
     */
    protected function getCurrentUserId(): ?int
    {
        // Try to get from default guard (User model)
        if (Auth::check()) {
            return Auth::id();
        }

        // Try to get from students guard
        if (Auth::guard('students')->check()) {
            return Auth::guard('students')->id();
        }

        // Return null if no user is authenticated
        return null;
    }

    // ========================================
    // DOCUMENT MANAGEMENT METHODS
    // ========================================

    /**
     * Release the file/document and notify signatories.
     *
     * @param array $signatories Array of signatory data
     * @param bool $isTrailRelease True if a trail (sequential) release; false for quick release
     * @param string|null $remarks Optional remarks for the release
     * @return void
     */
    public function releaseDocument(array $signatories, bool $isTrailRelease = false, ?string $remarks = null): void
    {
        DB::transaction(function () use ($signatories, $isTrailRelease, $remarks) {
            // Mark the document as released
            $this->update(['status' => 'Released']);

            // Soft delete all previous signatories before adding new ones
            set_signatory::where('signatoryable_id', $this->id)
                ->where('signatoryable_type', get_class($this))
                ->update(['deleted_at' => now()]);

            // Create new signatories for the document
            $createdSignatories = [];
            foreach ($signatories as $index => $signatory) {
                $createdSignatories[] = set_signatory::create([
                    'users_id' => $signatory['user_id'],
                    'position' => $signatory['position'] ?? null,
                    'academic_suffix' => $signatory['academic_suffix'] ?? null,
                    'signatory_action_id' => $signatory['signatory_action_id'] ?? null,
                    'status' => ($signatory['status'] ?? 'pending') === 'approved' ? 'approved' : 'pending',
                    'action_taken_at' => ($signatory['status'] ?? null) === 'approved' ? now() : null,
                    'hierarchy' => $isTrailRelease ? $index + 1 : null,
                    'signatoryable_id' => $this->id,
                    'signatoryable_type' => get_class($this),
                    'printable' => ($signatory['printable'] ?? true) === true ? true : false,
                ]);
                
                $userName = $this->getUserName($signatory['user_id']);
                // Log each signatory addition with remarks
                $this->logAction('document_signatory_added', 'Signatory added: ' . $userName . ' as ' . ($signatory['position'] ?? 'Signatory') . '.', $remarks);
            }

            // Send notifications for all signatories for audit/history
            foreach ($createdSignatories as $signatory) {
                // Determine notification type and status
                $notificationType = 'info';
                $notificationStatus = $signatory->status;
                if ($isTrailRelease && $signatory->status === 'pending') {
                    $notificationType = 'trail_release';
                } elseif (!$isTrailRelease) {
                    $notificationType = 'quick_release';
                } else if ($signatory->status === 'approved') {
                    $notificationType = 'auto-approved';
                }
                $this->sendNotification($signatory, $notificationType, $notificationStatus);
            }

            // For trail release, send actionable notification only to the first pending signatory
            if ($isTrailRelease) {
                $firstPendingSignatory = collect($createdSignatories)
                    ->where('status', 'pending')
                    ->sortBy('hierarchy')
                    ->first();
                if ($firstPendingSignatory) {
                    // Optionally, you can re-send or update the notification to make it actionable
                    // $this->sendNotification($firstPendingSignatory, 'trail_release', $firstPendingSignatory->status);
                } else {
                    // All signatories are already approved, mark document as approved and notify owner
                    $this->update(['status' => 'Approved']);
                    $this->logAction('document_approved', 'Document auto-approved: all signatories already approved on release.', $remarks);
                    $this->sendOwnerNotification("File '{$this->title}' has been approved.");
                }
            }

            // Log the document release action with remarks
            $this->logAction('document_released', 'Document released with ' . count($signatories) . ' signatories.', $remarks);
        });
    }

    /**
     * Handle an action taken by a signatory (e.g., approve, reject, return).
     *
     * @param int $signatoryId The ID of the signatory record
     * @param string $action The action taken ('approved', 'rejected', etc.)
     * @param string|null $remarks Optional remarks for the action
     * @param bool $useESignature Whether to use e-signature for this action
     * @param string|null $signatureImagePath Optional path to the signature image
     * @return void
     */
    public function takeAction(
        int $signatoryId,
        string $action,
        ?string $remarks = null,
        bool $useESignature = false,
        ?string $signatureImagePath = null
    ): void {
        DB::transaction(function () use ($signatoryId, $action, $remarks, $useESignature, $signatureImagePath) {
            // Get the signatory and update their status
            $signatory = set_signatory::where('signatoryable_id', $this->id)
                ->where('signatoryable_type', get_class($this))
                ->where('id', $signatoryId)
                ->firstOrFail();

            // Update the signatory's status and e-signature usage
            $signatory->update([
                'status' => $action,
                'used_esignature' => $useESignature,
                'action_taken_at' => now(),
                'signature_image_path' => $useESignature ? $signatureImagePath : null,
            ]);

            // Get the user's name for logging
            $userName = $this->getUserName($signatory->users_id);

            // Update the notification status if it exists
            $notification = Notification::where('signatory_id', $signatoryId)->first();
            if ($notification) {
                $notification->update(['status' => $action]);
            }

            // If e-signature was used, log it first
            if ($useESignature) {
                $this->logAction(
                    'esignature_used',
                    "E-signature used by {$userName} for {$action} action.",
                    $remarks
                );
            }

            // Process the document status which includes document state updates
            $this->processDocumentStatus($signatory, $action);

            // Create action description with e-signature info
            $actionDescription = "User {$userName} performed {$action} on the document" .
                ($useESignature ? ' using e-signature.' : '.');

            // Log the action with remarks (most important, so logged last)
            $this->logAction($action, $actionDescription, $remarks);
        });
    }

    /**
     * Process the file status after a signatory's action.
     *
     * @param \App\Models\set_signatory $signatory
     * @param string $action
     * @return void
     */
    protected function processDocumentStatus(set_signatory $signatory, string $action): void
    {
        $modelType = static::class;
        $isTrailRelease = !is_null($signatory->hierarchy);

        if ($isTrailRelease) {
            // For trail release: if approved, notify the next signatory
            $nextSignatory = set_signatory::where('signatoryable_id', $this->id)
                ->where('signatoryable_type', $modelType)
                ->where('status', 'pending')
                ->where('hierarchy', $signatory->hierarchy + 1)
                ->first();

            if ($nextSignatory) {
                if ($action === 'approved') {
                    // First update status
                    $this->update(['status' => 'Released']);

                    // Then record timeline
                    $this->recordTimeline('document_status_updated', "Document status updated to Released");

                    // Finally send notification and record it
                    $this->sendNotification($nextSignatory, 'trail_release', 'pending');
                    $nextUserName = $this->getUserName($nextSignatory->users_id);
                    $this->recordTimeline('notification_sent', "Trail notification sent to next signatory {$nextUserName}");
                } else {
                    // First update status
                    $this->update(['status' => 'Rejected']);

                    // Then record the rejection
                    $this->recordTimeline('document_rejected', "Document rejected in trail process");

                    // Finally notify owner and record it
                    $this->sendOwnerNotification("File '{$this->title}' has been rejected.");
                    $this->recordTimeline('owner_notified', "Owner notified about action: {$action}");
                }
            } else {
                // Last signatory: check if all have approved
                $allApproved = set_signatory::where('signatoryable_id', $this->id)
                    ->where('signatoryable_type', $modelType)
                    ->whereNotNull('hierarchy')
                    ->where('hierarchy', '<=', $signatory->hierarchy)
                    ->get()
                    ->every(fn($sig) => $sig->status === 'approved');

                if ($allApproved && $action === 'approved') {
                    // First update status
                    $this->update(['status' => 'Approved']);

                    // Then record approval
                    $this->recordTimeline('document_approved', "Document approved after trail completion");

                    // Finally notify owner and record it
                    $this->sendOwnerNotification("File '{$this->title}' has been approved.");
                }
                if ($action === 'rejected') {
                    // First update status
                    $this->update(['status' => 'Rejected']);

                    // Then record rejection
                    $this->recordTimeline('document_rejected', "Document rejected after trail completion");

                    // Finally notify owner and record it
                    $this->sendOwnerNotification("File '{$this->title}' has been rejected.");
                }
            }
        } else {
            // For quick release, check the overall status of all signatories
            $signatories = set_signatory::where('signatoryable_id', $this->id)
                ->where('signatoryable_type', $modelType)
                ->get();

            $allApproved = $signatories->every(fn($sig) => $sig->status === 'approved');
            if ($allApproved) {
                // First update status
                $this->update(['status' => 'Approved']);

                // Then record approval
                $this->recordTimeline('document_approved', "Document approved after all quick release signatories approved");

                // Finally notify owner and record it
                $this->sendOwnerNotification("File '{$this->title}' has been approved.");
            } else {
                $anyRejected = $signatories->contains(fn($sig) => in_array($sig->status, ['Rejected', 'Disapproved']));
                if ($anyRejected) {
                    $status = $signatories->contains(fn($sig) => $sig->status === 'Rejected') ? 'Rejected' : 'Disapproved';

                    // First update status
                    $this->update(['status' => $status]);

                    // Then record status change
                    $this->recordTimeline("document_{$status}", "Document marked as {$status} due to signatory actions");

                    // Finally notify owner and record it
                    $this->sendOwnerNotification("File '{$this->title}' has been {$status}.");
                }
            }
        }
    }

    // ========================================
    // NOTIFICATION METHODS
    // ========================================

    /**
     * Send a notification to a signatory.
     *
     * @param \App\Models\set_signatory $signatory
     * @param string $releaseType 'quick_release' or 'trail_release'
     * @param string $status Typically 'pending'
     * @return void
     */
    protected function sendNotification(set_signatory $signatory, string $releaseType, string $status): void
    {
        // Always use the signatory's current status for the notification
        $status = $signatory->status;
        // Get model-specific details
        $modelDetails = $this->getModelSpecificDetails();

        // Get PDF URL if supported
        $pdfUrl = $this->supportsPdfPreview() ? $this->getPdfPreviewUrl() : null;

        // Prepare notification data
        $notificationData = [
            'title' => $this->getDocumentTitle(),
            'message' => "You have been assigned as a signatory for file: '{$this->title}'.",
            'type' => $releaseType,
            'status' => $status,
            'document_type' => get_class($this),
            'additional_details' => $modelDetails['additional_details'] ?? null,
        ];

        // Create notification with organized data
        Notification::create([
            'type' => 'App\\Notifications\\DocumentNotification',
            'user_id' => $signatory->users_id,
            'signatory_id' => $signatory->id,
            'release_type' => $releaseType,
            'status' => $status,
            'message' => $notificationData['message'],
            'title' => 'Document Signatory Assignment',
            'icon' => $modelDetails['icon'] ?? 'heroicon-o-document',
            'icon_color' => $modelDetails['icon_color'] ?? 'primary',
            'url' => $modelDetails['url'] ?? null,
            'pdf_url' => $pdfUrl,
            'documentable_type' => get_class($this),
            'documentable_id' => $this->id,
            'notifiable_type' => 'App\\Models\\User',
            'notifiable_id' => (string) $signatory->users_id,
            'data' => $notificationData,
            'view_data' => $modelDetails['additional_details'] ?? null,
        ]);

        // Record in timeline for tracking
        $userName = $this->getUserName($signatory->users_id);
        $this->recordTimeline('notification_created', "Notification created for {$userName}");

        // Dispatch toast notification
        $this->dispatchToastNotification([
            'type' => $status === 'approved' ? 'success' : ($status === 'rejected' ? 'error' : 'info'),
            'title' => 'Document Signatory Assignment',
            'message' => "You have been assigned as a signatory for: {$this->getDocumentTitle()}",
        ]);
    }

    /**
     * Send a notification to the file owner.
     *
     * @param string $message
     * @return void
     */
    protected function sendOwnerNotification(string $message): void
    {
        // Get model-specific details
        $modelDetails = $this->getModelSpecificDetails();

        // Determine status based on message content
        $status = $this->determineNotificationStatus($message);

        // Prepare notification data
        $notificationData = array_merge($modelDetails['additional_details'] ?? [], [
            'title' => $this->getDocumentTitle(),
            'message' => $message,
            'type' => 'owner_notification',
            'status' => $status,
            'document_type' => get_class($this),
        ]);

        // Create notification with organized data
        Notification::create([
            'type' => 'App\\Notifications\\DocumentNotification',
            'user_id' => $this->users_id ?? $this->created_by ?? null,
            'signatory_id' => null,
            'release_type' => 'owner_notification',
            'status' => $status,
            'message' => $message,
            'title' => 'Document Status Update',
            'icon' => $modelDetails['icon'] ?? 'heroicon-o-bell',
            'icon_color' => $this->getNotificationColorByStatus($status),
            'url' => $modelDetails['url'] ?? null,
            'documentable_type' => get_class($this),
            'documentable_id' => $this->id,
            'notifiable_type' => 'App\\Models\\User',
            'notifiable_id' => $this->users_id ?? $this->created_by ?? null,
            'data' => $notificationData,
            'view_data' => $modelDetails['additional_details'] ?? null,
        ]);

        // Record in timeline for tracking
        $this->recordTimeline('owner_notification', "Owner notified: {$message}");

        // Dispatch toast notification
        $this->dispatchToastNotification([
            'type' => $status === 'approved' ? 'success' : ($status === 'rejected' ? 'error' : 'info'),
            'title' => 'Document Status Update',
            'message' => $message,
        ]);
    }

    /**
     * Get model-specific details for notifications
     * Override this in your models to provide custom details
     */
    protected function getModelSpecificDetails(): array
    {
        $modelType = get_class($this);
        $baseUrl = config('app.url');

        switch ($modelType) {
            case 'App\\Models\\set_signatory':
                return [
                    'icon' => 'heroicon-o-user-check',
                    'icon_color' => 'info',
                    'url' => "/signatory-management/{$this->id}",
                    'additional_details' => [
                        'title' => $this->getDocumentTitle(),
                        'position' => $this->position,
                        'academic_suffix' => $this->academic_suffix,
                        'status' => $this->status,
                        'user_name' => $this->getUserName($this->users_id),
                    ]
                ];

            case 'App\\Models\\signatory_action':
                return [
                    'icon' => 'heroicon-o-cog-6-tooth',
                    'icon_color' => 'warning',
                    'url' => "/signatory-actions/{$this->id}",
                    'additional_details' => [
                        'action_name' => $this->action_name,
                        'status' => $this->status,
                    ]
                ];

            default:
                return [
                    'icon' => 'heroicon-o-document',
                    'icon_color' => 'primary',
                    'url' => null,
                    'additional_details' => [
                        'title' => $this->getDocumentTitle()
                    ],
                ];
        }
    }

    /**
     * Get document title with fallback
     */
    protected function getDocumentTitle(): string
    {
        if (!isset($this->title)) {
            $this->title = match (get_class($this)) {
                'App\\Models\\set_signatory' => "Signatory Assignment #{$this->id}",
                'App\\Models\\signatory_action' => "Signatory Action: {$this->action_name}",
                default => "Document #{$this->id}"
            };
            if (method_exists($this, 'save')) {
                $this->save();
            }
        }

        return $this->title;
    }

    /**
     * Determine notification status based on message content
     */
    protected function determineNotificationStatus(string $message): string
    {
        if (stripos($message, 'approved') !== false) {
            return 'approved';
        } elseif (stripos($message, 'rejected') !== false || stripos($message, 'disapproved') !== false) {
            return 'rejected';
        }
        return 'info';
    }

    /**
     * Get notification color based on status
     */
    protected function getNotificationColorByStatus(string $status): string
    {
        return match ($status) {
            'approved' => 'success',
            'rejected' => 'danger',
            'pending' => 'warning',
            default => 'info'
        };
    }

    // ========================================
    // ACTION LOGGING METHODS
    // ========================================

    /**
     * Record a timeline log of an event.
     *
     * @param string $action
     * @param string $details
     * @return void
     */
    protected function recordTimeline(string $action, string $details): void
    {
        ActionLog::create([
            'document_type' => static::class,
            'document_id' => $this->id,
            'user_id' => $this->getCurrentUserId(),
            'created_by' => $this->getCurrentUserId(),
            'action' => $action,
            'details' => $details,
            'trackable_type' => get_class($this),
            'trackable_id' => $this->id,
        ]);
    }

    /**
     * Log an action with optional remarks
     */
    protected function logAction(string $action, string $details, ?string $remarks = null): void
    {
        ActionLog::create([
            'document_type' => get_class($this),
            'document_id' => $this->id,
            'user_id' => $this->getCurrentUserId(),
            'created_by' => $this->getCurrentUserId(),
            'action' => $action,
            'details' => $details,
            'remarks' => $remarks,
            'trackable_type' => get_class($this),
            'trackable_id' => $this->id,
        ]);
    }

    /**
     * Retrieve timeline logs for the file/document.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getTimelineLogs()
    {
        return ActionLog::where('document_type', static::class)
            ->where('document_id', $this->id)
            ->orderBy('created_at', 'asc')
            ->get();
    }

    // ========================================
    // PDF PREVIEW METHODS
    // ========================================

    /**
     * Get the URL for PDF preview.
     * Returns the appropriate download route with action-center flag if supported.
     *
     * @return string|null
     */
    public function getPdfPreviewUrl(): ?string
    {
        // Map model types to their preview routes
        $routeMap = [
            'App\\Models\\set_signatory' => 'signatory.print',
            'App\\Models\\signatory_action' => 'signatory-action.print',
        ];

        $modelType = get_class($this);

        if (isset($routeMap[$modelType])) {
            return route($routeMap[$modelType], [
                'id' => $this->id,
                'from' => 'action-center',
            ]);
        }

        return null;
    }

    /**
     * Check if the document type supports PDF preview
     *
     * @return bool
     */
    public function supportsPdfPreview(): bool
    {
        $supportedTypes = [
            'App\\Models\\set_signatory',
            'App\\Models\\signatory_action',
        ];

        return in_array(get_class($this), $supportedTypes);
    }

    /**
     * Dispatch toast notification
     *
     * @param array $options Toast options (type, title, message)
     * @return void
     */
    protected function dispatchToastNotification(array $options): void
    {
        // Simple and direct approach - dispatch browser event
        $script = "
            <script>
                (function() {
                    function showToast() {
                        if (window.showMenuToast) {
                            window.showMenuToast(" . json_encode($options) . ");
                        } else {
                            // Fallback: dispatch custom event
                            window.dispatchEvent(new CustomEvent('show-toast', {
                                detail: " . json_encode($options) . "
                            }));
                        }
                    }
                    
                    // Try immediately
                    showToast();
                    
                    // Also try after a short delay to ensure DOM is ready
                    setTimeout(showToast, 100);
                })();
            </script>
        ";
        
        // Push the script to the view
        if (function_exists('app') && app()->bound('view')) {
            app('view')->startPush('scripts', $script);
        }
    }
}
