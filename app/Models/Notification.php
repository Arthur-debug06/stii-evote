<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'type',
        'user_id',
        'signatory_id',
        'release_type',
        'status',
        'message',
        'title',
        'icon',
        'icon_color',
        'url',
        'pdf_url',
        'documentable_type',
        'documentable_id',
        'notifiable_type',
        'notifiable_id',
        'data',
        'view_data',
        'read_at',
    ];

    protected $casts = [
        'data' => 'array',
        'view_data' => 'array',
        'read_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the documentable model (polymorphic relation)
     */
    public function documentable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the notifiable model (polymorphic relation)
     */
    public function notifiable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the user who will receive the notification
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the signatory associated with this notification
     */
    public function signatory(): BelongsTo
    {
        return $this->belongsTo(set_signatory::class, 'signatory_id');
    }

    /**
     * Get the student who will receive the notification (if applicable)
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(students::class, 'user_id');
    }

    /**
     * Scope to filter by notification type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope to filter by release type
     */
    public function scopeOfReleaseType($query, $releaseType)
    {
        return $query->where('release_type', $releaseType);
    }

    /**
     * Scope to filter by status
     */
    public function scopeOfStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to filter by user
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope to filter unread notifications
     */
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    /**
     * Scope to filter read notifications
     */
    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }

    /**
     * Scope to filter by document type
     */
    public function scopeForDocumentType($query, $documentType)
    {
        return $query->where('documentable_type', $documentType);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(): bool
    {
        return $this->update(['read_at' => now()]);
    }

    /**
     * Mark notification as unread
     */
    public function markAsUnread(): bool
    {
        return $this->update(['read_at' => null]);
    }

    /**
     * Check if notification is read
     */
    public function isRead(): bool
    {
        return !is_null($this->read_at);
    }

    /**
     * Check if notification is unread
     */
    public function isUnread(): bool
    {
        return is_null($this->read_at);
    }

    /**
     * Get the user name (handles both User and students models)
     */
    public function getUserNameAttribute(): string
    {
        if (!$this->user_id) {
            return 'System';
        }

        // Try to find in User model first (admin/staff)
        $user = User::find($this->user_id);
        if ($user) {
            return $user->name ?? 'Unknown User';
        }

        // Try to find in students model
        $student = students::find($this->user_id);
        if ($student) {
            $name = trim($student->first_name . ' ' . $student->middle_name . ' ' . $student->last_name);
            $suffix = $student->suffix ? ', ' . $student->suffix : '';
            return $name . $suffix ?: 'Unknown Student';
        }

        return 'Unknown User';
    }

    /**
     * Get the user type (admin or student)
     */
    public function getUserTypeAttribute(): string
    {
        if (!$this->user_id) {
            return 'system';
        }

        // Check if it's a User model (admin/staff)
        if (User::find($this->user_id)) {
            return 'admin';
        }

        // Check if it's a students model
        if (students::find($this->user_id)) {
            return 'student';
        }

        return 'unknown';
    }

    /**
     * Get the URL for the notification, with fallback based on documentable_type
     */
    public function getUrlAttribute($value)
    {
        // If URL is already set, return it
        if ($value) {
            return $value;
        }

        // Provide fallback URLs based on documentable_type
        if ($this->documentable_type) {
            switch ($this->documentable_type) {
                case 'App\\Models\\applied_candidacy':
                    return '/candidacy-management';
                case 'App\\Models\\registration_request':
                    return '/registration-request';
                case 'App\\Models\\set_signatory':
                    return '/set-signatory';
                case 'App\\Models\\signatory_action':
                    return '/set-signatory';
                default:
                    return null;
            }
        }

        return null;
    }

    /**
     * Get formatted notification data
     */
    public function getFormattedDataAttribute(): array
    {
        $data = $this->data ?? [];
        
        return array_merge($data, [
            'id' => $this->id,
            'type' => $this->type,
            'title' => $this->title,
            'message' => $this->message,
            'icon' => $this->icon,
            'icon_color' => $this->icon_color,
            'url' => $this->url,
            'pdf_url' => $this->pdf_url,
            'status' => $this->status,
            'release_type' => $this->release_type,
            'is_read' => $this->isRead(),
            'created_at' => $this->created_at,
            'user_name' => $this->user_name,
            'user_type' => $this->user_type,
        ]);
    }
}
