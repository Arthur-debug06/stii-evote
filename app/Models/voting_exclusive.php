<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\department;
use App\Models\course;
use Carbon\Carbon;

class voting_exclusive extends Model
{
    //
    protected $table = 'voting_exclusive';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $fillable = ['department_id', 'course_id','school_year_id','start_datetime', 'end_datetime', 'status'];
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
    public function department()
    {
        return $this->belongsTo(department::class, 'department_id');
    }
    public function course()
    {
        return $this->belongsTo(course::class, 'course_id');
    }
    public function schoolYear()
    {
        return $this->belongsTo(\App\Models\school_year_and_semester::class, 'school_year_id');
    }
    
    public function voteCounts()
    {
        return $this->hasMany(\App\Models\voting_vote_count::class, 'voting_exclusive_id');
    }
    
    /**
     * Check if the voting period has ended
     */
    public function hasEnded()
    {
        return Carbon::now()->greaterThan($this->end_datetime);
    }
    
    /**
     * Check if the voting period has started
     */
    public function hasStarted()
    {
        return Carbon::now()->greaterThanOrEqualTo($this->start_datetime);
    }
    
    /**
     * Check if the voting is currently active
     */
    public function isActive()
    {
        $now = Carbon::now();
        return $this->status === 'active' && 
               $now->greaterThanOrEqualTo($this->start_datetime) && 
               $now->lessThanOrEqualTo($this->end_datetime);
    }
}
