<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\department;

class course extends Model
{
    //
    use HasFactory, SoftDeletes;
    protected $table = 'course';
    protected $primaryKey = 'id';
    protected $fillable = ['department_id', 'course_name', 'description', 'status'];
    public $timestamps = true;

    // Scope to get only active courses
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Relation to department
    public function department()
    {
        return $this->belongsTo(department::class, 'department_id');
    }
}
