<?php

namespace App\Livewire\STSGAdminNotification;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Notification;
use App\Models\ActionLog;
use Illuminate\Support\Facades\Auth;

class STSGAdminNotification extends Component
{
    use WithPagination;

    // Search and filter properties
    public $search = '';
    public $filterStatus = '';
    public $filterType = '';
    public $perPage = 10;

    // Modal properties
    public $showDeleteModal = false;
    public $showBulkDeleteModal = false;

    // Selected notifications for bulk actions
    public $selectedNotifications = [];
    public $selectAll = false;

    // Delete confirmation
    public $deleteNotificationId;
    public $bulkDeleteIds = [];

    protected $queryString = [
        'search' => ['except' => ''],
        'filterStatus' => ['except' => ''],
        'filterType' => ['except' => ''],
    ];

    protected $listeners = ['markAsRead', 'refreshNotifications'];

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

    public function updatedSelectAll()
    {
        if ($this->selectAll) {
            $this->selectedNotifications = $this->getNotifications()->pluck('id')->toArray();
        } else {
            $this->selectedNotifications = [];
        }
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
        
        $notification = Notification::where('id', $notificationId)
            ->where('user_id', $userId)
            ->where('notifiable_id', (string) $userId)
            ->where('notifiable_type', 'App\\Models\\User')
            ->first();

        if ($notification) {
            $notification->update(['read_at' => now()]);
            
            $this->dispatch('show-toast', [
                'type' => 'success',
                'title' => 'Success!',
                'message' => 'Notification marked as read.',
            ]);
        }
    }

    public function markAsUnread($notificationId)
    {
        $userId = $this->getCurrentUserId();
        
        $notification = Notification::where('id', $notificationId)
            ->where('user_id', $userId)
            ->where('notifiable_id', (string) $userId)
            ->where('notifiable_type', 'App\\Models\\User')
            ->first();

        if ($notification) {
            $notification->update(['read_at' => null]);
            
            $this->dispatch('show-toast', [
                'type' => 'success',
                'title' => 'Success!',
                'message' => 'Notification marked as unread.',
            ]);
        }
    }

    public function markAllAsRead()
    {
        $userId = $this->getCurrentUserId();
        
        $updatedCount = Notification::where('user_id', $userId)
            ->where('notifiable_id', (string) $userId)
            ->where('notifiable_type', 'App\\Models\\User')
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $this->dispatch('show-toast', [
            'type' => 'success',
            'title' => 'Success!',
            'message' => "Marked {$updatedCount} notifications as read.",
        ]);
    }

    public function markAllAsUnread()
    {
        $userId = $this->getCurrentUserId();
        
        $updatedCount = Notification::where('user_id', $userId)
            ->where('notifiable_id', (string) $userId)
            ->where('notifiable_type', 'App\\Models\\User')
            ->whereNotNull('read_at')
            ->update(['read_at' => null]);

        $this->dispatch('show-toast', [
            'type' => 'success',
            'title' => 'Success!',
            'message' => "Marked {$updatedCount} notifications as unread.",
        ]);
    }

    public function openDeleteModal($notificationId)
    {
        $this->deleteNotificationId = $notificationId;
        $this->showDeleteModal = true;
    }

    public function openBulkDeleteModal()
    {
        if (empty($this->selectedNotifications)) {
            $this->dispatch('show-toast', [
                'type' => 'warning',
                'title' => 'Warning!',
                'message' => 'Please select notifications to delete.',
            ]);
            return;
        }
        
        $this->bulkDeleteIds = $this->selectedNotifications;
        $this->showBulkDeleteModal = true;
    }

    public function deleteNotification()
    {
        $userId = $this->getCurrentUserId();
        
        $notification = Notification::where('id', $this->deleteNotificationId)
            ->where('user_id', $userId)
            ->where('notifiable_id', (string) $userId)
            ->where('notifiable_type', 'App\\Models\\User')
            ->first();

        if ($notification) {
            $notification->delete();
            
            $this->dispatch('show-toast', [
                'type' => 'success',
                'title' => 'Success!',
                'message' => 'Notification deleted successfully.',
            ]);
        }

        $this->showDeleteModal = false;
        $this->deleteNotificationId = null;
    }

    public function bulkDeleteNotifications()
    {
        $userId = $this->getCurrentUserId();
        
        $deletedCount = Notification::where('user_id', $userId)
            ->where('notifiable_id', (string) $userId)
            ->where('notifiable_type', 'App\\Models\\User')
            ->whereIn('id', $this->bulkDeleteIds)
            ->delete();

        $this->dispatch('show-toast', [
            'type' => 'success',
            'title' => 'Success!',
            'message' => "Deleted {$deletedCount} notifications successfully.",
        ]);

        $this->showBulkDeleteModal = false;
        $this->bulkDeleteIds = [];
        $this->selectedNotifications = [];
        $this->selectAll = false;
    }

    public function cancelDelete()
    {
        $this->showDeleteModal = false;
        $this->deleteNotificationId = null;
    }

    public function cancelBulkDelete()
    {
        $this->showBulkDeleteModal = false;
        $this->bulkDeleteIds = [];
    }

    public function refreshNotifications()
    {
        // This method can be called to refresh the notifications list
        $this->resetPage();
    }

    /**
     * Get current logged-in STSG Admin user ID
     */
    private function getCurrentUserId(): ?int
    {
        if (Auth::check()) {
            $user = Auth::user();
            // Ensure it's a STSG admin
            if ($user && $user->role === 'stsg') {
                return $user->id;
            }
        }
        return null;
    }

    /**
     * Get notifications query for current STSG Admin
     */
    private function getNotifications()
    {
        $userId = $this->getCurrentUserId();
        
        if (!$userId) {
            return collect();
        }

        $query = Notification::where('user_id', $userId)
            ->where('notifiable_id', (string) $userId)
            ->where('notifiable_type', 'App\\Models\\User')
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

        return $query->orderBy('created_at', 'desc');
    }

    public function render()
    {
        $userId = $this->getCurrentUserId();
        
        if (!$userId) {
            return view('livewire.stsg-admin-notification.stsg-admin-notification', [
                'notifications' => new \Illuminate\Pagination\LengthAwarePaginator([], 0, $this->perPage),
                'unreadCount' => 0,
                'totalCount' => 0,
            ]);
        }

        $notifications = $this->getNotifications()->paginate($this->perPage);

        $unreadCount = Notification::where('user_id', $userId)
            ->where('notifiable_id', (string) $userId)
            ->where('notifiable_type', 'App\\Models\\User')
            ->whereNull('read_at')
            ->count();

        $totalCount = Notification::where('user_id', $userId)
            ->where('notifiable_id', (string) $userId)
            ->where('notifiable_type', 'App\\Models\\User')
            ->count();

        return view('livewire.stsg-admin-notification.stsg-admin-notification', [
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
            'totalCount' => $totalCount,
        ]);
    }
}
