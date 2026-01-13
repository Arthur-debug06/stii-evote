<?php

namespace App\Services;

use App\Models\voting_vote_count;
use App\Models\voting_exclusive;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class VotingStatusService
{
    /**
     * Process all expired voting exclusives and update win/loss status
     */
    public static function processExpiredVotings()
    {
        try {
            $processedCount = voting_vote_count::processExpiredVotings();
            
            if ($processedCount > 0) {
                Log::info("Processed {$processedCount} expired voting exclusives and updated win/loss status");
            }
            
            return $processedCount;
        } catch (\Exception $e) {
            Log::error('Error processing expired votings: ' . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Get voting statistics for dashboard
     */
    public static function getVotingStats()
    {
        $now = Carbon::now();
        
        return [
            'active_votings' => voting_exclusive::where('status', 'active')
                                              ->where('start_datetime', '<=', $now)
                                              ->where('end_datetime', '>=', $now)
                                              ->count(),
            'completed_votings' => voting_exclusive::where('status', 'completed')->count(),
            'upcoming_votings' => voting_exclusive::where('status', 'active')
                                                 ->where('start_datetime', '>', $now)
                                                 ->count(),
            'total_candidates' => voting_vote_count::where('status', 'official')->count(),
            'winners' => voting_vote_count::where('status', 'win')->count(),
            'losers' => voting_vote_count::where('status', 'loss')->count(),
        ];
    }
    
    /**
     * Check if there are any votings that need status updates
     */
    public static function hasExpiredVotings()
    {
        $now = Carbon::now();
        
        return voting_exclusive::where('end_datetime', '<=', $now)
                              ->where('status', 'active')
                              ->exists();
    }
}
