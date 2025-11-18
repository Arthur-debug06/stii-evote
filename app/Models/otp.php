<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class otp extends Model
{
    //
    use HasFactory, SoftDeletes;
    protected $table = 'otp';
    protected $primaryKey = 'id';
    protected $fillable = ['email_from_id', 'email', 'otp_number', 'status', 'expired_at'];
    public $timestamps = true;

    public function user()
    {
        if ($this->email_from_id === 'User') {
            return $this->belongsTo(User::class, 'email', 'email');
        } elseif ($this->email_from_id === 'students') {
            return $this->belongsTo(students::class, 'email', 'email');
        }
        return null;
    }
}
