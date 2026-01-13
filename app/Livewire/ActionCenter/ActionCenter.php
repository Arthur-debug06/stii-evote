<?php

namespace App\Livewire\ActionCenter;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Notification;
use App\Models\ActionLog;

class ActionCenter extends Component
{
    use WithPagination;

    public $search = '';
    public $filterStatus = '';
    public $filterType = '';
    public $perPage = 10;
    protected $listeners = ['markAsRead'];
    public $activityLogsPerPage = 5;
    public $declineNotificationId = null;
    public $declineCandidacyId = null;
    public $showDeclineModal = false;
    public $approveNotificationId = null;
    public $showApproveModal = false;
    public $declineReason = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'filterStatus' => ['except' => ''],
        'filterType' => ['except' => ''],
    ];

    public function mount()
    {
        // Initialize any default values
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterStatus()
    {
        $this->resetPage();
    }

    public function updatedFilterType()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->filterStatus = '';
        $this->filterType = '';
        $this->resetPage();
    }

    public function markAsRead($notificationId)
    {
        $userId = $this->getCurrentUserId();
        $userType = auth()->check() ? 'App\\Models\\User' : 'App\\Models\\students';
        
        $notification = Notification::where('id', $notificationId)
            ->where('user_id', $userId)
            ->where('notifiable_id', (string) $userId) // Only allow marking notifications where current user is the notifiable_id
            ->where('notifiable_type', $userType) // Match the user type
            ->first();

        if ($notification) {
            $notification->update(['read_at' => now()]);
        }
    }



    public function markAllAsRead()
    {
        $userId = $this->getCurrentUserId();
        $userType = auth()->check() ? 'App\\Models\\User' : 'App\\Models\\students';
        
        Notification::where('user_id', $userId)
            ->where('notifiable_id', (string) $userId) // Only mark notifications where current user is the notifiable_id
            ->where('notifiable_type', $userType) // Match the user type
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $this->dispatch('show-toast', [
            'type' => 'success',
            'title' => 'All Notifications Marked as Read',
            'message' => 'All your notifications have been marked as read.',
        ]);
    }

    private function getCurrentUserId()
    {
        if (auth()->check()) {
            return auth()->id();
        } elseif (auth()->guard('students')->check()) {
            return auth()->guard('students')->id();
        }
        return null;
    }

    public function render()
    {
        $userId = $this->getCurrentUserId();
        
        if (!$userId) {
            return view('livewire.action-center.action-center', [
                'notifications' => new \Illuminate\Pagination\LengthAwarePaginator([], 0, $this->perPage),
                'unreadCount' => 0,
                'totalCount' => 0,
            ]);
        }

        // Get current user type
        $userType = auth()->check() ? 'App\\Models\\User' : 'App\\Models\\students';
        
        $query = Notification::where('user_id', $userId)
            ->where('notifiable_id', (string) $userId) // Only show notifications where current user is the notifiable_id
            ->where('notifiable_type', $userType) // Match the user type
            ->with(['documentable']);

        // Apply search filter
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('message', 'like', '%' . $this->search . '%');
            });
        }

        // Apply status filter
        if ($this->filterStatus) {
            if ($this->filterStatus === 'read') {
                $query->whereNotNull('read_at');
            } elseif ($this->filterStatus === 'unread') {
                $query->whereNull('read_at');
            } else {
                $query->where('status', $this->filterStatus);
            }
        }

        // Apply type filter
        if ($this->filterType) {
            $query->where('type', 'like', '%' . $this->filterType . '%');
        }

        $notifications = $query->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        $unreadCount = Notification::where('user_id', $userId)
            ->where('notifiable_id', (string) $userId) // Only count notifications where current user is the notifiable_id
            ->where('notifiable_type', $userType) // Match the user type
            ->whereNull('read_at')
            ->count();

        $totalCount = Notification::where('user_id', $userId)
            ->where('notifiable_id', (string) $userId) // Only count notifications where current user is the notifiable_id
            ->where('notifiable_type', $userType) // Match the user type
            ->count();

        // Get activity logs for the current user
        $activityLogs = ActionLog::where('user_id', $userId)
            ->with(['trackable'])
            ->orderBy('created_at', 'desc')
            ->paginate($this->activityLogsPerPage, ['*'], 'activity_page');

        return view('livewire.action-center.action-center', [
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
            'totalCount' => $totalCount,
            'activityLogs' => $activityLogs,
        ]);
    }

    /**
     * Show approve confirmation modal
     */
    public function confirmApprove($notificationId)
    {
        try {
            $userId = $this->getCurrentUserId();
            $userType = auth()->check() ? 'App\\Models\\User' : 'App\\Models\\students';
            
            $notification = \App\Models\Notification::where('id', $notificationId)
                ->where('user_id', $userId)
                ->where('notifiable_id', (string) $userId) // Only allow confirming notifications where current user is the notifiable_id
                ->where('notifiable_type', $userType) // Match the user type
                ->firstOrFail();
            
            // Set the notification ID for the modal
            $this->approveNotificationId = $notificationId;
            
            // Show the modal
            $this->showApproveModal = true;
            
        } catch (\Exception $e) {
            $this->dispatch('show-toast', [
                'type' => 'error',
                'title' => 'Error',
                'message' => 'Failed to process approve request: ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * Approve candidacy application
     */
    public function approveCandidacy($notificationId)
    {
        try {
            $userId = $this->getCurrentUserId();
            $userType = auth()->check() ? 'App\\Models\\User' : 'App\\Models\\students';
            
            $notification = \App\Models\Notification::where('id', $notificationId)
                ->where('user_id', $userId)
                ->where('notifiable_id', (string) $userId) // Only allow approving notifications where current user is the notifiable_id
                ->where('notifiable_type', $userType) // Match the user type
                ->firstOrFail();
            
            // Get the candidacy application
            $candidacy = \App\Models\applied_candidacy::findOrFail($notification->documentable_id);
            
            // Update candidacy status to approved
            $candidacy->update([
                'status' => 'approved',
                'remarks' => 'Your application is approved'
            ]);
            
            // Update notification status
            $notification->update([
                'status' => 'approved',
                'read_at' => now()
            ]);
            
            // Log the approval action using LoggerTrait
            $candidacy->logActivity('candidacy_approved', [
                'message' => 'Candidacy application approved by signatory',
                'approved_by' => $this->getCurrentUserId(),
                'notification_id' => $notificationId
            ]);
            
            // Send notification to student about approval
            $this->sendStudentNotification($candidacy, 'approved', 'Your candidacy application has been approved.');
            
            $this->dispatch('show-toast', [
                'type' => 'success',
                'title' => 'Candidacy Approved!',
                'message' => 'The candidacy application has been approved successfully.',
            ]);
            
            // Close the modal and clear properties
            $this->showApproveModal = false;
            $this->approveNotificationId = null;
            
        } catch (\Exception $e) {
            $this->dispatch('show-toast', [
                'type' => 'error',
                'title' => 'Error',
                'message' => 'Failed to approve candidacy: ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * Decline candidacy application
     */
    public function declineCandidacy($notificationId)
    {
        try {
            $userId = $this->getCurrentUserId();
            $userType = auth()->check() ? 'App\\Models\\User' : 'App\\Models\\students';
            
            $notification = \App\Models\Notification::where('id', $notificationId)
                ->where('user_id', $userId)
                ->where('notifiable_id', (string) $userId) // Only allow declining notifications where current user is the notifiable_id
                ->where('notifiable_type', $userType) // Match the user type
                ->firstOrFail();
            
            // Get the candidacy application
            $candidacy = \App\Models\applied_candidacy::findOrFail($notification->documentable_id);
            
            // Set the properties for the modal
            $this->declineNotificationId = $notificationId;
            $this->declineCandidacyId = $candidacy->id;
            
            // Show the modal
            $this->showDeclineModal = true;
            
        } catch (\Exception $e) {
            $this->dispatch('show-toast', [
                'type' => 'error',
                'title' => 'Error',
                'message' => 'Failed to process decline request: ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * Process candidacy decline with reason
     */
    public function processDecline()
    {
        // Validate decline reason
        if (empty(trim($this->declineReason))) {
            $this->dispatch('show-toast', [
                'type' => 'error',
                'title' => 'Validation Error',
                'message' => 'Please provide a reason for declining the candidacy application.',
            ]);
            return;
        }

        try {
            $userId = $this->getCurrentUserId();
            $userType = auth()->check() ? 'App\\Models\\User' : 'App\\Models\\students';
            
            $notification = \App\Models\Notification::where('id', $this->declineNotificationId)
                ->where('user_id', $userId)
                ->where('notifiable_id', (string) $userId) // Only allow processing notifications where current user is the notifiable_id
                ->where('notifiable_type', $userType) // Match the user type
                ->firstOrFail();
            $candidacy = \App\Models\applied_candidacy::findOrFail($this->declineCandidacyId);
            
            // Update candidacy status to declined
            $candidacy->update([
                'status' => 'declined',
                'remarks' => $this->declineReason
            ]);
            
            // Update notification status
            $notification->update([
                'status' => 'rejected',
                'read_at' => now()
            ]);
            
            // Log the decline action using LoggerTrait
            $candidacy->logActivity('candidacy_declined', [
                'message' => 'Candidacy application declined by signatory',
                'declined_by' => $this->getCurrentUserId(),
                'reason' => $this->declineReason,
                'notification_id' => $this->declineNotificationId
            ]);
            
            // Send notification to student about decline
            $this->sendStudentNotification($candidacy, 'declined', 'Your candidacy application has been declined. Reason: ' . $this->declineReason);
            
            $this->dispatch('show-toast', [
                'type' => 'error',
                'title' => 'Candidacy Declined',
                'message' => 'The candidacy application has been declined.',
            ]);
            
            // Close the modal and clear properties
            $this->showDeclineModal = false;
            $this->declineNotificationId = null;
            $this->declineCandidacyId = null;
            $this->declineReason = '';
            
        } catch (\Exception $e) {
            $this->dispatch('show-toast', [
                'type' => 'error',
                'title' => 'Error',
                'message' => 'Failed to decline candidacy: ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * Send notification to student about candidacy status
     */
    private function sendStudentNotification($candidacy, $status, $message)
    {
        try {
            // Get student information
            $student = $candidacy->students;
            if (!$student) {
                return;
            }
            
            // Create notification for student
            \App\Models\Notification::create([
                'type' => 'App\\Notifications\\DocumentNotification',
                'user_id' => $student->id,
                'message' => $message,
                'title' => 'Candidacy Application Update',
                'status' => $status,
                'documentable_type' => get_class($candidacy),
                'documentable_id' => $candidacy->id,
                'notifiable_type' => 'App\\Models\\students',
                'notifiable_id' => (string) $student->id,
                'icon' => $status === 'approved' ? 'check-circle' : 'x-circle',
                'icon_color' => $status === 'approved' ? 'green' : 'red',
                'url' => '/candidacy-management',
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Failed to send student notification: ' . $e->getMessage());
        }
    }
}
