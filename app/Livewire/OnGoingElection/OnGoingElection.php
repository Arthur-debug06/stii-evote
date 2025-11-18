<?php

namespace App\Livewire\OnGoingElection;

use Livewire\Component;
use App\Models\voting_exclusive;
use App\Models\voting_vote_count;
use App\Models\voting_voted_by;
use App\Models\students;
use App\Models\position;
use App\Models\applied_candidacy;
use App\Models\department;
use App\Models\course;
use App\Models\school_year_and_semester;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class OnGoingElection extends Component
{
    public $activeVotingExclusives = [];
    public $selectedCandidates = [];
    // Track previous selections to detect newly added items
    public $previousSelectedCandidates = [];
    public $currentVoterId = null;
    public $positionVoteCounts = []; // Track votes per position
    public $showWarningModal = false;
    public $warningMessage = '';
    public $showVoteConfirmationModal = false;
    // Image modal state
    public $showImageModal = false;
    public $imageModalSrc = null;
    // (Department filter removed) The component automatically shows elections relevant to the current student.
    public $hasVoted = false;
    public $currentStudent = null;
    public $canVote = false;
    public $canViewCandidates = false;
    public $upcomingVoting = null;
    // Disable front-end pagination for positions by default so all positions render on one page.
    // Set to true if you want to enable the pagination UI again.
    public $enablePagination = false;

    protected $rules = [
        'selectedCandidates' => 'required|array|min:1',
    ];

    protected $messages = [
        'selectedCandidates.required' => 'Please select at least one candidate to vote.',
        'selectedCandidates.min' => 'Please select at least one candidate to vote.',
        // Note: individual candidate existence is validated during submission to allow both vote_count IDs and student IDs
    ];

    public function mount()
    {
        $this->currentVoterId = Auth::guard('students')->id();
        $this->currentStudent = students::find($this->currentVoterId);
        $this->loadActiveVotingExclusives();
        $this->initializePositionVoteCounts();
        $this->checkIfUserHasVoted();
        $this->checkVotingPermissions();
        // Initialize previous selections
        $this->previousSelectedCandidates = is_array($this->selectedCandidates) ? $this->selectedCandidates : [];
    }

    public function initializePositionVoteCounts()
    {
        $this->positionVoteCounts = [];
        foreach ($this->activeVotingExclusives as $exclusive) {
            foreach ($exclusive['candidates_by_position'] as $positionName => $positionData) {
                $this->positionVoteCounts[$positionName] = 0;
            }
        }
    }

    public function checkIfUserHasVoted()
    {
        if ($this->currentVoterId) {
            $this->hasVoted = voting_voted_by::where('students_id', $this->currentVoterId)->exists();
        }
    }

    public function checkVotingPermissions()
    {
        if (!$this->currentStudent || empty($this->activeVotingExclusives)) {
            $this->canVote = false;
            $this->canViewCandidates = false;
            return;
        }

        $this->canVote = false;
        $this->canViewCandidates = false;

        foreach ($this->activeVotingExclusives as $exclusive) {
            // Skip general elections (no specific department/course restrictions)
            if (isset($exclusive['is_general']) && $exclusive['is_general']) {
                $this->canVote = true;
                $this->canViewCandidates = true;
                continue;
            }

            // If both department_id and course_id are NULL, allow everyone to vote
            if (is_null($exclusive['department_id']) && is_null($exclusive['course_id'])) {
                $this->canVote = true;
                $this->canViewCandidates = true;
                continue;
            }

            // Check department match (NULL means all departments)
            $departmentMatches = is_null($exclusive['department_id']) || 
                                $this->currentStudent->department_id == $exclusive['department_id'];

            // Check course match (NULL means all courses)
            $courseMatches = is_null($exclusive['course_id']) || 
                            $this->currentStudent->course_id == $exclusive['course_id'];

            if ($departmentMatches && $courseMatches) {
                // Full match - can vote and view candidates
                $this->canVote = true;
                $this->canViewCandidates = true;
            } elseif ($departmentMatches && !$courseMatches) {
                // Department matches but course doesn't - can only view candidates
                $this->canViewCandidates = true;
            }
        }
    }

    public function canVoteInExclusive($exclusive)
    {
        if (!$this->currentStudent) {
            return false;
        }

        // General elections allow everyone to vote
        if (isset($exclusive['is_general']) && $exclusive['is_general']) {
            return true;
        }

        // If both department_id and course_id are NULL, allow everyone to vote
        if (is_null($exclusive['department_id']) && is_null($exclusive['course_id'])) {
            return true;
        }

        // Check department match (NULL means all departments)
        $departmentMatches = is_null($exclusive['department_id']) || 
                            $this->currentStudent->department_id == $exclusive['department_id'];

        // Check course match (NULL means all courses)
        $courseMatches = is_null($exclusive['course_id']) || 
                        $this->currentStudent->course_id == $exclusive['course_id'];

        // Can vote only if both department and course match
        return $departmentMatches && $courseMatches;
    }

    public function updatedSelectedCandidates()
    {
        // Ensure selectedCandidates is always an array
        if (!is_array($this->selectedCandidates)) {
            $this->selectedCandidates = [];
        }
        // Only validate when there's at least one selection
        if (!empty($this->selectedCandidates)) {
            try {
                $this->validateOnly('selectedCandidates');
            } catch (\Exception $e) {
                // If validation fails for realtime changes, don't block UI — we'll show errors on submit
            }
        }
        
    $this->positionVoteCounts = [];

    // Determine what was newly added (current - previous)
    $currentSelections = $this->selectedCandidates;
    $previousSelections = is_array($this->previousSelectedCandidates) ? $this->previousSelectedCandidates : [];
    $newlyAdded = array_values(array_diff($currentSelections, $previousSelections));
        
        // Count votes per position
        foreach ($this->selectedCandidates as $candidateId) {
            foreach ($this->activeVotingExclusives as $exclusive) {
                foreach ($exclusive['candidates_by_position'] as $positionName => $positionData) {
                    foreach ($positionData['candidates'] as $candidate) {
                        $candidateIdToCheck = isset($candidate->id) ? $candidate->id : $candidate->students_id;
                        if ($candidateIdToCheck == $candidateId) {
                            if (!isset($this->positionVoteCounts[$positionName])) {
                                $this->positionVoteCounts[$positionName] = 0;
                            }
                            $this->positionVoteCounts[$positionName]++;
                        }
                    }
                }
            }
        }

        // If there are newly added selections, validate per-position limits and revert if needed
        if (!empty($newlyAdded)) {
            foreach ($newlyAdded as $newId) {
                // Find the position of this candidate
                $foundPosition = null;
                foreach ($this->activeVotingExclusives as $exclusive) {
                    foreach ($exclusive['candidates_by_position'] as $posName => $posData) {
                        foreach ($posData['candidates'] as $candidate) {
                            $candidateIdToCheck = isset($candidate->id) ? $candidate->id : $candidate->students_id;
                            if ((string)$candidateIdToCheck === (string)$newId) {
                                $foundPosition = $posName;
                                $allowed = isset($posData['allowed_votes']) ? $posData['allowed_votes'] : 1;
                                break 3;
                            }
                        }
                    }
                }

                if ($foundPosition) {
                    $currentCount = $this->positionVoteCounts[$foundPosition] ?? 0;
                    if ($currentCount > $allowed) {
                        // Revert this selection
                        $this->selectedCandidates = array_values(array_filter($this->selectedCandidates, function ($id) use ($newId) {
                            return (string)$id !== (string)$newId;
                        }));

                        // Set warning
                        $this->warningMessage = "You can only vote for {$allowed} candidate(s) for the {$foundPosition} position. Please deselect a candidate first if you want to vote for someone else.";
                        $this->showWarningModal = true;
                        // Recalculate counts after revert
                        $this->positionVoteCounts[$foundPosition] = $allowed; // ensure not exceeding
                    }
                }
            }
        }

        // If a warning modal was shown previously (voting limit exceeded),
        // check whether the current selections have dropped below the allowed votes
        // for any position. If so, clear the warning so the user can select again.
        if ($this->showWarningModal) {
            $cleared = false;
            foreach ($this->activeVotingExclusives as $exclusive) {
                foreach ($exclusive['candidates_by_position'] as $positionName => $positionData) {
                    $allowed = isset($positionData['allowed_votes']) ? $positionData['allowed_votes'] : 1;
                    $current = isset($this->positionVoteCounts[$positionName]) ? $this->positionVoteCounts[$positionName] : 0;
                    // If current selections for this position are now less than allowed,
                    // clear the warning/modal so the user can attempt selection again.
                    if ($current < $allowed) {
                        $this->showWarningModal = false;
                        $this->warningMessage = '';
                        $cleared = true;
                        break;
                    }
                }
                if ($cleared) {
                    break;
                }
            }
        }

        // Update previous selections snapshot for next change
        $this->previousSelectedCandidates = is_array($this->selectedCandidates) ? $this->selectedCandidates : [];
    }

    public function canSelectCandidate($candidateId, $positionName)
    {
        // Ensure selectedCandidates is always an array
        if (!is_array($this->selectedCandidates)) {
            $this->selectedCandidates = [];
        }
        
        $allowedVotes = 1;
        
        // Get allowed votes for this position
        foreach ($this->activeVotingExclusives as $exclusive) {
            if (isset($exclusive['candidates_by_position'][$positionName])) {
                $allowedVotes = $exclusive['candidates_by_position'][$positionName]['allowed_votes'];
                break;
            }
        }
        
        $currentVotes = $this->positionVoteCounts[$positionName] ?? 0;
        
        // If already selected, allow deselection
        if (in_array($candidateId, $this->selectedCandidates)) {
            return true;
        }
        
        // Check if we can select more candidates for this position
        return $currentVotes < $allowedVotes;
    }

    public function attemptSelectCandidate($candidateId, $positionName)
    {
        // Ensure selectedCandidates is always an array
        if (!is_array($this->selectedCandidates)) {
            $this->selectedCandidates = [];
        }
        // If the candidate is already selected, treat this as a deselect (toggle)
        if (in_array($candidateId, $this->selectedCandidates)) {
            // Remove candidate from selections
            $this->selectedCandidates = array_values(array_filter($this->selectedCandidates, function ($id) use ($candidateId) {
                return (string)$id !== (string)$candidateId;
            }));

            // Recalculate counts and clear warnings if needed
            $this->updatedSelectedCandidates();
            return;
        }

        if ($this->canSelectCandidate($candidateId, $positionName)) {
            // Add to selected candidates
            if (!in_array($candidateId, $this->selectedCandidates)) {
                $this->selectedCandidates[] = $candidateId;
            }
            // After adding, recalc counts
            $this->updatedSelectedCandidates();
        } else {
            // Show warning modal
            $allowedVotes = 1;
            foreach ($this->activeVotingExclusives as $exclusive) {
                if (isset($exclusive['candidates_by_position'][$positionName])) {
                    $allowedVotes = $exclusive['candidates_by_position'][$positionName]['allowed_votes'];
                    break;
                }
            }
            
            $this->warningMessage = "You can only vote for {$allowedVotes} candidate(s) for the {$positionName} position. Please deselect a candidate first if you want to vote for someone else.";
            $this->showWarningModal = true;
        }
    }

    public function closeWarningModal()
    {
        $this->showWarningModal = false;
        $this->warningMessage = '';
    }

    public function showVoteConfirmation()
    {
        // Ensure selectedCandidates is always an array
        if (!is_array($this->selectedCandidates)) {
            $this->selectedCandidates = [];
        }

        if (empty($this->selectedCandidates)) {
            $this->dispatch('show-toast', [
                'message' => 'Please select at least one candidate to vote.',
                'type' => 'error',
                'title' => 'No Selection'
            ]);
            return;
        }

        $this->showVoteConfirmationModal = true;
    }

    public function cancelVote()
    {
        $this->showVoteConfirmationModal = false;
    }

    public function confirmVote()
    {
        $this->showVoteConfirmationModal = false;
        $this->submitVote();
    }

    /**
     * Open image modal with given image src
     */
    public function openImageModal($src)
    {
        $this->imageModalSrc = $src;
        $this->showImageModal = true;
    }

    public function closeImageModal()
    {
        $this->showImageModal = false;
        $this->imageModalSrc = null;
    }

    public function updatedSelectedDepartment()
    {
        // kept for compatibility but not used; reload data if somehow called
        $this->loadActiveVotingExclusives();
        $this->initializePositionVoteCounts();
        $this->checkVotingPermissions();
    }

    // Public wrapper to be called from the view to avoid calling lifecycle methods directly
    public function filterByDepartment()
    {
        // Department filtering is handled server-side; keep method present but no-op
        $this->updatedSelectedDepartment();
    }

    public function loadActiveVotingExclusives()
    {
        // Get voting exclusives with status = 'active'
        // Only include those that are relevant to the current logged-in student:
        // - General elections (both department_id and course_id are NULL)
        // - Or those where (department_id is NULL or equals student's department) AND (course_id is NULL or equals student's course)
        $now = Carbon::now();
        $activeQuery = voting_exclusive::where('status', 'active');

        if ($this->currentStudent) {
            $student = $this->currentStudent;
            $activeQuery->where(function ($q) use ($student) {
                // general elections
                $q->where(function ($q2) {
                    $q2->whereNull('department_id')->whereNull('course_id');
                })
                // or elections that match student's department/course (NULL means all)
                ->orWhere(function ($q3) use ($student) {
                    $q3->where(function ($q4) use ($student) {
                        $q4->whereNull('department_id')->orWhere('department_id', $student->department_id);
                    })->where(function ($q5) use ($student) {
                        $q5->whereNull('course_id')->orWhere('course_id', $student->course_id);
                    });
                });
            });
        }

        $activeExclusives = $activeQuery->get();

        // Note: we intentionally do NOT use a selectable department filter here.
        // This component will only show elections that apply to the current student
        // (their department) or general elections (both department_id and course_id NULL).

        // Always set the next upcoming voting exclusive (if any) based on start_datetime in the future
        $this->upcomingVoting = voting_exclusive::where('status', 'active')
            ->where('start_datetime', '>', $now)
            ->orderBy('start_datetime')
            ->first();

        // If there are no currently ongoing (time-window) active exclusives,
        // do not fall back to showing general/approved candidates. Leave list empty,
        // but still expose upcomingVoting for the UI.
        if ($activeExclusives->isEmpty()) {
            $this->activeVotingExclusives = collect();
            return;
        }

        if ($activeExclusives->isNotEmpty()) {
            $mapped = $activeExclusives->map(function ($exclusive) {
                // Get all candidates for this voting exclusive (any status)
                $voteCounts = voting_vote_count::where('voting_exclusive_id', $exclusive->id)
                    ->get();

                $candidatesByPosition = collect();
                
                foreach ($voteCounts as $voteCount) {
                    $student = students::find($voteCount->students_id);
                    if ($student) {
                        $candidacy = applied_candidacy::where('students_id', $student->id)
                            ->where('status', 'approved')
                            ->first();
                        
                        if ($candidacy) {
                            $position = position::withTrashed()->find($candidacy->position_id);
                            $positionName = $position ? $position->position_name : 'Unknown Position';
                            $allowedVotes = $position ? $position->allowed_number_to_vote : 1;
                            
                            if (!$candidatesByPosition->has($positionName)) {
                                $candidatesByPosition->put($positionName, [
                                    'allowed_votes' => $allowedVotes,
                                    'candidates' => collect()
                                ]);
                            }
                            
                            $candidatesByPosition->get($positionName)['candidates']->push($voteCount);
                        }
                    }
                }
                // Also include any approved applied_candidacy records for this election's school year
                // that may not have a corresponding voting_vote_count record yet.
                // This ensures positions with only approved candidacies still show up.
                $schoolYearId = $exclusive->school_year_id ?? null;
                $additionalCandidacies = applied_candidacy::where('status', 'approved')
                    ->when($schoolYearId, function ($q) use ($schoolYearId) {
                        // applied_candidacy table uses school_year_and_semester_id
                        return $q->where('school_year_and_semester_id', $schoolYearId);
                    })
                    ->get();

                foreach ($additionalCandidacies as $candidacy) {
                    $position = position::withTrashed()->find($candidacy->position_id);
                    $positionName = $position ? $position->position_name : 'Unknown Position';
                    $allowedVotes = $position ? $position->allowed_number_to_vote : 1;

                    if (!$candidatesByPosition->has($positionName)) {
                        $candidatesByPosition->put($positionName, [
                            'allowed_votes' => $allowedVotes,
                            'candidates' => collect()
                        ]);
                    }

                    // Avoid duplicates: check if this student's id already exists in the candidates list
                    $existingStudentIds = collect($candidatesByPosition->get($positionName)['candidates'])
                        ->map(function ($candidate) {
                            return $candidate->students_id ?? ($candidate->student->id ?? ($candidate->students->id ?? null));
                        })->filter()->values()->all();

                    if (!in_array($candidacy->students_id, $existingStudentIds)) {
                        $candidatesByPosition->get($positionName)['candidates']->push($candidacy);
                    }
                }
                // No external department filter: candidates are left as-is.

                // Convert inner candidate Collections to plain arrays and ensure keys are preserved
                $candidatesByPosition = $candidatesByPosition->map(function ($posData) {
                    // normalize candidates to simple indexed arrays to avoid serialization/iteration issues
                    $posData['candidates'] = collect($posData['candidates'])->values()->all();
                    return $posData;
                });
                
                // Get department and course info
                $department = $exclusive->department_id ? department::find($exclusive->department_id) : null;
                $course = $exclusive->course_id ? course::find($exclusive->course_id) : null;
                $schoolYear = $exclusive->school_year_id ? school_year_and_semester::find($exclusive->school_year_id) : null;
                
                return [
                    'id' => $exclusive->id,
                    'department_id' => $exclusive->department_id,
                    'course_id' => $exclusive->course_id,
                    'department' => $department ? $department->department_name : 'All Departments',
                    'course' => $course ? $course->course_name : 'All Courses',
                    'school_year' => $schoolYear ? $schoolYear->school_year : 'N/A',
                    'semester' => $schoolYear ? $schoolYear->semester : 'N/A',
                    'start_datetime' => $exclusive->start_datetime,
                    'end_datetime' => $exclusive->end_datetime,
                    'candidates_by_position' => $candidatesByPosition->toArray(),
                    'is_general' => false
                ];
            });
            // Remove any exclusives that (after filtering) have no positions/candidates
            // candidates_by_position has been converted to arrays above, so use array-safe checks
            $mapped = $mapped->filter(function ($ex) {
                return isset($ex['candidates_by_position']) && is_array($ex['candidates_by_position']) && count($ex['candidates_by_position']) > 0;
            })->values();

            $this->activeVotingExclusives = $mapped;
        } else {
            // If no active voting exclusives, get all candidates from voting_vote_count (any status)
            $allOfficialCandidates = voting_vote_count::all();
            
            $candidatesByPosition = collect();
            
            foreach ($allOfficialCandidates as $voteCount) {
                $student = students::find($voteCount->students_id);
                if ($student) {
                    $candidacy = applied_candidacy::where('students_id', $student->id)
                        ->where('status', 'approved')
                        ->first();
                    
                    if ($candidacy) {
                        $position = position::withTrashed()->find($candidacy->position_id);
                        $positionName = $position ? $position->position_name : 'Unknown Position';
                        $allowedVotes = $position ? $position->allowed_number_to_vote : 1;
                        
                        if (!$candidatesByPosition->has($positionName)) {
                            $candidatesByPosition->put($positionName, [
                                'allowed_votes' => $allowedVotes,
                                'candidates' => collect()
                            ]);
                        }
                        
                        $candidatesByPosition->get($positionName)['candidates']->push($voteCount);
                    }
                }
            }

            if ($candidatesByPosition->isNotEmpty()) {
                // normalize inner candidate collections to arrays
                $candidatesArray = $candidatesByPosition->map(function ($posData) {
                    $posData['candidates'] = collect($posData['candidates'])->values()->all();
                    return $posData;
                })->toArray();

                $this->activeVotingExclusives = collect([
                    [
                        'id' => 'general',
                        'department_id' => null,
                        'course_id' => null,
                        'department' => 'All Departments',
                        'course' => 'All Courses',
                        'school_year' => 'Current',
                        'semester' => 'Semester',
                        'start_datetime' => null,
                        'end_datetime' => null,
                        'candidates_by_position' => $candidatesArray,
                        'is_general' => true
                    ]
                ]);
            } else {
                // If no voting vote count records, show all approved candidates directly
                $approvedCandidates = applied_candidacy::where('status', 'approved')->get();
                
                $candidatesByPosition = collect();
                
                foreach ($approvedCandidates as $candidacy) {
                    $position = position::withTrashed()->find($candidacy->position_id);
                    $positionName = $position ? $position->position_name : 'Unknown Position';
                    $allowedVotes = $position ? $position->allowed_number_to_vote : 1;
                    
                    if (!$candidatesByPosition->has($positionName)) {
                        $candidatesByPosition->put($positionName, [
                            'allowed_votes' => $allowedVotes,
                            'candidates' => collect()
                        ]);
                    }
                    
                    $candidatesByPosition->get($positionName)['candidates']->push($candidacy);
                }

                if ($candidatesByPosition->isNotEmpty()) {
                    $candidatesArray = $candidatesByPosition->map(function ($posData) {
                        $posData['candidates'] = collect($posData['candidates'])->values()->all();
                        return $posData;
                    })->toArray();

                    $this->activeVotingExclusives = collect([
                        [
                            'id' => 'approved_candidates',
                            'department_id' => null,
                            'course_id' => null,
                            'department' => 'All Departments',
                            'course' => 'All Courses',
                            'school_year' => 'Current',
                            'semester' => 'Semester',
                            'start_datetime' => null,
                            'end_datetime' => null,
                            'candidates_by_position' => $candidatesArray,
                            'is_general' => true,
                            'is_approved_only' => true
                        ]
                    ]);
                } else {
                    $this->activeVotingExclusives = collect();
                }
            }
        }
    }


    public function submitVote()
    {
        $this->validate();

        try {
            // Resolve vote counts for all selected candidates first (create missing voteCount entries)
            $resolvedVoteCounts = [];
            foreach ($this->selectedCandidates as $candidateId) {
                $voteCount = voting_vote_count::find($candidateId);

                if (!$voteCount) {
                    // This might be a student ID from approved candidates, create a vote count record
                    $student = students::find($candidateId);
                    if ($student) {
                        // Create a general voting exclusive if it doesn't exist
                        $generalExclusive = voting_exclusive::firstOrCreate(
                            ['id' => 'general'],
                            [
                                'department_id' => null,
                                'course_id' => null,
                                'school_year_id' => 1, // Default school year
                                'start_datetime' => Carbon::now(),
                                'end_datetime' => Carbon::now()->addDays(30),
                                'status' => 'active'
                            ]
                        );

                        // Create vote count record
                        $voteCount = voting_vote_count::firstOrCreate([
                            'voting_exclusive_id' => $generalExclusive->id,
                            'students_id' => $candidateId,
                        ], [
                            'number_of_vote' => 0,
                            'status' => 'official'
                        ]);
                    }
                }

                if ($voteCount) {
                    $resolvedVoteCounts[] = $voteCount;
                }
            }

            // Determine voting_exclusive ids involved and ensure the student hasn't voted in any of them yet
            $exclusiveIds = collect($resolvedVoteCounts)->pluck('voting_exclusive_id')->unique()->filter()->values()->all();

            if (!empty($exclusiveIds)) {
                $hasVotedInExclusive = voting_voted_by::where('students_id', $this->currentVoterId)
                    ->whereHas('voting_vote_count', function($q) use ($exclusiveIds) {
                        $q->whereIn('voting_exclusive_id', $exclusiveIds);
                    })->exists();

                if ($hasVotedInExclusive) {
                    $this->addError('voting', 'You have already voted in this election.');
                    return;
                }
            }

            // Process each resolved voteCount: create a single voting_voted_by per student per voting_exclusive and increment votes
            foreach ($resolvedVoteCounts as $voteCount) {
                // Only insert voting_voted_by once per student per voting_exclusive
                $alreadyRecorded = voting_voted_by::where('students_id', $this->currentVoterId)
                    ->whereHas('voting_vote_count', function($q) use ($voteCount) {
                        $q->where('voting_exclusive_id', $voteCount->voting_exclusive_id);
                    })->exists();

                if (!$alreadyRecorded) {
                    voting_voted_by::create([
                        'voting_vote_count_id' => $voteCount->id,
                        'students_id' => $this->currentVoterId,
                        'status' => 'voted'
                    ]);
                }

                // Increment vote count for this candidate
                $voteCount->increment('number_of_vote');
            }

            // Clear selections
            $this->selectedCandidates = [];
            
            // Mark user as having voted
            $this->hasVoted = true;
            
            // Send email notification
            $this->sendVoteConfirmationEmail();
            
            // Reload data
            $this->loadActiveVotingExclusives();

            session()->flash('success', 'Your vote has been submitted successfully! A confirmation email has been sent to your registered email address.');
            
        } catch (\Exception $e) {
            $this->addError('voting', 'An error occurred while submitting your vote. Please try again.');
        }
    }

    public function sendVoteConfirmationEmail()
    {
        try {
            // Get current student
            $student = students::find($this->currentVoterId);
            if (!$student || !$student->email) {
                return; // No email to send to
            }

            // Get the voted candidates data
            $votedCandidates = [];
            $totalVotes = 0;

            // Get the selected candidates from the last vote submission
            $recentVotes = voting_voted_by::where('students_id', $this->currentVoterId)
                ->with(['voting_vote_count.student', 'voting_vote_count.appliedCandidacy.position'])
                ->get();

            foreach ($recentVotes as $vote) {
                $voteCount = $vote->voting_vote_count;
                if ($voteCount && $voteCount->student && $voteCount->appliedCandidacy) {
                    $position = $voteCount->appliedCandidacy->position;
                    $positionName = $position ? $position->position_name : 'Unknown Position';
                    
                    if (!isset($votedCandidates[$positionName])) {
                        $votedCandidates[$positionName] = [];
                    }
                    
                    $votedCandidates[$positionName][] = [
                        'name' => $voteCount->student->first_name . ' ' . $voteCount->student->last_name,
                        'student_id' => $voteCount->student->student_id,
                        'course' => $voteCount->student->course->course_name ?? 'N/A'
                    ];
                    
                    $totalVotes++;
                }
            }

            // Prepare email data
            $emailData = [
                'student' => $student,
                'votedCandidates' => $votedCandidates,
                'totalVotes' => $totalVotes,
                'voteDate' => Carbon::now()->format('F j, Y \a\t g:i A')
            ];

            // Send email
            Mail::send('emails.vote-confirmation', $emailData, function ($message) use ($student) {
                $message->to($student->email, $student->first_name . ' ' . $student->last_name)
                        ->subject('Vote Confirmation - Student Government Election');
            });

        } catch (\Exception $e) {
            // Log error but don't fail the vote submission
            \Log::error('Failed to send vote confirmation email: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.on-going-election.on-going-election');
    }
}
