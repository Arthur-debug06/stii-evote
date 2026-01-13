<?php

namespace App\Livewire\VotingExclusive;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\voting_exclusive;
use App\Models\department;
use App\Models\course;
use App\Models\school_year_and_semester;
use App\Models\applied_candidacy;
use App\Models\voting_vote_count;
use Carbon\Carbon;

class VotingExclusive extends Component
{
    use WithPagination;

    // Form properties
    public ?int $department_id = null;
    public ?int $course_id = null;
    public ?string $start_datetime = null;
    public ?string $end_datetime = null;
    public string $status = 'active';

    // UI state properties
    public string $search = '';
    public bool $showAddModal = false;
    public bool $showEditModal = false;
    public bool $showDeleteModal = false;
    public ?int $editingId = null;
    public ?int $deletingId = null;

    protected $rules = [
        'department_id' => 'nullable|exists:department,id',
        'course_id' => 'nullable|exists:course,id',
        'start_datetime' => 'required|date|after:now',
        'end_datetime' => 'required|date|after:start_datetime',
        'status' => 'required|in:active,inactive',
    ];

    protected $messages = [
        'start_datetime.after' => 'Start date must be in the future.',
        'end_datetime.after' => 'End date must be after start date.',
        'start_datetime.required' => 'Start date is required.',
        'end_datetime.required' => 'End date is required.',
    ];

    public function openAddModal()
    {
        $this->resetForm();
        $this->status = 'active';
        $this->showAddModal = true;
    }

    public function openEditModal($id)
    {
        $votingExclusive = voting_exclusive::find($id);
        if ($votingExclusive) {
            $this->editingId = $votingExclusive->id;
            $this->department_id = $votingExclusive->department_id;
            $this->course_id = $votingExclusive->course_id;
            $this->start_datetime = $votingExclusive->start_datetime ? Carbon::parse($votingExclusive->start_datetime)->format('Y-m-d\TH:i') : null;
            $this->end_datetime = $votingExclusive->end_datetime ? Carbon::parse($votingExclusive->end_datetime)->format('Y-m-d\TH:i') : null;
            $this->status = $votingExclusive->status;
            $this->showEditModal = true;
        }
    }

    public function openDeleteModal($id)
    {
        $this->deletingId = $id;
        $this->showDeleteModal = true;
    }

    public function createVotingExclusive()
    {
        $this->validate();
        
        // Ensure minimum duration: more than 1 hour
        if (!$this->hasMinimumDuration()) {
            $this->dispatch('show-toast', [
                'message' => 'The voting period must be more than 1 hour in duration.',
                'type' => 'error',
                'title' => 'Invalid Duration'
            ]);
            return;
        }
        
        // Check for overlapping date ranges
        if ($this->hasOverlappingVotingPeriod()) {
            $this->dispatch('show-toast', [
                'message' => 'Time conflict detected. Please choose different dates or times.',
                'type' => 'error',
                'title' => 'Date Conflict!'
            ]);
            return;
        }

        // Check for active voting exclusive for the same course
        if ($this->hasActiveVotingForSameCourse()) {
            $this->dispatch('show-toast', [
                'message' => 'Active voting is already running for this course. Please wait for it to end.',
                'type' => 'error',
                'title' => 'Active Voting in Progress!'
            ]);
            return;
        }

        // Check for minimum time gap between voting periods
        if (!$this->hasMinimumTimeGap()) {
            $this->dispatch('show-toast', [
                'message' => 'Please schedule at least 1 hour after any existing period ends.',
                'type' => 'error',
                'title' => 'Insufficient Time Gap!'
            ]);
            return;
        }

        try {
            // Get the active school year and semester
            $activeSchoolYear = school_year_and_semester::active()->first();
            
            if (!$activeSchoolYear) {
                $this->dispatch('show-toast', [
                    'message' => 'No active school year found. Please set an active school year first.',
                    'type' => 'error',
                    'title' => 'Error!'
                ]);
                return;
            }

            $votingExclusive = voting_exclusive::create([
                'department_id' => $this->department_id,
                'course_id' => $this->course_id,
                'school_year_id' => $activeSchoolYear->id,
                'start_datetime' => $this->start_datetime,
                'end_datetime' => $this->end_datetime,
                'status' => $this->status,
            ]);

            // Get approved candidacy applications
            $approvedCandidacies = applied_candidacy::where('status', 'approved')
                ->with('students')
                ->get();

            $voteCountCreated = 0;
            foreach ($approvedCandidacies as $candidacy) {
                // Check if student matches the voting exclusive criteria
                $student = $candidacy->students;
                
                // If department is specified, check if student belongs to that department
                if ($this->department_id && $student->department_id != $this->department_id) {
                    continue;
                }
                
                // If course is specified, check if student belongs to that course
                if ($this->course_id && $student->course_id != $this->course_id) {
                    continue;
                }

                // Create voting vote count record
                voting_vote_count::create([
                    'voting_exclusive_id' => $votingExclusive->id,
                    'students_id' => $student->id,
                    'number_of_vote' => 0, // No votes yet
                    'status' => 'official',
                ]);
                
                $voteCountCreated++;
            }

            $this->dispatch('show-toast', [
                'type' => 'success',
                'title' => 'Voting Exclusive Created!',
                'message' => "Voting exclusive created successfully. {$voteCountCreated} candidates added.",
            ]);

            $this->resetForm();
            $this->showAddModal = false;
            $this->dispatch('close-modal', id: 'add-voting-exclusive-modal');
            $this->resetPage();
        } catch (\Exception $e) {
            $this->dispatch('show-toast', [
                'message' => 'Error creating voting exclusive: ' . $e->getMessage(),
                'type' => 'error',
                'title' => 'Error!'
            ]);
        }
    }

    public function updateVotingExclusive()
    {
        if (!$this->editingId) {
            $this->dispatch('show-toast', [
                'message' => 'No voting exclusive selected to update.',
                'type' => 'error',
                'title' => 'Error'
            ]);
            return;
        }

        $this->validate();
        
        // Ensure minimum duration: more than 1 hour
        if (!$this->hasMinimumDuration()) {
            $this->dispatch('show-toast', [
                'message' => 'The voting period must be more than 1 hour in duration.',
                'type' => 'error',
                'title' => 'Invalid Duration'
            ]);
            return;
        }
        
        // Check for overlapping date ranges (excluding current record)
        if ($this->hasOverlappingVotingPeriod($this->editingId)) {
            $this->dispatch('show-toast', [
                'message' => 'Time conflict detected. Please choose different dates or times.',
                'type' => 'error',
                'title' => 'Date Conflict!'
            ]);
            return;
        }

        // Check for active voting exclusive for the same course (excluding current record)
        if ($this->hasActiveVotingForSameCourse($this->editingId)) {
            $this->dispatch('show-toast', [
                'message' => 'Active voting is already running for this course. Please wait for it to end.',
                'type' => 'error',
                'title' => 'Active Voting in Progress!'
            ]);
            return;
        }

        // Check for minimum time gap between voting periods (excluding current record)
        if (!$this->hasMinimumTimeGap($this->editingId)) {
            $this->dispatch('show-toast', [
                'message' => 'Please schedule at least 1 hour after any existing period ends.',
                'type' => 'error',
                'title' => 'Insufficient Time Gap!'
            ]);
            return;
        }

        try {
            $votingExclusive = voting_exclusive::find($this->editingId);
            if (!$votingExclusive) {
                $this->dispatch('show-toast', [
                    'message' => 'Voting exclusive not found.',
                    'type' => 'error',
                    'title' => 'Not Found'
                ]);
                return;
            }

            // Get the active school year and semester
            $activeSchoolYear = school_year_and_semester::active()->first();
            
            if (!$activeSchoolYear) {
                $this->dispatch('show-toast', [
                    'message' => 'No active school year found. Please set an active school year first.',
                    'type' => 'error',
                    'title' => 'Error!'
                ]);
                return;
            }

            $votingExclusive->update([
                'department_id' => $this->department_id,
                'course_id' => $this->course_id,
                'school_year_id' => $activeSchoolYear->id,
                'start_datetime' => $this->start_datetime,
                'end_datetime' => $this->end_datetime,
                'status' => $this->status,
            ]);

            // Delete existing voting vote count records for this voting exclusive
            voting_vote_count::where('voting_exclusive_id', $votingExclusive->id)->delete();

            // Get approved candidacy applications
            $approvedCandidacies = applied_candidacy::where('status', 'approved')
                ->with('students')
                ->get();

            $voteCountCreated = 0;
            foreach ($approvedCandidacies as $candidacy) {
                // Check if student matches the voting exclusive criteria
                $student = $candidacy->students;
                
                // If department is specified, check if student belongs to that department
                if ($this->department_id && $student->department_id != $this->department_id) {
                    continue;
                }
                
                // If course is specified, check if student belongs to that course
                if ($this->course_id && $student->course_id != $this->course_id) {
                    continue;
                }

                // Create voting vote count record
                voting_vote_count::create([
                    'voting_exclusive_id' => $votingExclusive->id,
                    'students_id' => $student->id,
                    'number_of_vote' => 0, // No votes yet
                    'status' => 'official',
                ]);
                
                $voteCountCreated++;
            }

            $this->showEditModal = false;
            $this->dispatch('close-modal', id: 'edit-voting-exclusive-modal');
            $this->dispatch('show-toast', [
                'message' => "Voting exclusive updated successfully. {$voteCountCreated} candidates updated.",
                'type' => 'success',
                'title' => 'Updated'
            ]);

            $this->resetForm();
            $this->resetPage();
        } catch (\Exception $e) {
            $this->dispatch('show-toast', [
                'message' => 'Error updating voting exclusive: ' . $e->getMessage(),
                'type' => 'error',
                'title' => 'Error'
            ]);
        }
    }

    public function deleteConfirmed()
    {
        if (!$this->deletingId) {
            $this->dispatch('show-toast', [
                'message' => 'No item selected to delete.',
                'type' => 'error',
                'title' => 'Error'
            ]);
            return;
        }

        try {
            $votingExclusive = voting_exclusive::find($this->deletingId);
            if (!$votingExclusive) {
                $this->dispatch('show-toast', [
                    'message' => 'Voting exclusive not found.',
                    'type' => 'error',
                    'title' => 'Not Found'
                ]);
                $this->cancelDelete();
                return;
            }

            // Delete associated voting vote count records first
            voting_vote_count::where('voting_exclusive_id', $votingExclusive->id)->delete();
            
            $votingExclusive->delete();
            $this->dispatch('show-toast', [
                'message' => 'Voting exclusive moved to trash successfully.',
                'type' => 'success',
                'title' => 'Moved to Trash'
            ]);
            $this->cancelDelete();
            $this->resetPage();
        } catch (\Exception $e) {
            $this->dispatch('show-toast', [
                'message' => 'Error moving voting exclusive to trash: ' . $e->getMessage(),
                'type' => 'error',
                'title' => 'Error'
            ]);
        }
    }

    public function cancelDelete()
    {
        $this->showDeleteModal = false;
        $this->deletingId = null;
    }

    public function resetForm()
    {
        $this->department_id = null;
        $this->course_id = null;
        $this->start_datetime = null;
        $this->end_datetime = null;
        $this->status = 'active';
        $this->editingId = null;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedDepartmentId()
    {
        // Reset course selection when department changes
        $this->course_id = null;
    }

    public function getCoursesByDepartment()
    {
        if ($this->department_id) {
            return course::where('department_id', $this->department_id)->active()->orderBy('course_name')->get();
        }
        return collect(); // Return empty collection when "All Departments" is selected
    }

    /**
     * Check if there are overlapping voting periods (time-based only)
     * 
     * @param int|null $excludeId ID to exclude from check (for updates)
     * @return bool
     */
    private function hasOverlappingVotingPeriod($excludeId = null)
    {
        if (!$this->start_datetime || !$this->end_datetime) {
            return false;
        }

        $startDate = Carbon::parse($this->start_datetime);
        $endDate = Carbon::parse($this->end_datetime);

        // Check for overlapping periods using date range logic (time-based only)
        $query = voting_exclusive::where(function ($q) use ($startDate, $endDate) {
            $q->where(function ($subQ) use ($startDate, $endDate) {
                // New period starts within existing period
                $subQ->where('start_datetime', '<=', $startDate)
                     ->where('end_datetime', '>', $startDate);
            })->orWhere(function ($subQ) use ($startDate, $endDate) {
                // New period ends within existing period
                $subQ->where('start_datetime', '<', $endDate)
                     ->where('end_datetime', '>=', $endDate);
            })->orWhere(function ($subQ) use ($startDate, $endDate) {
                // New period completely contains existing period
                $subQ->where('start_datetime', '>=', $startDate)
                     ->where('end_datetime', '<=', $endDate);
            })->orWhere(function ($subQ) use ($startDate, $endDate) {
                // Existing period completely contains new period
                $subQ->where('start_datetime', '<=', $startDate)
                     ->where('end_datetime', '>=', $endDate);
            });
        });

        // Exclude current record when updating
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    /**
     * Ensure the voting period has a minimum duration (strictly more than 1 hour)
     *
     * @return bool
     */
    private function hasMinimumDuration()
    {
        if (!$this->start_datetime || !$this->end_datetime) {
            return false; // validation elsewhere will handle required fields
        }

        $startDate = Carbon::parse($this->start_datetime);
        $endDate = Carbon::parse($this->end_datetime);

        // Calculate duration in minutes
        $durationMinutes = $startDate->diffInMinutes($endDate);

        // Require strictly greater than 60 minutes
        return $durationMinutes > 60;
    }

    /**
     * Check for duplicate voting exclusive based on department, course, and school year
     * Note: This method is now deprecated in favor of hasOverlappingVotingPeriod
     * which provides more comprehensive validation including time overlap checking
     * 
     * @param int|null $excludeId ID to exclude from check (for updates)
     * @return bool
     */
    private function hasDuplicateVotingExclusive($excludeId = null)
    {
        // Allow multiple voting exclusives for same department/course
        // as long as they don't overlap in time (handled by hasOverlappingVotingPeriod)
        return false;
    }

    /**
     * Check if there's an active voting exclusive for the same course
     * 
     * @param int|null $excludeId ID to exclude from check (for updates)
     * @return bool
     */
    private function hasActiveVotingForSameCourse($excludeId = null)
    {
        if (!$this->course_id) {
            return false; // If no course selected, allow creation
        }

        $now = Carbon::now();

        $query = voting_exclusive::where('course_id', $this->course_id)
            ->where('status', 'active')
            ->where('start_datetime', '<=', $now)
            ->where('end_datetime', '>=', $now);

        // Exclude current record when updating
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    /**
     * Get active voting exclusive for the same course (for error messages)
     * 
     * @param int|null $excludeId ID to exclude from check (for updates)
     * @return \App\Models\voting_exclusive|null
     */
    private function getActiveVotingForSameCourse($excludeId = null)
    {
        if (!$this->course_id) {
            return null;
        }

        $now = Carbon::now();

        $query = voting_exclusive::with(['course'])
            ->where('course_id', $this->course_id)
            ->where('status', 'active')
            ->where('start_datetime', '<=', $now)
            ->where('end_datetime', '>=', $now);

        // Exclude current record when updating
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->first();
    }

    /**
     * Check if there's a minimum time gap between voting periods
     * 
     * @param int|null $excludeId ID to exclude from check (for updates)
     * @return bool
     */
    private function hasMinimumTimeGap($excludeId = null)
    {
        if (!$this->start_datetime || !$this->end_datetime) {
            return true; // If no dates, consider it valid
        }

        $startDate = Carbon::parse($this->start_datetime);
        $endDate = Carbon::parse($this->end_datetime);
        
        // Define minimum gap (1 hour)
        $minGap = 60; // minutes

        $query = voting_exclusive::where(function ($q) use ($startDate, $endDate, $minGap) {
            // Check if new period starts too close to an existing period's end
            // (existing period ends within 1 hour before new period starts)
            $q->where(function ($subQ) use ($startDate, $minGap) {
                $subQ->where('end_datetime', '>', $startDate->copy()->subMinutes($minGap))
                     ->where('end_datetime', '<', $startDate);
            })->orWhere(function ($subQ) use ($endDate, $minGap) {
                // Check if new period ends too close to an existing period's start
                // (existing period starts within 1 hour after new period ends)
                $subQ->where('start_datetime', '>', $endDate)
                     ->where('start_datetime', '<', $endDate->copy()->addMinutes($minGap));
            });
        });

        // Exclude current record when updating
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return !$query->exists();
    }

    /**
     * Get existing voting periods for reference
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getExistingVotingPeriods()
    {
        return voting_exclusive::with(['department', 'course'])
            ->orderBy('start_datetime')
            ->get()
            ->map(function ($period) {
                return [
                    'id' => $period->id,
                    'department' => $period->department ? $period->department->department_name : 'All Departments',
                    'course' => $period->course ? $period->course->course_name : 'All Courses',
                    'start_date' => Carbon::parse($period->start_datetime)->format('M d, Y h:i A'),
                    'end_date' => Carbon::parse($period->end_datetime)->format('M d, Y h:i A'),
                    'status' => $period->status,
                ];
            });
    }

    /**
     * Get conflicting voting periods for better error messages (time-based only)
     * 
     * @return array
     */
    private function getConflictingPeriods()
    {
        if (!$this->start_datetime || !$this->end_datetime) {
            return [];
        }

        $startDate = Carbon::parse($this->start_datetime);
        $endDate = Carbon::parse($this->end_datetime);

        return voting_exclusive::with(['department', 'course'])
            ->where(function ($q) use ($startDate, $endDate) {
                $q->where(function ($subQ) use ($startDate, $endDate) {
                    $subQ->where('start_datetime', '<=', $startDate)
                         ->where('end_datetime', '>', $startDate);
                })->orWhere(function ($subQ) use ($startDate, $endDate) {
                    $subQ->where('start_datetime', '<', $endDate)
                         ->where('end_datetime', '>=', $endDate);
                })->orWhere(function ($subQ) use ($startDate, $endDate) {
                    $subQ->where('start_datetime', '>=', $startDate)
                         ->where('end_datetime', '<=', $endDate);
                })->orWhere(function ($subQ) use ($startDate, $endDate) {
                    $subQ->where('start_datetime', '<=', $startDate)
                         ->where('end_datetime', '>=', $endDate);
                });
            })
            ->get()
            ->map(function ($period) {
                return [
                    'department' => $period->department ? $period->department->department_name : 'All Departments',
                    'course' => $period->course ? $period->course->course_name : 'All Courses',
                    'start_date' => Carbon::parse($period->start_datetime)->format('M d, Y h:i A'),
                    'end_date' => Carbon::parse($period->end_datetime)->format('M d, Y h:i A'),
                ];
            })
            ->toArray();
    }

    public function render()
    {
        $perPage = (int) request()->get('perPage', 10);
        if ($perPage <= 0) { $perPage = 10; }

        $query = voting_exclusive::with(['department', 'course', 'schoolYear'])->orderByDesc('id');

        if (!empty($this->search)) {
            $query->whereHas('department', function($q) {
                $q->where('department_name', 'like', '%' . $this->search . '%');
            })->orWhereHas('course', function($q) {
                $q->where('course_name', 'like', '%' . $this->search . '%');
            })->orWhere('status', 'like', '%' . $this->search . '%');
        }

        $votingExclusives = $query->paginate($perPage);
        $departments = department::active()->get();
        $courses = $this->getCoursesByDepartment();
        $existingPeriods = $this->getExistingVotingPeriods();

        return view('livewire.voting-exclusive.voting-exclusive', compact('votingExclusives', 'departments', 'courses', 'existingPeriods'));
    }
}
