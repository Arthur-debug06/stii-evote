<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class position extends Model
{
    //
    use HasFactory, SoftDeletes;
    protected $primaryKey = 'id';
    protected $table = 'position';
    protected $fillable = ['position_name', 'allowed_number_to_vote', 'status'];
    public $timestamps = true;

    // Scope to get only active positions
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
