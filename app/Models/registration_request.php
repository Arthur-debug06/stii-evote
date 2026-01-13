<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\students;

class registration_request extends Model
{
    //
    use HasFactory, SoftDeletes;
    protected $table = 'registration_request';
    protected $primaryKey = 'id';
    protected $fillable = ['students_id', 'remarks','status'];
    public $timestamps = true;

    public function students()
    {
        return $this->belongsTo(students::class, 'students_id');
    }

    /**
     * Override getModelSpecificDetails to provide proper URL for notifications
     */
    protected function getModelSpecificDetails(): array
    {
        return [
            'icon' => 'heroicon-o-user-plus',
            'icon_color' => 'success',
            'url' => "/registration-request",
            'additional_details' => [
                'title' => 'Registration Request',
                'student_name' => $this->students ? $this->students->first_name . ' ' . $this->students->last_name : 'Unknown',
                'status' => $this->status,
                'remarks' => $this->remarks,
            ]
        ];
    }
}
