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

class CandidacyManagement1 extends Component
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
    // School year filter
    public $schoolYears = [];
    public $selectedSchoolYear = null;
    public bool $showAddCandidacyModal = false;
    public bool $showEditCandidacyModal = false;
    public bool $showViewCandidacyModal = false;
    public bool $showDeleteCandidacyModal = false;
    public bool $showApproveConfirmModal = false;
    public bool $showRejectConfirmModal = false;
    public ?int $editingId = null;
    public ?int $viewingId = null;
    public ?int $deletingId = null;
    public ?int $approvingId = null;
    public ?int $rejectingId = null;
    public $viewingCandidacy = null;
    public $activeVoting = null;

    // Reset pagination when search is updated
    public function updatingSearch()
    {
        $this->resetPage();
    }

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

    /**
     * Open approve confirmation modal
     */
    public function confirmApprove(int $id): void
    {
        $this->approvingId = $id;
        $this->showApproveConfirmModal = true;
    }

    public function cancelApprove(): void
    {
        $this->approvingId = null;
        $this->showApproveConfirmModal = false;
    }

    public function approveConfirmed(): void
    {
        if (!$this->approvingId) {
            $this->dispatch('show-toast', [ 'message' => 'No candidacy selected to approve.', 'type' => 'error', 'title' => 'Error' ]);
            return;
        }

        $id = $this->approvingId;
        $this->approvingId = null;
        $this->showApproveConfirmModal = false;

        $this->approveCandidacy($id);
    }

    /**
     * Open reject confirmation modal
     */
    public function confirmReject(int $id): void
    {
        $this->rejectingId = $id;
        $this->showRejectConfirmModal = true;
    }

    public function cancelReject(): void
    {
        $this->rejectingId = null;
        $this->showRejectConfirmModal = false;
    }

    public function rejectConfirmed(): void
    {
        if (!$this->rejectingId) {
            $this->dispatch('show-toast', [ 'message' => 'No candidacy selected to reject.', 'type' => 'error', 'title' => 'Error' ]);
            return;
        }

        $id = $this->rejectingId;
        $this->rejectingId = null;
        $this->showRejectConfirmModal = false;

        $this->rejectCandidacy($id);
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

    /**
     * Approve a candidacy by setting its status to 'approved'
     */
    public function approveCandidacy(int $id): void
    {
        try {
            $candidacy = applied_candidacy::find($id);
            if (!$candidacy) {
                $this->dispatch('show-toast', [ 'message' => 'Candidacy application not found.', 'type' => 'error', 'title' => 'Not Found' ]);
                return;
            }

            $candidacy->status = 'approved';
            $candidacy->save();

            // Create a notification for the student
            try {
                \App\Models\Notification::create([
                    'type' => 'App\\Notifications\\DocumentNotification',
                    'user_id' => $candidacy->students_id,
                    'message' => 'Your candidacy application #' . $candidacy->id . ' has been approved.',
                    'title' => 'Candidacy Approved',
                    'status' => 'pending',
                    'documentable_type' => get_class($candidacy),
                    'documentable_id' => $candidacy->id,
                    'notifiable_type' => 'App\\Models\\students',
                    'notifiable_id' => (string) $candidacy->students_id,
                    'icon' => 'check',
                    'icon_color' => 'green',
                    'url' => '/candidacy-management',
                ]);
            } catch (\Exception $e) {
                \Log::error('Failed to create notification for approved candidacy: ' . $e->getMessage());
            }

            $this->dispatch('show-toast', [ 'message' => 'Candidacy application approved.', 'type' => 'success', 'title' => 'Approved' ]);
            $this->resetPage();
        } catch (\Exception $e) {
            $this->dispatch('show-toast', [ 'message' => 'Error approving candidacy: ' . $e->getMessage(), 'type' => 'error', 'title' => 'Error' ]);
        }
    }

    /**
     * Reject a candidacy by setting its status to 'rejected'
     */
    public function rejectCandidacy(int $id): void
    {
        try {
            $candidacy = applied_candidacy::find($id);
            if (!$candidacy) {
                $this->dispatch('show-toast', [ 'message' => 'Candidacy application not found.', 'type' => 'error', 'title' => 'Not Found' ]);
                return;
            }

            $candidacy->status = 'rejected';
            $candidacy->save();

            // Create a notification for the student
            try {
                \App\Models\Notification::create([
                    'type' => 'App\\Notifications\\DocumentNotification',
                    'user_id' => $candidacy->students_id,
                    'message' => 'Your candidacy application #' . $candidacy->id . ' has been rejected.',
                    'title' => 'Candidacy Rejected',
                    'status' => 'pending',
                    'documentable_type' => get_class($candidacy),
                    'documentable_id' => $candidacy->id,
                    'notifiable_type' => 'App\\Models\\students',
                    'notifiable_id' => (string) $candidacy->students_id,
                    'icon' => 'x',
                    'icon_color' => 'red',
                    'url' => '/candidacy-management',
                ]);
            } catch (\Exception $e) {
                \Log::error('Failed to create notification for rejected candidacy: ' . $e->getMessage());
            }

            $this->dispatch('show-toast', [ 'message' => 'Candidacy application rejected.', 'type' => 'success', 'title' => 'Rejected' ]);
            $this->resetPage();
        } catch (\Exception $e) {
            $this->dispatch('show-toast', [ 'message' => 'Error rejecting candidacy: ' . $e->getMessage(), 'type' => 'error', 'title' => 'Error' ]);
        }
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



    public function getActiveVoting()
    {
        try {
            // Get the active school year and semester
            $activeSchoolYear = school_year_and_semester::active()->first();
            
            if ($activeSchoolYear) {
                // Check if there's an active voting period for the active school year
                $this->activeVoting = voting_exclusive::where('status', 'active')
                    ->where('school_year_id', $activeSchoolYear->id)
                    ->first();
                    
            } else {
                $this->activeVoting = null;
            }
        } catch (\Exception $e) {
            $this->activeVoting = null;
            \Log::error('Error getting active voting: ' . $e->getMessage());
        }
    }


public function render()
{
    $perPage = (int) request()->get('perPage', 10);
    if ($perPage <= 0) { 
        $perPage = 10; 
    }

    // Get active voting status
    $this->getActiveVoting();

    // Show candidacies only from the active school year and semester
    $activeSchoolYear = school_year_and_semester::active()->first();
    $query = applied_candidacy::with(['students', 'position', 'school_year_and_semester'])
        ->where('school_year_and_semester_id', $activeSchoolYear ? $activeSchoolYear->id : 0);
    
    // Apply search filter
    if (!empty($this->search)) {
        $searchTerm = '%' . $this->search . '%';
        $query->where(function($q) use ($searchTerm) {
            $q->whereHas('students', function($studentQuery) use ($searchTerm) {
                $studentQuery->where('first_name', 'like', $searchTerm)
                    ->orWhere('last_name', 'like', $searchTerm)
                    ->orWhere('middle_name', 'like', $searchTerm)
                    ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", [$searchTerm]);
            })
            ->orWhereHas('position', function($positionQuery) use ($searchTerm) {
                $positionQuery->where('position_name', 'like', $searchTerm);
            })
            ->orWhere('status', 'like', $searchTerm);
        });
    }
    
    $query->orderByDesc('id');

    // If you want to show all candidacies regardless of school year, comment out the above and use:
    // $query = applied_candidacy::with(['students', 'position', 'school_year_and_semester'])
    //     ->orderByDesc('id');

    $candidacies = $query->paginate($perPage);

    $positions = \App\Models\position::active()->get();
    $courses = \App\Models\course::active()->get();
    $partylists = \App\Models\partylist::where('status', 'active')->get();
    $signatories = \App\Models\set_signatory::where('status', 'active')->get();

    // Attach signatory action names
    $signatoryActions = \DB::table('signatory_action')
        ->where('status', 'active')
        ->pluck('action_name', 'id')
        ->toArray();

    $signatories->each(function ($signatory) use ($signatoryActions) {
        $signatory->action_name = $signatoryActions[$signatory->signatory_action_id] ?? 'Signatory';
    });

    return view('livewire.candidacy-management.candidacy-management1', [
        'candidacies' => $candidacies,
        'positions' => $positions,
        'courses' => $courses,
        'partylists' => $partylists,
        'signatories' => $signatories,
        'schoolYears' => $this->schoolYears,
    ]);
}


}
