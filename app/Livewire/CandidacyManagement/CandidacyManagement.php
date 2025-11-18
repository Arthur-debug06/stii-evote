<?php

namespace App\Livewire\CandidacyManagement;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\applied_candidacy;
use App\Models\students;
use App\Models\school_year_and_semester;
use App\Models\voting_exclusive;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CandidacyManagement extends Component
{
    use WithPagination, WithFileUploads;

    // Candidacy fields
    public bool $is_regular_student = true;
    public ?int $position_id = null;
    public ?int $partylist_id = null;
    public $grade_attachment = null;
    
    // Certificate of Candidacy fields (matching the certificate form)
    public string $first_name = '';
    public string $middle_name = '';
    public string $last_name = '';
    public string $gender = '';
    public string $date_of_birth = '';
    public int $age = 0;
    public string $marital_status = '';
    public ?int $course_id = null;
    
    // Letter-by-letter name fields for the certificate form
    public array $first_name_letters = [];
    public array $middle_name_letters = [];
    public array $last_name_letters = [];
    
    // UI state
    public string $search = '';
    public bool $showAddCandidacyModal = false;
    public bool $showEditCandidacyModal = false;
    public bool $showViewCandidacyModal = false;
    public bool $showDeleteCandidacyModal = false;
    public ?int $editingId = null;
    public ?int $viewingId = null;
    public ?int $deletingId = null;
    public $viewingCandidacy = null;
    public $activeVoting = null;
    public $upcomingVoting = null;

    protected $rules = [
        // Candidacy fields
        'is_regular_student' => 'required|boolean',
        'position_id' => 'required|exists:position,id',
        'partylist_id' => 'nullable|exists:partylist,id',
        'grade_attachment' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        
        // Certificate of Candidacy fields (matching the certificate form)
        'first_name' => 'required|string|max:255',
        'middle_name' => 'nullable|string|max:255',
        'last_name' => 'required|string|max:255',
        // gender, date_of_birth, age, marital_status, course_id are now read-only from student data
    ];

    public function createCandidacy(): void
    {
        // Convert letter arrays to full names before validation
        $this->convertLettersToNames();
        
        $this->validate();

        try {
            // Get current logged-in student
            $currentStudent = auth()->guard('students')->user();
            
            if (!$currentStudent) {
                $this->dispatch('show-toast', [
                    'message' => 'You must be logged in as a student to apply for candidacy.',
                    'type' => 'error',
                    'title' => 'Authentication Required!'
                ]);
                return;
            }

            // Get the active school year and semester - same logic as VotingExclusive
            $activeSchoolYear = school_year_and_semester::active()->first();
            
            if (!$activeSchoolYear) {
                $this->dispatch('show-toast', [
                    'message' => 'No active school year and semester found. Please set an active school year first.',
                    'type' => 'error',
                    'title' => 'Error!'
                ]);
                return;
            }

            // Update student information with the certificate data (excluding read-only fields)
            $currentStudent->update([
                'first_name' => $this->first_name,
                'middle_name' => $this->middle_name,
                'last_name' => $this->last_name,
                // gender, date_of_birth, age, marital_status, course_id are read-only from student data
                'school_year_and_semester_id' => $activeSchoolYear->id,
            ]);

            // Handle file upload
            $gradeAttachmentPath = null;
            if ($this->grade_attachment) {
                $gradeAttachmentPath = $this->grade_attachment->store('grade_attachments', 'public');
            }

            // Double-check: prevent duplicate active candidacy (pending or approved) in the same active school year
            if ($this->studentHasActiveCandidacy($currentStudent->id, $activeSchoolYear->id, $this->position_id)) {
                $this->dispatch('show-toast', [
                    'message' => 'You already have an active candidacy application. You may not apply again until it is resolved.',
                    'type' => 'error',
                    'title' => 'Application Exists'
                ]);
                return;
            }

            $candidacy = applied_candidacy::create([
                'students_id' => $currentStudent->id,
                'position_id' => $this->position_id,
                'school_year_and_semester_id' => $activeSchoolYear->id,
                'partylist_id' => $this->partylist_id,
                'grade_attachment' => $gradeAttachmentPath,
                'is_regular_student' => $this->is_regular_student,
                'status' => 'pending',
            ]);

            // Debug: Check candidacy creation
            // dd('Candidacy created successfully!', [
            //     'candidacy_id' => $candidacy->id,
            //     'student_id' => $currentStudent->id,
            //     'student_name' => $currentStudent->first_name . ' ' . $currentStudent->last_name,
            // ]);

            // Create notifications manually since DocumentTraits has database schema issues
            try {
                // Get existing signatories from set_signatory table
                $existingSignatories = \App\Models\set_signatory::where('status', 'active')->get();
                
                if ($existingSignatories->count() > 0) {
                    // Create notifications for each signatory
                    foreach ($existingSignatories as $signatory) {
                        // Debug: Log notification creation details
                        \Log::info('Creating notification', [
                            'signatory_users_id' => $signatory->users_id,
                                'current_student_id' => $currentStudent->id ?? null,
                                'current_student_name' => trim(($currentStudent->first_name ?? '') . ' ' . ($currentStudent->last_name ?? '')),
                            'candidacy_id' => $candidacy->id,
                        ]);
                        
                        \App\Models\Notification::create([
                            'type' => 'App\\Notifications\\DocumentNotification',
                            'user_id' => $signatory->users_id,
                            'message' => 'You have been assigned as a signatory for candidacy application #' . $candidacy->id . ' by ' . trim(($currentStudent->first_name ?? '') . ' ' . ($currentStudent->last_name ?? '')),
                            'title' => 'Candidacy Signatory Assignment',
                            'status' => 'pending',
                            'documentable_type' => get_class($candidacy),
                            'documentable_id' => $candidacy->id,
                            'notifiable_type' => 'App\\Models\\User', // The user who should receive the notification
                            'notifiable_id' => (string) $signatory->users_id, // The user ID who should receive the notification
                            'signatory_id' => $signatory->id, // Add signatory_id field
                            'icon' => 'bell',
                            'icon_color' => 'blue',
                            'url' => '/candidacy-management',
                        ]);
                    }
                    
                    // Log the action using LoggerTrait
                    $candidacy->logActivity('candidacy_submitted', [
                        'message' => 'Candidacy application submitted by student: ' . trim(($currentStudent->first_name ?? '') . ' ' . ($currentStudent->last_name ?? '')),
                        'signatories_notified' => $existingSignatories->count(),
                    ]);
                } else {
                    // No signatories available, just log the action using LoggerTrait
                    $candidacy->logActivity('candidacy_submitted', [
                        'message' => 'Candidacy application submitted by student: ' . trim(($currentStudent->first_name ?? '') . ' ' . ($currentStudent->last_name ?? ''))
                    ]);
                }
            } catch (\Exception $e) {
                // Fallback: just log the action if notification creation fails
                $candidacy->logActivity('candidacy_submitted', [
                    'message' => 'Candidacy application submitted by student: ' . trim(($currentStudent->first_name ?? '') . ' ' . ($currentStudent->last_name ?? '')),
                    'error' => $e->getMessage()
                ]);
            }

            // Show success toast only once at the end
            $this->dispatch('show-toast', [
                'type' => 'success',
                'title' => 'Candidacy Application Submitted!',
                'message' => 'Your candidacy application has been submitted successfully.',
            ]);

            // Reset form fields
            $this->resetFormFields();

            // Close modal
            $this->showAddCandidacyModal = false;
            $this->dispatch('close-modal', id: 'add-candidacy-modal');
            
            $this->resetPage();
            
        } catch (\Exception $e) {
            $this->dispatch('show-toast', [
                'message' => 'Error submitting candidacy: ' . $e->getMessage(),
                'type' => 'error',
                'title' => 'Error!'
            ]);
        }
    }

    /**
     * Check if a student already has an active candidacy (pending or approved)
     *
     * @param int $studentId
     * @return bool
     */
    private function studentHasActiveCandidacy(int $studentId, ?int $schoolYearId = null, ?int $positionId = null): bool
    {
        $query = applied_candidacy::where('students_id', $studentId)
            ->whereIn('status', ['pending', 'approved']);

        if ($schoolYearId) {
            $query->where('school_year_and_semester_id', $schoolYearId);
        }

        if ($positionId) {
            $query->where('position_id', $positionId);
        }

        return $query->exists();
    }

    public function viewCandidacy(int $id): void
    {
        try {
            $candidacy = applied_candidacy::with(['students', 'position', 'partylist', 'school_year_and_semester'])->find($id);
            if (!$candidacy) {
                $this->dispatch('show-toast', [
                    'message' => 'Candidacy application not found.',
                    'type' => 'error',
                    'title' => 'Not Found'
                ]);
                return;
            }

            // Set viewing state
            $this->viewingId = $candidacy->id;
            $this->viewingCandidacy = $candidacy;

            // Open view modal
            $this->showViewCandidacyModal = true;
        } catch (\Exception $e) {
            $this->dispatch('show-toast', [
                'message' => 'Unable to load candidacy for viewing.',
                'type' => 'error',
                'title' => 'Error'
            ]);
        }
    }

    public function editCandidacy(int $id): void
    {
        try {
            $candidacy = applied_candidacy::with('students')->find($id);
            if (!$candidacy) {
                $this->dispatch('show-toast', [
                    'message' => 'Candidacy application not found.',
                    'type' => 'error',
                    'title' => 'Not Found'
                ]);
                return;
            }

            // Check if candidacy is approved - prevent editing
            if ($candidacy->status === 'approved') {
                $this->dispatch('show-toast', [
                    'message' => 'Cannot edit approved candidacy applications.',
                    'type' => 'error',
                    'title' => 'Action Not Allowed'
                ]);
                return;
            }

            // Set editing state and populate form with existing data
            $this->editingId = $candidacy->id;
            $this->position_id = $candidacy->position_id;
            $this->is_regular_student = (bool) $candidacy->is_regular_student;
            
            // Populate student data
            $this->populateStudentData($candidacy->students);

            // Open edit modal
            $this->showEditCandidacyModal = true;
        } catch (\Exception $e) {
            $this->dispatch('show-toast', [
                'message' => 'Unable to load candidacy for editing.',
                'type' => 'error',
                'title' => 'Error'
            ]);
        }
    }

    public function updateCandidacy(): void
    {
        if (!$this->editingId) {
            $this->dispatch('show-toast', [
                'message' => 'No candidacy selected to update.',
                'type' => 'error',
                'title' => 'Error'
            ]);
            return;
        }

        // Convert letter arrays to full names before validation
        $this->convertLettersToNames();
        
        $this->validate();

        try {
            $candidacy = applied_candidacy::find($this->editingId);
            if (!$candidacy) {
                $this->dispatch('show-toast', [
                    'message' => 'Candidacy application not found.',
                    'type' => 'error',
                    'title' => 'Not Found'
                ]);
                return;
            }

            // Check if candidacy is approved - prevent updating
            if ($candidacy->status === 'approved') {
                $this->dispatch('show-toast', [
                    'message' => 'Cannot update approved candidacy applications.',
                    'type' => 'error',
                    'title' => 'Action Not Allowed'
                ]);
                $this->showEditCandidacyModal = false;
                $this->resetEditingState();
                return;
            }

            $candidacy->position_id = $this->position_id;
            $candidacy->is_regular_student = $this->is_regular_student;
            $candidacy->save();

            // Update student information with certificate data (excluding read-only fields)
            if ($candidacy->relationLoaded('students') && $candidacy->students) {
                $candidacy->students->update([
                    'first_name' => $this->first_name,
                    'middle_name' => $this->middle_name,
                    'last_name' => $this->last_name,
                    // gender, date_of_birth, age, marital_status, course_id are read-only from student data
                ]);
            } elseif ($candidacy->students) {
                // relation not loaded but exists via dynamic property
                $candidacy->students->update([
                    'first_name' => $this->first_name,
                    'middle_name' => $this->middle_name,
                    'last_name' => $this->last_name,
                ]);
            } else {
                // Unexpected: students relation missing - log and notify
                \Log::warning('Attempted to update candidacy student but students relation is missing for candidacy id: ' . $candidacy->id);
                $this->dispatch('show-toast', [
                    'message' => 'Student record missing for this candidacy; student fields were not updated.',
                    'type' => 'warning',
                    'title' => 'Partial Update'
                ]);
            }

            $this->showEditCandidacyModal = false;
            $this->dispatch('close-modal', id: 'edit-candidacy-modal');
            $this->dispatch('show-toast', [
                'message' => 'Candidacy application updated successfully.',
                'type' => 'success',
                'title' => 'Updated'
            ]);

            $this->resetEditingState();
            $this->resetPage();
        } catch (\Exception $e) {
            $this->dispatch('show-toast', [
                'message' => 'Error updating candidacy: ' . $e->getMessage(),
                'type' => 'error',
                'title' => 'Error'
            ]);
        }
    }

    public function deleteCandidacy(int $id): void
    {
        try {
            $candidacy = applied_candidacy::find($id);
            if (!$candidacy) {
                $this->dispatch('show-toast', [
                    'message' => 'Candidacy application not found.',
                    'type' => 'error',
                    'title' => 'Not Found'
                ]);
                return;
            }

            // Check if candidacy is approved - prevent deletion
            if ($candidacy->status === 'approved') {
                $this->dispatch('show-toast', [
                    'message' => 'Cannot delete approved candidacy applications.',
                    'type' => 'error',
                    'title' => 'Action Not Allowed'
                ]);
                return;
            }

            // Open confirmation modal
            $this->deletingId = $id;
            $this->showDeleteCandidacyModal = true;
        } catch (\Exception $e) {
            $this->dispatch('show-toast', [
                'message' => 'Unable to process deletion request.',
                'type' => 'error',
                'title' => 'Error'
            ]);
        }
    }

    public function cancelDelete(): void
    {
        $this->showDeleteCandidacyModal = false;
        $this->deletingId = null;
    }

    public function deleteConfirmed(): void
    {
        if (!$this->deletingId) {
            $this->dispatch('show-toast', [ 'message' => 'No item selected to delete.', 'type' => 'error', 'title' => 'Error' ]);
            return;
        }
        try {
            $candidacy = applied_candidacy::find($this->deletingId);
            if (!$candidacy) {
                $this->dispatch('show-toast', [ 'message' => 'Candidacy application not found.', 'type' => 'error', 'title' => 'Not Found' ]);
                $this->cancelDelete();
                return;
            }

            // Check if candidacy is approved - prevent deletion
            if ($candidacy->status === 'approved') {
                $this->dispatch('show-toast', [
                    'message' => 'Cannot delete approved candidacy applications.',
                    'type' => 'error',
                    'title' => 'Action Not Allowed'
                ]);
                $this->cancelDelete();
                return;
            }

            $candidacy->delete();
            $this->dispatch('show-toast', [ 'message' => 'Candidacy application deleted.', 'type' => 'success', 'title' => 'Deleted' ]);
            $this->cancelDelete();
            $this->resetPage();
        } catch (\Exception $e) {
            $this->dispatch('show-toast', [ 'message' => 'Error deleting candidacy: '.$e->getMessage(), 'type' => 'error', 'title' => 'Error' ]);
        }
    }

    public function openAddModal(): void
    {
        // Get current logged-in student
        $currentStudent = auth()->guard('students')->user();
        
        if (!$currentStudent) {
            $this->dispatch('show-toast', [
                'message' => 'You must be logged in as a student to apply for candidacy.',
                'type' => 'error',
                'title' => 'Authentication Required!'
            ]);
            return;
        }

        // Get the active school year and semester - same logic as VotingExclusive
        $activeSchoolYear = school_year_and_semester::active()->first();
        
        if (!$activeSchoolYear) {
            $this->dispatch('show-toast', [
                'message' => 'No active school year and semester found. Please set an active school year first.',
                'type' => 'error',
                'title' => 'Error!'
            ]);
            return;
        }

        // Force refresh the active voting check before opening modal
        $this->getActiveVoting();

        // Check if there's an active voting period for the active school year
        // only consider currently ongoing voting (start_datetime <= now <= end_datetime)
        $now = Carbon::now();
        $activeVoting = voting_exclusive::where('status', 'active')
            ->where('school_year_id', $activeSchoolYear->id)
            ->where('start_datetime', '<=', $now)
            ->where('end_datetime', '>=', $now)
            ->first();

        if ($activeVoting) {
            $this->dispatch('show-toast', [
                'message' => 'Cannot apply for candidacy while voting is ongoing for this school year. Please wait until the election period ends.',
                'type' => 'error',
                'title' => 'Election in Progress!'
            ]);
            return;
        }

        // Prevent applying if the student already has a pending/approved candidacy in the same active school year
        if ($currentStudent) {
            // Check for an active candidacy in ANY year (pending or approved)
            $existingActive = applied_candidacy::where('students_id', $currentStudent->id)
                ->whereIn('status', ['pending', 'approved'])
                ->orderByDesc('id')
                ->first();

            // If there is an active candidacy in the same school year, block
            if ($this->studentHasActiveCandidacy($currentStudent->id, $activeSchoolYear->id)) {
                $this->dispatch('show-toast', [
                    'message' => 'You already submitted a candidacy application for the current school year. You may not submit another until your current application is resolved.',
                    'type' => 'error',
                    'title' => 'Application Exists'
                ]);
                return;
            }

            // If the student has an active candidacy but in a DIFFERENT school year, allow applying again
            if ($existingActive && $existingActive->school_year_and_semester_id !== $activeSchoolYear->id) {
                // Try to load the related school_year_and_semester for a friendly message
                $prevSY = null;
                try {
                    $prevSY = $existingActive->school_year_and_semester; // may be null
                } catch (\Exception $e) {
                    $prevSY = null;
                }

                $syLabel = $prevSY ? ($prevSY->school_year . ' - ' . $prevSY->semester) : 'a previous school year';
                $this->dispatch('show-toast', [
                    'message' => 'Note: You have an existing application from ' . $syLabel . '. You may still apply for the current school year.',
                    'type' => 'info',
                    'title' => 'Previous Application Found'
                ]);
                // continue to allow opening the modal
            }
        }

        $this->resetEditingState();
        
        // Populate form with current student data
        $this->populateStudentData($currentStudent);
        
        $this->showAddCandidacyModal = true;
    }

    public function resetEditingState(): void
    {
        $this->resetFormFields();
        $this->editingId = null;
        $this->viewingId = null;
        $this->viewingCandidacy = null;
    }

    public function resetFormFields(): void
    {
        // Candidacy fields
        $this->is_regular_student = true;
        $this->position_id = null;
        $this->partylist_id = null;
        $this->grade_attachment = null;
        
        // Certificate of Candidacy fields
        $this->first_name = '';
        $this->middle_name = '';
        $this->last_name = '';
        // gender, date_of_birth, age, marital_status, course_id are now read-only from student data
        
        // Letter arrays
        $this->first_name_letters = array_fill(0, 20, '');
        $this->middle_name_letters = array_fill(0, 20, '');
        $this->last_name_letters = array_fill(0, 20, '');
    }

    public function populateStudentData($student): void
    {
        // Safely populate form with current student data (certificate fields only).
        // Protect against null or unexpected values to avoid "attempt to read property on null" errors.
        if (!$student || !is_object($student)) {
            $this->first_name = '';
            $this->middle_name = '';
            $this->last_name = '';
            // gender, date_of_birth, age, marital_status, course_id are now read-only from student data
            // Convert names to letter arrays (empty)
            $this->convertNamesToLetters();
            return;
        }

        // Populate fields using safe null coalescing on object properties
        $this->first_name = $student->first_name ?? '';
        $this->middle_name = $student->middle_name ?? '';
        $this->last_name = $student->last_name ?? '';
        // gender, date_of_birth, age, marital_status, course_id are now read-only from student data

        // Convert names to letter arrays
        $this->convertNamesToLetters();
    }

    public function convertLettersToNames(): void
    {
        $this->first_name = trim(implode('', $this->first_name_letters));
        $this->middle_name = trim(implode('', $this->middle_name_letters));
        $this->last_name = trim(implode('', $this->last_name_letters));
    }

    public function convertNamesToLetters(): void
    {
        // Convert first name to letters
        $first_name_chars = str_split($this->first_name ?? '');
        $this->first_name_letters = array_merge($first_name_chars, array_fill(0, 20 - count($first_name_chars), ''));
        
        // Convert middle name to letters
        $middle_name_chars = str_split($this->middle_name ?? '');
        $this->middle_name_letters = array_merge($middle_name_chars, array_fill(0, 20 - count($middle_name_chars), ''));
        
        // Convert last name to letters
        $last_name_chars = str_split($this->last_name ?? '');
        $this->last_name_letters = array_merge($last_name_chars, array_fill(0, 20 - count($last_name_chars), ''));
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updated()
    {
        // Refresh active voting status whenever the component is updated
        $this->getActiveVoting();
    }


    public function getActiveVoting()
    {
        try {
            // Get the active school year and semester
            $activeSchoolYear = school_year_and_semester::active()->first();
            
            if ($activeSchoolYear) {
                // Only consider currently ongoing voting periods as active (start <= now <= end)
                $now = Carbon::now();
                $this->activeVoting = voting_exclusive::where('status', 'active')
                    ->where('school_year_id', $activeSchoolYear->id)
                    ->where('start_datetime', '<=', $now)
                    ->where('end_datetime', '>=', $now)
                    ->first();

                // Also expose the next upcoming voting period (if any) for informational UI
                $this->upcomingVoting = voting_exclusive::where('status', 'active')
                    ->where('school_year_id', $activeSchoolYear->id)
                    ->where('start_datetime', '>', $now)
                    ->orderBy('start_datetime')
                    ->first();
                    
            } else {
                $this->activeVoting = null;
                $this->upcomingVoting = null;
            }
        } catch (\Exception $e) {
            $this->activeVoting = null;
            \Log::error('Error getting active voting: ' . $e->getMessage());
        }
    }



    public function render()
    {
        $perPage = (int) request()->get('perPage', 10);
        if ($perPage <= 0) { $perPage = 10; }

        // Get active voting status
        $this->getActiveVoting();

        // Get current logged-in student
        $currentStudent = auth()->guard('students')->user();
        
        if (!$currentStudent) {
            // If no student is logged in, return empty results
            $candidacies = applied_candidacy::where('id', 0)->paginate($perPage);
            $positions = \App\Models\position::active()->get();
            $courses = \App\Models\course::active()->get();
            $partylists = \App\Models\partylist::where('status', 'active')->get();
            $signatories = \App\Models\set_signatory::where('status', 'active')->get();
            
            // Get signatory actions separately to avoid composition conflict
            $signatoryActions = \DB::table('signatory_action')
                ->where('status', 'active')
                ->pluck('action_name', 'id')
                ->toArray();
            
            // Add signatory action names to each signatory
            $signatories->each(function ($signatory) use ($signatoryActions) {
                $signatory->action_name = $signatoryActions[$signatory->signatory_action_id] ?? 'Signatory';
            });
            
            return view('livewire.candidacy-management.candidacy-management', compact('candidacies', 'positions', 'courses', 'partylists', 'signatories'));
        }

        // Only show candidacies for the current logged-in student
        $query = applied_candidacy::with(['students', 'position', 'school_year_and_semester'])
            ->where('students_id', $currentStudent->id)
            ->orderByDesc('id');

        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('status', 'like', '%' . $this->search . '%')
                  ->orWhereHas('position', function($positionQuery) {
                      $positionQuery->where('position_name', 'like', '%' . $this->search . '%');
                  })
                  ->orWhereHas('students', function($studentQuery) {
                      $studentQuery->where('first_name', 'like', '%' . $this->search . '%')
                                  ->orWhere('last_name', 'like', '%' . $this->search . '%');
                  })
                  ->orWhereHas('school_year_and_semester', function($schoolYearQuery) {
                      $schoolYearQuery->where('school_year', 'like', '%' . $this->search . '%')
                                     ->orWhere('semester', 'like', '%' . $this->search . '%');
                  });
            });
        }

        $candidacies = $query->paginate($perPage);
        $positions = \App\Models\position::active()->get();
        $courses = \App\Models\course::active()->get();
        $partylists = \App\Models\partylist::where('status', 'active')->get();
        
        // Get signatories for the form
        $signatories = \App\Models\set_signatory::where('status', 'active')->get();
        
        // Get signatory actions separately to avoid composition conflict
        $signatoryActions = \DB::table('signatory_action')
            ->where('status', 'active')
            ->pluck('action_name', 'id')
            ->toArray();
        
        // Add signatory action names to each signatory
        $signatories->each(function ($signatory) use ($signatoryActions) {
            $signatory->action_name = $signatoryActions[$signatory->signatory_action_id] ?? 'Signatory';
        });

        return view('livewire.candidacy-management.candidacy-management', compact('candidacies', 'positions', 'courses', 'partylists', 'signatories'));
    }
}
