<?php

namespace App\Livewire\MeetingAbanse;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\meeting_de_abanse;
use App\Models\students;
use App\Models\set_signatory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class MeetingAbanse extends Component
{
    use WithPagination;

    // Search and filter properties
    public $search = '';
    public $filterStatus = '';
    public $perPage = 10;

    // Modal properties
    public $showAddMeetingModal = false;
    public $showEditMeetingModal = false;
    public $showDeleteMeetingModal = false;

    // Form properties
    public $meeting_id = null;
    public $meeting_de_abanse_name = '';
    public $selected_student_id = '';
    public $description = '';
    public $start_datetime = '';
    public $end_datetime = '';
    public $status = 'active';

    // Action properties
    public $declineReason = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'filterStatus' => ['except' => ''],
    ];

    public function mount()
    {
        $this->perPage = 10;
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterStatus()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->filterStatus = '';
        $this->resetPage();
    }

    public function openAddModal()
    {
        $this->resetForm();
        $this->showAddMeetingModal = true;
    }

    public function openEditModal($meetingId)
    {
        $meeting = meeting_de_abanse::findOrFail($meetingId);
        $this->meeting_id = $meeting->id;
        $this->meeting_de_abanse_name = $meeting->meeting_de_abanse_name;
        $this->selected_student_id = $meeting->students_id;
        $this->description = $meeting->description;
        $this->start_datetime = $meeting->start_datetime;
        $this->end_datetime = $meeting->end_datetime;
        $this->status = $meeting->status;
        $this->showEditMeetingModal = true;
    }

    public function openDeleteModal($meetingId)
    {
        $this->meeting_id = $meetingId;
        $this->showDeleteMeetingModal = true;
    }

    public function createMeeting()
    {
        try {
            $this->validate([
                'meeting_de_abanse_name' => 'required|string|max:255',
                'selected_student_id' => 'required|exists:students,id',
                'description' => 'required|string',
                'start_datetime' => 'required|date|after:now',
                'end_datetime' => 'required|date|after:start_datetime',
            ]);

            $selectedStudent = students::find($this->selected_student_id);
            if (!$selectedStudent) {
                $this->dispatch('show-toast', [
                    'type' => 'error',
                    'title' => 'Error',
                    'message' => 'Selected student not found.',
                ]);
                return;
            }

            // Create meeting de abanse
            $meeting = meeting_de_abanse::create([
                'students_id' => $selectedStudent->id,
                'meeting_de_abanse_name' => $this->meeting_de_abanse_name,
                'description' => $this->description,
                'start_datetime' => $this->start_datetime,
                'end_datetime' => $this->end_datetime,
                'status' => 'active',
            ]);

            // Get existing signatories and create notifications
            $existingSignatories = set_signatory::where('status', 'active')->get();

            if ($existingSignatories->count() > 0) {
                foreach ($existingSignatories as $signatory) {
                        \App\Models\Notification::create([
                            'type' => 'App\\Notifications\\DocumentNotification',
                            'user_id' => $signatory->users_id,
                            'message' => $selectedStudent->first_name . ' ' . $selectedStudent->last_name . ', your schedule for the meeting de abanse "' . $this->meeting_de_abanse_name . '" has been created.',
                            'title' => 'New Meeting De Abanse Created',
                            'status' => 'active',
                            'documentable_type' => get_class($meeting),
                            'documentable_id' => $meeting->id,
                            'notifiable_type' => 'App\\Models\\students',
                            'notifiable_id' => (string) $selectedStudent->id,
                            'signatory_id' => $signatory->id,
                            'icon' => 'users',
                            'icon_color' => 'green',
                            'url' => '/meeting-abanse',
                        ]);
                }

                // Log the action
                $meeting->logActivity('meeting_de_abanse_created', [
                    'message' => 'Meeting de abanse created for student: ' . $selectedStudent->first_name . ' ' . $selectedStudent->last_name,
                    'signatories_notified' => $existingSignatories->count(),
                ]);
            } else {
                $meeting->logActivity('meeting_de_abanse_created', [
                    'message' => 'Meeting de abanse created for student: ' . $selectedStudent->first_name . ' ' . $selectedStudent->last_name
                ]);
            }

            $this->dispatch('show-toast', [
                'type' => 'success',
                'title' => 'Meeting De Abanse Created!',
                'message' => 'Meeting de abanse has been created successfully.',
            ]);

            $this->resetForm();
            $this->showAddMeetingModal = false;

        } catch (\Exception $e) {
            $this->dispatch('show-toast', [
                'type' => 'error',
                'title' => 'Error',
                'message' => 'Failed to create meeting de abanse: ' . $e->getMessage(),
            ]);
        }
    }

    public function updateMeeting()
    {
        try {
            $this->validate([
                'meeting_de_abanse_name' => 'required|string|max:255',
                'description' => 'required|string',
                'start_datetime' => 'required|date',
                'end_datetime' => 'required|date|after:start_datetime',
                'status' => 'required|in:active,inactive',
            ]);

            $meeting = meeting_de_abanse::findOrFail($this->meeting_id);
            $meeting->update([
                'meeting_de_abanse_name' => $this->meeting_de_abanse_name,
                'description' => $this->description,
                'start_datetime' => $this->start_datetime,
                'end_datetime' => $this->end_datetime,
                'status' => $this->status,
            ]);

            // Log the action
            $meeting->logActivity('meeting_de_abanse_updated', [
                'message' => 'Meeting de abanse updated',
                'updated_by' => $this->getCurrentUserId(),
            ]);

            $this->dispatch('show-toast', [
                'type' => 'success',
                'title' => 'Meeting De Abanse Updated!',
                'message' => 'Meeting de abanse has been updated successfully.',
            ]);

            $this->resetForm();
            $this->showEditMeetingModal = false;

        } catch (\Exception $e) {
            $this->dispatch('show-toast', [
                'type' => 'error',
                'title' => 'Error',
                'message' => 'Failed to update meeting de abanse: ' . $e->getMessage(),
            ]);
        }
    }

    public function deleteMeeting()
    {
        try {
            $meeting = meeting_de_abanse::findOrFail($this->meeting_id);
            
            // Log the action before deletion
            $meeting->logActivity('meeting_de_abanse_deleted', [
                'message' => 'Meeting de abanse deleted',
                'deleted_by' => $this->getCurrentUserId(),
            ]);

            $meeting->delete();

            $this->dispatch('show-toast', [
                'type' => 'success',
                'title' => 'Meeting De Abanse Deleted!',
                'message' => 'Meeting de abanse has been deleted successfully.',
            ]);

            $this->showDeleteMeetingModal = false;

        } catch (\Exception $e) {
            $this->dispatch('show-toast', [
                'type' => 'error',
                'title' => 'Error',
                'message' => 'Failed to delete meeting de abanse: ' . $e->getMessage(),
            ]);
        }
    }

    private function getCurrentStudent()
    {
        if (Auth::guard('students')->check()) {
            return Auth::guard('students')->user();
        }
        return null;
    }

    private function getCurrentUserId()
    {
        if (Auth::guard('students')->check()) {
            return Auth::guard('students')->id();
        } elseif (Auth::check()) {
            return Auth::id();
        }
        return null;
    }

    private function resetForm()
    {
        $this->meeting_id = null;
        $this->meeting_de_abanse_name = '';
        $this->selected_student_id = '';
        $this->description = '';
        $this->start_datetime = '';
        $this->end_datetime = '';
        $this->status = 'active';
        $this->declineReason = '';
    }

    public function render()
    {
        $query = meeting_de_abanse::with('students');

        // Apply search filter
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('meeting_de_abanse_name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%')
                  ->orWhereHas('students', function ($studentQuery) {
                      $studentQuery->where('first_name', 'like', '%' . $this->search . '%')
                                   ->orWhere('last_name', 'like', '%' . $this->search . '%');
                  });
            });
        }

        // Apply status filter
        if (!empty($this->filterStatus)) {
            $query->where('status', $this->filterStatus);
        }

        $meetings = $query->orderBy('created_at', 'desc')->paginate($this->perPage);

        // Get students with approved candidacy applications
        $approvedStudents = students::whereHas('applied_candidacies', function ($query) {
            $query->where('status', 'approved');
        })->orderBy('first_name')->orderBy('last_name')->get();

        return view('livewire.meeting-abanse.meeting-abanse', [
            'meetings' => $meetings,
            'approvedStudents' => $approvedStudents,
        ]);
    }
}
