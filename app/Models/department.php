<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class department extends Model
{
    //
    use HasFactory, SoftDeletes;
    protected $table = 'department';
    protected $primaryKey = 'id';
    protected $fillable = ['department_name', 'description', 'status'];
    public $timestamps = true;

    // Scope to get only active departments
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
