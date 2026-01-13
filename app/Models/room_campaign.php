<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\students;
use App\Traits\LoggerTrait;

class room_campaign extends Model
{
    use HasFactory, SoftDeletes, LoggerTrait;
    
    protected $table = 'room_campaign';
    protected $primaryKey = 'id';
    protected $fillable = [
        'students_id', 
        'room_name', 
        'description',
        'start_datetime',
        'end_datetime',
        'status'
    ];
    public $timestamps = true;

    public function students()
    {
        return $this->belongsTo(students::class, 'students_id');
    }
}
