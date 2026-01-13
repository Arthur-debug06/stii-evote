<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\students;
use App\Traits\LoggerTrait;
use App\Traits\SignatoryTrait;
use App\Models\position;
use App\Models\school_year_and_semester;
use App\Models\partylist;

class applied_candidacy extends Model
{
    use HasFactory, SoftDeletes, LoggerTrait, SignatoryTrait;
    protected $table = 'applied_candidacy';
    protected $primaryKey = 'id';
    protected $fillable = ['students_id','position_id','school_year_and_semester_id','partylist_id','grade_attachment','is_regular_student', 'status', 'remarks'];
    public $timestamps = true;

    public function students()
    {
        return $this->belongsTo(students::class, 'students_id');
    }
    public function position()
    {
        return $this->belongsTo(position::class, 'position_id');
    }
    public function school_year_and_semester()
    {
        return $this->belongsTo(school_year_and_semester::class, 'school_year_and_semester_id');
    }
    /**
     * Direct relations for notifications and logs
     */
    public function notifications()
    {
        return $this->hasMany(\App\Models\Notification::class, 'documentable_id')->where('documentable_type', get_class($this));
    }

    public function actionLogs()
    {
        return $this->hasMany(\App\Models\ActionLog::class, 'trackable_id')->where('trackable_type', get_class($this));
    }
    public function partylist()
    {
        return $this->belongsTo(partylist::class, 'partylist_id');
    }

    /**
     * Override getModelSpecificDetails to provide proper URL for notifications
     */
    protected function getModelSpecificDetails(): array
    {
        return [
            'icon' => 'heroicon-o-user-check',
            'icon_color' => 'info',
            'url' => "/candidacy-management",
            'additional_details' => [
                'title' => 'Candidacy Application',
                'student_name' => $this->students ? $this->students->first_name . ' ' . $this->students->last_name : 'Unknown',
                'position' => $this->position ? $this->position->position_name : 'N/A',
                'partylist' => $this->partylist ? $this->partylist->partylist_name : 'Independent',
                'status' => $this->status,
            ]
        ];
    }
}
