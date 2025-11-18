<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Traits\LoggerTrait;

class students extends Authenticatable
{
    //
    use HasFactory, SoftDeletes, LoggerTrait;
    protected $table = 'students';
    protected $primaryKey = 'id';
    protected $fillable = ['student_id', 'course_id', 'department_id', 'school_year_and_semester_id','marital_status','first_name', 'middle_name', 'last_name', 'suffix', 'gender','date_of_birth','age','address','profile_image','student_id_image','student_id_image_back','email','password','status'];
    public $timestamps = true;

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }
    
    public function course()
    {
        return $this->belongsTo(course::class, 'course_id');
    }
    public function department()
    {
        return $this->belongsTo(department::class, 'department_id');
    }
    public function school_year_and_semester()
    {
        return $this->belongsTo(school_year_and_semester::class, 'school_year_and_semester_id');
    }
    
    public function applied_candidacies()
    {
        return $this->hasMany(applied_candidacy::class, 'students_id');
    }
    
    public function appliedCandidacy()
    {
        return $this->hasOne(applied_candidacy::class, 'students_id');
    }

    /**
     * Get the action logs for the student.
     */
    public function actionLogs()
    {
        return $this->morphMany(\App\Models\ActionLog::class, 'trackable');
    }
}
