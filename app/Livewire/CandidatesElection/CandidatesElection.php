<?php

namespace App\Livewire\CandidatesElection;

use Livewire\Component;
use App\Models\voting_vote_count;
use App\Models\voting_exclusive;
use App\Models\school_year_and_semester;

class CandidatesElection extends Component
{
    public $search = '';
    public $schoolYearFilter = '';
    public $votingExclusiveFilter = '';
    public $voteCounts = [];
    public $candidatesByPosition = [];
    public $activeVotings = [];
    public $schoolYears = [];
    public $votingExclusives = [];
    // Explicit display order for positions
    public $positionDisplayOrder = [
        'President',
        'Vice President-Internal',
        'Vice President-External',
        'Secretary',
        'Treasurer',
        'Auditor',
        'P.I.O',
        'SGT At Arms',
        'Business Managers',
    ];

    public function mount()
    {
        $this->schoolYears = school_year_and_semester::orderBy('school_year', 'desc')->get();
        $this->votingExclusives = voting_exclusive::with(['schoolYear', 'department', 'course'])
            ->orderBy('created_at', 'desc')
            ->get();
        $this->loadElectionData();
    }

    public function loadElectionData()
    {
        // Get both winning and losing candidates so results show full ranking
        $query = voting_vote_count::with(['student', 'appliedCandidacy.position', 'voting_exclusive.schoolYear'])
                ->whereIn('status', ['win', 'loss']);

        // Apply voting exclusive filter if provided
        if (!empty($this->votingExclusiveFilter)) {
            $query->where('voting_exclusive_id', $this->votingExclusiveFilter);
        }

        // Apply school year filter if provided
        if (!empty($this->schoolYearFilter)) {
            $query->whereHas('voting_exclusive', function($q) {
                $q->where('school_year_id', $this->schoolYearFilter);
            });
        }

        // Apply search filter if provided
        if (!empty($this->search)) {
            $query->whereHas('student', function($q) {
                $q->where('first_name', 'like', '%' . $this->search . '%')
                  ->orWhere('last_name', 'like', '%' . $this->search . '%')
                  ->orWhere('student_id', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            })->orWhereHas('appliedCandidacy.position', function($q) {
                $q->where('position_name', 'like', '%' . $this->search . '%');
            });
        }

        $this->voteCounts = $query->get();

        // Get all voting exclusives for display
        $this->activeVotings = voting_exclusive::all();

        // Group candidates by position name
        $grouped = [];
        foreach ($this->voteCounts as $voteCount) {
            $position = $voteCount->appliedCandidacy?->position;
            if ($position) {
                $positionName = $position->position_name;
                if (!isset($grouped[$positionName])) {
                    $grouped[$positionName] = collect();
                }
                $grouped[$positionName]->push($voteCount);
            }
        }

        // Sort candidates within each position by vote count (descending)
        foreach ($grouped as $positionName => $candidates) {
            $grouped[$positionName] = $candidates->sortByDesc('number_of_vote')->values();
        }

        // Reorder according to explicit display order and keep only positions present
        $ordered = [];
        foreach ($this->positionDisplayOrder as $posName) {
            if (isset($grouped[$posName])) {
                $ordered[$posName] = $grouped[$posName];
            }
        }

        $this->candidatesByPosition = $ordered;
    }

    public function updatedSearch()
    {
        $this->loadElectionData();
    }

    public function updatedSchoolYearFilter()
    {
        $this->loadElectionData();
    }

    public function updatedVotingExclusiveFilter()
    {
        $this->loadElectionData();
    }

    public function render()
    {
        return view('livewire.candidates-election.candidates-election');
    }
}
