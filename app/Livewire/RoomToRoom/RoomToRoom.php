<?php

namespace App\Livewire\RoomToRoom;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\room_campaign;
use App\Models\students;
use App\Models\set_signatory;
use Illuminate\Support\Facades\Auth;

class RoomToRoom extends Component
{
    use WithPagination;

    // Public properties
    public $search = '';
    public $filterStatus = '';
    public $perPage = 10;

    // Modal properties
    public $showAddCampaignModal = false;
    public $showEditCampaignModal = false;
    public $showDeleteCampaignModal = false;
    public $showApproveModal = false;
    public $showDeclineModal = false;

    // Form properties
    public $campaign_id;
    public $room_name = '';
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
        $this->resetPage();
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
        $this->showAddCampaignModal = true;
    }

    public function openEditModal($campaignId)
    {
        $campaign = room_campaign::findOrFail($campaignId);
        $this->campaign_id = $campaign->id;
        $this->room_name = $campaign->room_name;
        $this->description = $campaign->description;
        $this->start_datetime = $campaign->start_datetime;
        $this->end_datetime = $campaign->end_datetime;
        $this->status = $campaign->status;
        $this->showEditCampaignModal = true;
    }

    public function openDeleteModal($campaignId)
    {
        $this->campaign_id = $campaignId;
        $this->showDeleteCampaignModal = true;
    }

    public function createCampaign()
    {
        $this->validate([
            'room_name' => 'required|string|max:255',
            'selected_student_id' => 'required|exists:students,id',
            'description' => 'required|string',
            'start_datetime' => 'required|date|after:now',
            'end_datetime' => 'required|date|after:start_datetime',
        ]);

        try {
            // Get selected student
            $selectedStudent = students::find($this->selected_student_id);
            if (!$selectedStudent) {
                $this->dispatch('show-toast', [
                    'type' => 'error',
                    'title' => 'Error',
                    'message' => 'Selected student not found.',
                ]);
                return;
            }

            // Create room campaign
            $campaign = room_campaign::create([
                'students_id' => $selectedStudent->id,
                'room_name' => $this->room_name,
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
                            'message' => $selectedStudent->first_name . ' ' . $selectedStudent->last_name . ', your schedule for the room to room campaign "' . $this->room_name . '" has been created.',
                            'title' => 'New Room Campaign Created',
                            'status' => 'active',
                            'documentable_type' => get_class($campaign),
                            'documentable_id' => $campaign->id,
                            'notifiable_type' => 'App\\Models\\students',
                            'notifiable_id' => (string) $selectedStudent->id,
                            'signatory_id' => $signatory->id,
                            'icon' => 'building-office',
                            'icon_color' => 'green',
                            'url' => '/room-to-room',
                        ]);
                }

                // Log the action
                $campaign->logActivity('room_campaign_created', [
                    'message' => 'Room campaign created for student: ' . $selectedStudent->first_name . ' ' . $selectedStudent->last_name,
                    'signatories_notified' => $existingSignatories->count(),
                ]);
            } else {
                $campaign->logActivity('room_campaign_created', [
                    'message' => 'Room campaign created for student: ' . $selectedStudent->first_name . ' ' . $selectedStudent->last_name
                ]);
            }

            $this->dispatch('show-toast', [
                'type' => 'success',
                'title' => 'Room Campaign Created!',
                'message' => 'Room campaign has been created successfully.',
            ]);

            $this->resetForm();
            $this->showAddCampaignModal = false;

        } catch (\Exception $e) {
            $this->dispatch('show-toast', [
                'type' => 'error',
                'title' => 'Error',
                'message' => 'Failed to create room campaign: ' . $e->getMessage(),
            ]);
        }
    }

    public function updateCampaign()
    {
        $this->validate([
            'room_name' => 'required|string|max:255',
            'description' => 'required|string',
            'start_datetime' => 'required|date',
            'end_datetime' => 'required|date|after:start_datetime',
        ]);

        try {
            $campaign = room_campaign::findOrFail($this->campaign_id);
            $campaign->update([
                'room_name' => $this->room_name,
                'description' => $this->description,
                'start_datetime' => $this->start_datetime,
                'end_datetime' => $this->end_datetime,
                'status' => $this->status,
            ]);

            $this->dispatch('show-toast', [
                'type' => 'success',
                'title' => 'Room Campaign Updated!',
                'message' => 'The room campaign has been updated successfully.',
            ]);

            $this->resetForm();
            $this->showEditCampaignModal = false;

        } catch (\Exception $e) {
            $this->dispatch('show-toast', [
                'type' => 'error',
                'title' => 'Error',
                'message' => 'Failed to update room campaign: ' . $e->getMessage(),
            ]);
        }
    }

    public function deleteCampaign()
    {
        try {
            $campaign = room_campaign::findOrFail($this->campaign_id);
            $campaign->delete();

            $this->dispatch('show-toast', [
                'type' => 'success',
                'title' => 'Room Campaign Deleted!',
                'message' => 'The room campaign has been deleted successfully.',
            ]);

            $this->showDeleteCampaignModal = false;
            $this->campaign_id = null;

        } catch (\Exception $e) {
            $this->dispatch('show-toast', [
                'type' => 'error',
                'title' => 'Error',
                'message' => 'Failed to delete room campaign: ' . $e->getMessage(),
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
        if (Auth::check()) {
            return Auth::id();
        } elseif (Auth::guard('students')->check()) {
            return Auth::guard('students')->id();
        }
        return null;
    }

    private function resetForm()
    {
        $this->campaign_id = null;
        $this->room_name = '';
        $this->selected_student_id = '';
        $this->description = '';
        $this->start_datetime = '';
        $this->end_datetime = '';
        $this->status = 'active';
        $this->declineReason = '';
    }

    public function render()
    {
        $query = room_campaign::with(['students']);

        // Apply search filter
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('room_name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%')
                  ->orWhereHas('students', function ($studentQuery) {
                      $studentQuery->where('first_name', 'like', '%' . $this->search . '%')
                                   ->orWhere('last_name', 'like', '%' . $this->search . '%');
                  });
            });
        }

        // Apply status filter
        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        $campaigns = $query->orderBy('created_at', 'desc')->paginate($this->perPage);

        // Get students with approved candidacy applications
        $approvedStudents = students::whereHas('applied_candidacies', function ($query) {
            $query->where('status', 'approved');
        })->orderBy('first_name')->orderBy('last_name')->get();

        return view('livewire.room-to-room.room-to-room', [
            'campaigns' => $campaigns,
            'approvedStudents' => $approvedStudents,
        ]);
    }
}
