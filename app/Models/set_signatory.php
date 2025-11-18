<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\DocumentTraits;
use App\Models\students;

class set_signatory extends Model
{
    use HasFactory, SoftDeletes, DocumentTraits;
    
    protected $primaryKey = 'id';
    protected $table = 'set_signatory';
    protected $fillable = ['users_id', 'position', 'academic_suffix', 'signatory_action_id', 'status'];
    public $timestamps = true;

    /**
     * Morph relations for DocumentTraits
     */
    public function signatories()
    {
        return $this->morphMany(\App\Models\set_signatory::class, 'signatoryable');
    }

    public function actionLogs()
    {
        return $this->morphMany(\App\Models\ActionLog::class, 'trackable');
    }

    public function notifications()
    {
        return $this->morphMany(\App\Models\Notification::class, 'documentable');
    }

    // Keep the original methods for backward compatibility
    public function users()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    /**
     * Get the user (either User or students model)
     */
    public function getUser()
    {
        // Try User model first (admin/staff)
        $user = User::find($this->users_id);
        if ($user) {
            return $user;
        }

        // Try students model
        return students::find($this->users_id);
    }

    /**
     * Get user name from either User or students model
     */
    public function getUserNameAttribute()
    {
        $user = $this->getUser();
        
        if (!$user) {
            return 'Unknown User';
        }

        // If it's a User model (admin/staff)
        if ($user instanceof User) {
            return $user->name ?? 'Unknown User';
        }

        // If it's a students model
        if ($user instanceof students) {
            $name = trim($user->first_name . ' ' . $user->middle_name . ' ' . $user->last_name);
            $suffix = $user->suffix ? ', ' . $user->suffix : '';
            return $name . $suffix ?: 'Unknown Student';
        }

        return 'Unknown User';
    }
    
    public function signatory_action()
    {
        return $this->belongsTo(signatory_action::class, 'signatory_action_id');
    }
}
