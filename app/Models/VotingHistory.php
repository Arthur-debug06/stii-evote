<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VotingHistory extends Model
{
    protected $table = 'voting_histories';

    protected $fillable = [
        'voting_exclusive_id', 'title', 'school_year_id', 'start_datetime', 'end_datetime',
        'total_voters', 'total_votes', 'winner_summary', 'result_summary'
    ];

    protected $casts = [
        'result_summary' => 'array',
        'start_datetime' => 'datetime',
        'end_datetime' => 'datetime'
    ];

    public function votingExclusive()
    {
        return $this->belongsTo(\App\Models\voting_exclusive::class, 'voting_exclusive_id');
    }

    public function schoolYear()
    {
        return $this->belongsTo(\App\Models\school_year_and_semester::class, 'school_year_id');
    }
}
