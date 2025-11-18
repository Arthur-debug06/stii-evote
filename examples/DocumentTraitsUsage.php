<?php

/**
 * DocumentTraits Usage Examples
 * 
 * This file demonstrates how to use the DocumentTraits trait
 * in your Laravel Eloquent models.
 */

namespace Examples;

use App\Models\set_signatory;
use App\Models\signatory_action;
use App\Models\User;

class DocumentTraitsUsage
{
    public function demonstrateUsage()
    {
        // Example 1: Using morph relations on set_signatory model
        
        $signatory = set_signatory::find(1);
        
        // Access user relation (belongsTo)
        $user = $signatory->user; // Uses morph relation
        $user = $signatory->user(); // Returns relation query builder
        
        // Access signatory action relation (belongsTo)
        $action = $signatory->signatoryAction; // Uses morph relation
        $action = $signatory->signatoryAction(); // Returns relation query builder
        
        // Access signatories relation (morphMany)
        $signatories = $signatory->signatories; // Uses morph relation
        $signatories = $signatory->signatories(); // Returns relation query builder
        
        // Access action logs relation (morphMany)
        $actionLogs = $signatory->actionLogs; // Uses morph relation
        $actionLogs = $signatory->actionLogs(); // Returns relation query builder
        
        // Access notifications relation (morphMany)
        $notifications = $signatory->notifications; // Uses morph relation
        $notifications = $signatory->notifications(); // Returns relation query builder
        
        // Example 2: Using morph relations on signatory_action model
        
        $action = signatory_action::find(1);
        
        // Access signatories relation (hasMany)
        $signatories = $action->signatories; // Uses morph relation
        $signatories = $action->signatories(); // Returns relation query builder
        
        // Access action logs relation (morphMany)
        $actionLogs = $action->actionLogs; // Uses morph relation
        $actionLogs = $action->actionLogs(); // Returns relation query builder
        
        // Access notifications relation (morphMany)
        $notifications = $action->notifications; // Uses morph relation
        $notifications = $action->notifications(); // Returns relation query builder
        
        // Example 3: Dynamic morph relation configuration
        
        $signatory = new set_signatory();
        
        // Add a new morph relation dynamically
        $signatory->addMorphRelation('comments', [
            'type' => 'morphMany',
            'related' => 'App\Models\Comment',
            'morph_name' => 'commentable',
        ]);
        
        // Now you can use it
        $comments = $signatory->comments();
        
        // Check if a morph relation exists
        if ($signatory->hasMorphRelation('comments')) {
            // Do something
        }
        
        // Get morph relation configuration
        $config = $signatory->getMorphRelationConfig('comments');
        
        // Remove a morph relation
        $signatory->removeMorphRelation('comments');
        
        // Clear all morph relations
        $signatory->clearMorphRelations();
        
        // Example 4: Eager loading with morph relations
        
        $signatories = set_signatory::with(['user', 'signatoryAction', 'documents'])->get();
        
        // Example 5: Querying with morph relations
        
        $signatoriesWithActiveUsers = set_signatory::whereHas('user', function ($query) {
            $query->where('status', 'active');
        })->get();
        
        $actionsWithSignatories = signatory_action::whereHas('signatories', function ($query) {
            $query->where('status', 'active');
        })->get();
        
        // Example 6: Document Management - Release Document
        
        $signatory = set_signatory::find(1);
        
        // Release a document with signatories (Trail Release)
        // Note: user_id can reference either User model (admin/staff) or students model
        $signatories = [
            [
                'user_id' => 1, // Could be User model (admin/staff)
                'position' => 'Department Head',
                'academic_suffix' => 'PhD',
                'signatory_action_id' => 1,
                'status' => 'approved',
                'printable' => true,
            ],
            [
                'user_id' => 2, // Could be students model
                'position' => 'Student Representative',
                'academic_suffix' => 'BS',
                'signatory_action_id' => 2,
                'status' => 'pending',
                'printable' => false,
            ]
        ];
        
        $signatory->releaseDocument($signatories, true, 'Initial release for approval');
        
        // Example 7: Document Management - Take Action
        
        // Take signatory action (approve/reject)
        $signatory->takeAction(
            signatoryId: 2,
            action: 'approved',
            remarks: 'Approved by Approver',
            useESignature: true,
            signatureImagePath: '/path/to/signature.png'
        );
        
        // Example 8: Timeline and Action Logs
        
        // Get timeline logs
        $timeline = $signatory->getTimelineLogs();
        
        // Access action logs through morph relation
        $actionLogs = $signatory->actionLogs;
        
        // Example 9: PDF Preview Support
        
        // Check if PDF preview is supported
        if ($signatory->supportsPdfPreview()) {
            $pdfUrl = $signatory->getPdfPreviewUrl();
        }
        
        // Example 10: Notifications
        
        // Access notifications through morph relation
        $notifications = $signatory->notifications;
        
        // Example 11: Creating related models through morph relations
        
        // Create action log through the morph relation
        $actionLog = $signatory->actionLogs()->create([
            'action' => 'manual_log',
            'details' => 'Manual action log entry',
            'user_id' => auth()->id(),
            'created_by' => auth()->id(),
        ]);
        
        // Create notification through the morph relation
        $notification = $signatory->notifications()->create([
            'type' => 'App\\Notifications\\DocumentNotification',
            'user_id' => auth()->id(),
            'message' => 'Test notification',
            'title' => 'Test Title',
            'status' => 'info',
        ]);

        // Example 12: Working with Dual User System (User + students)
        
        $signatory = set_signatory::find(1);
        
        // Get user name regardless of whether it's User or students model
        $userName = $signatory->user_name; // Uses the accessor we created
        
        // Get the actual user model (User or students)
        $userModel = $signatory->getUser();
        
        if ($userModel instanceof User) {
            // This is an admin/staff user
            echo "Admin/Staff: " . $userModel->name;
            echo "Role: " . $userModel->role;
        } elseif ($userModel instanceof students) {
            // This is a student user
            echo "Student: " . $userModel->first_name . ' ' . $userModel->last_name;
            echo "Student ID: " . $userModel->student_id;
            echo "Course: " . $userModel->course->name ?? 'N/A';
        }
        
        // The DocumentTraits automatically handles both user types
        // when creating signatories, sending notifications, etc.
    }
    
    /**
     * Example of how to extend the trait in a custom model
     */
    public function customModelExample()
    {
        // In your custom model, you would do:
        
        /*
        class CustomModel extends Model
        {
            use DocumentTraits;
            
            protected array $morphRelations = [
                'signatories' => [
                    'type' => 'morphMany',
                    'related' => set_signatory::class,
                    'morph_name' => 'signatoryable',
                ],
                'actionLogs' => [
                    'type' => 'morphMany',
                    'related' => ActionLog::class,
                    'morph_name' => 'trackable',
                ],
                'notifications' => [
                    'type' => 'morphMany',
                    'related' => Notification::class,
                    'morph_name' => 'documentable',
                ],
                'user' => [
                    'type' => 'belongsTo',
                    'related' => User::class,
                    'foreign_key' => 'users_id',
                ],
            ];
            
            // Override getModelSpecificDetails for custom notification details
            protected function getModelSpecificDetails(): array
            {
                return [
                    'icon' => 'heroicon-o-document-text',
                    'icon_color' => 'primary',
                    'url' => "/custom-documents/{$this->id}",
                    'additional_details' => [
                        'title' => $this->title,
                        'custom_field' => $this->custom_field,
                    ]
                ];
            }
        }
        */
    }
}
