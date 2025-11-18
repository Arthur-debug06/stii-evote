<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\students;
use App\Traits\LoggerTrait;
use App\Traits\SignatoryTrait;

class meeting_de_abanse extends Model
{
    //
    use HasFactory, SoftDeletes, LoggerTrait, SignatoryTrait;
    protected $table = 'meeting_de_abanse';
    protected $primaryKey = 'id';
    protected $fillable = ['students_id', 'meeting_de_abanse_name', 'description', 'start_datetime', 'end_datetime','status'];
    public $timestamps = true;

    public function students()
    {
        return $this->belongsTo(students::class, 'students_id');
    }
}
