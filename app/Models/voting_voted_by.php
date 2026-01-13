<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\voting_vote_count;
use App\Models\students;

class voting_voted_by extends Model
{
    //
    protected $table = 'voting_voted_by';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $fillable = ['voting_vote_count_id', 'students_id', 'status'];
    public function voting_vote_count()
    {
        return $this->belongsTo(voting_vote_count::class, 'voting_vote_count_id');
    }
    public function student()
    {
        return $this->belongsTo(students::class, 'students_id');
    }
}
