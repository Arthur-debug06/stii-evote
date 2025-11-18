<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\LoggerTrait;

class system_settings extends Model
{
    //
    use HasFactory, SoftDeletes, LoggerTrait;
    protected $table = 'system_settings';
    protected $primaryKey = 'id';
    protected $fillable = ['key', 'value', 'type','module_id','description','status'];
    public $timestamps = true;

    // Scope to get only active system settings
    public function scopeActiveSystemSettings($query)
    {
        return $query->where('status', 'active');
    }
}
