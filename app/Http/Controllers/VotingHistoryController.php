<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VotingHistory;
use App\Models\voting_exclusive;
use App\Models\voting_vote_count;
use App\Models\voting_voted_by;
use Carbon\Carbon;

class VotingHistoryController extends Controller
{
    public function index()
    {
        // Only load histories that have a related voting_exclusive
        $histories = VotingHistory::with(['votingExclusive.schoolYear', 'schoolYear'])
            ->whereHas('votingExclusive') // prevent null votingExclusive
            ->orderBy('created_at', 'desc')
            ->paginate(25);

        return view('voting-histories.index', compact('histories'));
    }

    public function show($id)
    {
        $history = VotingHistory::with(['votingExclusive.schoolYear', 'schoolYear'])
            ->whereHas('votingExclusive')
            ->findOrFail($id);

        return view('voting-histories.show', compact('history'));
    }

    /**
     * Create and persist a history snapshot for a voting exclusive.
     * Idempotent: if a VotingHistory exists for the voting_exclusive_id, it returns it.
     */
    public static function createHistoryForVoting(voting_exclusive $voting)
{
    // Avoid duplicate history
    $existing = VotingHistory::where('voting_exclusive_id', $voting->id)->first();
    if ($existing) return $existing;

    // Compute totals
    $totalVoters = voting_voted_by::whereHas('voting_vote_count', function($q) use ($voting) {
        $q->where('voting_exclusive_id', $voting->id);
    })->count();

    $totalVotes = voting_vote_count::where('voting_exclusive_id', $voting->id)
        ->where('status', 'official')
        ->sum('number_of_vote');

    // Get all official candidates with relationships
    $candidates = voting_vote_count::with(['student', 'position'])
        ->where('voting_exclusive_id', $voting->id)
        ->where('status', 'official')
        ->get();

    // Group by position (use 'unknown' if position missing)
    $byPosition = $candidates->groupBy(function($c) { 
        return $c->position?->id ?? 'unknown'; 
    });

    $winners = [];
    $winnerSentences = [];

    foreach ($byPosition as $posId => $group) {
        $position = $group->first()->position;
        $positionName = $position?->name ?? 'Unknown Position';
        $allowed = $position?->allowed_number_to_vote ?? 1;

        // Sort by votes descending
        $sorted = $group->sortByDesc('number_of_vote')->values();
        $topCandidates = $sorted->take($allowed);

        $winners[$posId] = [
            'position' => $positionName,
            'winners' => $topCandidates->map(function($c) {
                return [
                    'student_id' => $c->students_id,
                    'student' => $c->student?->only(['first_name', 'last_name', 'student_id']),
                    'votes' => $c->number_of_vote,
                ];
            })->toArray(),
        ];

        // Build human-readable summary for this position
        $names = [];
        $votesArr = [];
        foreach ($topCandidates as $c) {
            if (!$c->student) continue;
            $names[] = trim("{$c->student->first_name} {$c->student->last_name}");
            $votesArr[] = $c->number_of_vote;
        }

        if (empty($names)) continue;

        if (count($names) === 1) {
            $winnerSentences[] = "{$names[0]} elected $positionName ({$votesArr[0]} votes)";
        } else {
            $winnerSentences[] = implode(' and ', $names) . " elected $positionName (" . implode(' and ', $votesArr) . " votes)";
        }
    }

    $winnerSummaryText = !empty($winnerSentences) ? implode(', ', $winnerSentences) . '.' : 'No winners recorded';

    // Result summary object
    $resultSummary = [
        'total_voters' => $totalVoters,
        'total_votes' => $totalVotes,
        'winners_by_position' => $winners,
        'summary_text' => $winnerSummaryText,
    ];

    // Safely build title
    $departmentName = $voting->department?->name ?? '';
    $courseName = $voting->course?->name ?? '';

    // Create the history record
    $record = VotingHistory::create([
        'voting_exclusive_id' => $voting->id,
        'title' => trim("$departmentName $courseName") ?: 'Election Result',
        'school_year_id' => $voting->school_year_id,
        'start_datetime' => $voting->start_datetime,
        'end_datetime' => $voting->end_datetime,
        'total_voters' => $totalVoters,
        'total_votes' => $totalVotes,
        'winner_summary' => $winnerSummaryText,
        'result_summary' => $resultSummary,
    ]);

    return $record;
}

}
