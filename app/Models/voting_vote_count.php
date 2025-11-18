<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\voting_exclusive;
use App\Models\students;
use App\Models\applied_candidacy;
use App\Models\position;
use Carbon\Carbon;

class voting_vote_count extends Model
{
    //
    protected $table = 'voting_vote_count';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $fillable = ['voting_exclusive_id', 'students_id', 'number_of_vote','status'];
    
    public function voting_exclusive()
    {
        return $this->belongsTo(voting_exclusive::class, 'voting_exclusive_id');
    }
    
    public function student()
    {
        return $this->belongsTo(students::class, 'students_id');
    }
    
    public function appliedCandidacy()
    {
        return $this->hasOne(applied_candidacy::class, 'students_id', 'students_id')
                    ->where('status', 'approved');
    }
    
    /**
     * Get the position for this candidate
     */
    public function getPositionAttribute()
    {
        $candidacy = $this->appliedCandidacy;
        return $candidacy ? $candidacy->position : null;
    }
    
    /**
     * Update win/loss status for all candidates in a voting exclusive
     */
    public static function updateWinLossStatus($votingExclusiveId)
    {
        // Get all candidates for this voting exclusive
        $candidates = self::where('voting_exclusive_id', $votingExclusiveId)
                          ->where('status', 'official')
                          ->get();
        
        // Group candidates by position
        $candidatesByPosition = $candidates->groupBy(function ($candidate) {
            $position = $candidate->position;
            return $position ? $position->id : 'unknown';
        });
        
        foreach ($candidatesByPosition as $positionId => $positionCandidates) {
            if ($positionId === 'unknown') continue;
            
            // Get the position to determine how many winners allowed
            $position = position::find($positionId);
            if (!$position) continue;
            
            $allowedWinners = $position->allowed_number_to_vote;
            
            // Sort candidates by vote count (descending)
            $sortedCandidates = $positionCandidates->sortByDesc('number_of_vote');
            
            // Update status based on ranking
            $rank = 1;
            foreach ($sortedCandidates as $candidate) {
                if ($rank <= $allowedWinners) {
                    $candidate->update(['status' => 'win']);
                } else {
                    $candidate->update(['status' => 'loss']);
                }
                $rank++;
            }
        }
    }
    
    /**
     * Check and update status for all expired voting exclusives
     */
    public static function processExpiredVotings()
    {
        $now = Carbon::now();
        
        // Get all voting exclusives that have ended but haven't been processed
        $expiredVotings = voting_exclusive::where('end_datetime', '<=', $now)
                                         ->where('status', 'active')
                                         ->get();
        
        foreach ($expiredVotings as $voting) {
            // Update win/loss status for this voting
            self::updateWinLossStatus($voting->id);
            
            // Mark the voting as completed
            $voting->update(['status' => 'completed']);

            // Create a voting history snapshot (idempotent)
            try {
                \App\Http\Controllers\VotingHistoryController::createHistoryForVoting($voting);
            } catch (\Exception $e) {
                // Log but don't halt processing
                \Log::error('Failed to create voting history for voting ' . $voting->id . ': ' . $e->getMessage());
            }
        }
        
        return $expiredVotings->count();
    }
}
