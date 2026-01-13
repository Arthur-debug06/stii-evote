<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActionLog extends Model
{
    use HasFactory;

    protected $table = 'action_logs';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'document_type',
        'document_id',
        'user_id',
        'created_by',
        'action',
        'details',
        'remarks',
        'trackable_type',
        'trackable_id',
        'ip_address',
        'user_agent',
        'location',
        'batch_uuid',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the trackable model (polymorphic relation)
     */
    public function trackable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the user who performed the action
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the user who created the log
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the student who performed the action (if applicable)
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(students::class, 'user_id');
    }

    /**
     * Scope to filter by document type
     */
    public function scopeForDocumentType($query, $documentType)
    {
        return $query->where('document_type', $documentType);
    }

    /**
     * Scope to filter by action
     */
    public function scopeForAction($query, $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope to filter by user
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope to filter by batch UUID
     */
    public function scopeForBatch($query, $batchUuid)
    {
        return $query->where('batch_uuid', $batchUuid);
    }

    /**
     * Get the user name (handles both User and students models)
     */
    public function getUserNameAttribute(): string
    {
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
}
