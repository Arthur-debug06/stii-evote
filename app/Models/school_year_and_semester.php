<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class school_year_and_semester extends Model
{
    //
    use HasFactory, SoftDeletes;
    protected $table = 'school_year_and_semester';
    protected $primaryKey = 'id';
    protected $fillable = ['school_year', 'semester', 'status'];
    public $timestamps = true;

    // Scope to get only active school year and semesters
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
