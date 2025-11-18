<?php

namespace App\Livewire\CandidatesPosition;

use Livewire\Component;
use App\Models\applied_candidacy;
use App\Models\school_year_and_semester;

class CandidatesPosition extends Component
{
    public $search = '';
    public $approvedCandidacies = [];
    public $candidatesByPosition = [];

    public function mount()
    {
        $this->loadApprovedCandidacies();
    }

    public function loadApprovedCandidacies()
    {
        // Get the active school year
        $activeSchoolYear = school_year_and_semester::active()->first();
        
        if (!$activeSchoolYear) {
            $this->approvedCandidacies = [];
            $this->candidatesByPosition = [];
            return;
        }

        // Get approved candidacies for the current school year
        $query = applied_candidacy::where('status', 'approved')
            ->where('school_year_and_semester_id', $activeSchoolYear->id)
            ->with(['students', 'position', 'school_year_and_semester']);

        // Apply search filter if provided
        if (!empty($this->search)) {
            $query->whereHas('students', function($q) {
                $q->where('first_name', 'like', '%' . $this->search . '%')
                  ->orWhere('last_name', 'like', '%' . $this->search . '%')
                  ->orWhere('student_id', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            })->orWhereHas('position', function($q) {
                $q->where('position_name', 'like', '%' . $this->search . '%');
            });
        }

        $this->approvedCandidacies = $query->get();

        // Group candidates by position using a different approach
        $this->candidatesByPosition = [];
        foreach ($this->approvedCandidacies as $candidacy) {
            $positionName = $candidacy->position ? $candidacy->position->position_name : 'Unknown Position';
            if (!isset($this->candidatesByPosition[$positionName])) {
                $this->candidatesByPosition[$positionName] = collect();
            }
            $this->candidatesByPosition[$positionName]->push($candidacy);
        }
    }

    public function updatedSearch()
    {
        $this->loadApprovedCandidacies();
    }

    public function render()
    {
        return view('livewire.candidates-position.candidates-position');
    }
}
