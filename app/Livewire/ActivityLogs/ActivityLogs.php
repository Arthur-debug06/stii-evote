<?php

namespace App\Livewire\ActivityLogs;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ActionLog;
use Illuminate\Support\Facades\Auth;

class ActivityLogs extends Component
{
    use WithPagination;

    // Search and filter properties
    public $search = '';
    public $filterAction = '';
    public $filterUser = '';
    public $filterDateRange = '';
    public $perPage = 10;

    protected $queryString = [
        'search' => ['except' => ''],
        'filterAction' => ['except' => ''],
        'filterUser' => ['except' => ''],
        'filterDateRange' => ['except' => ''],
    ];

    public function mount()
    {
        $this->perPage = 10;
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterAction()
    {
        $this->resetPage();
    }

    public function updatedFilterUser()
    {
        $this->resetPage();
    }

    public function updatedFilterDateRange()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->filterAction = '';
        $this->filterUser = '';
        $this->filterDateRange = '';
        $this->resetPage();
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

    public function render()
    {
        $query = ActionLog::with(['trackable']);

        // Apply search filter
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('details', 'like', '%' . $this->search . '%')
                  ->orWhere('action', 'like', '%' . $this->search . '%')
                  ->orWhere('document_type', 'like', '%' . $this->search . '%')
                  ->orWhere('remarks', 'like', '%' . $this->search . '%');
            });
        }

        // Apply action filter
        if (!empty($this->filterAction)) {
            $query->where('action', $this->filterAction);
        }

        // Apply user filter
        if (!empty($this->filterUser)) {
            $query->where('user_id', $this->filterUser);
        }

        // Apply date range filter
        if (!empty($this->filterDateRange)) {
            // Handle EasePick date range format (comma-separated dates)
            if (strpos($this->filterDateRange, ',') !== false) {
                $dates = explode(',', $this->filterDateRange);
                if (count($dates) === 2) {
                    $query->whereBetween('created_at', [
                        \Carbon\Carbon::parse(trim($dates[0]))->startOfDay(),
                        \Carbon\Carbon::parse(trim($dates[1]))->endOfDay()
                    ]);
                } elseif (count($dates) === 1) {
                    $query->whereDate('created_at', trim($dates[0]));
                }
            } 
            // Handle "to" format as fallback
            elseif (strpos($this->filterDateRange, ' to ') !== false) {
                $dates = explode(' to ', $this->filterDateRange);
                if (count($dates) === 2) {
                    $query->whereBetween('created_at', [
                        \Carbon\Carbon::parse($dates[0])->startOfDay(),
                        \Carbon\Carbon::parse($dates[1])->endOfDay()
                    ]);
                }
            } 
            // Handle single date
            else {
                $query->whereDate('created_at', $this->filterDateRange);
            }
        }

        // Role-based visibility restrictions:
        // - If an admin user is logged in and their role is 'stsg' or 'selecom', show only their logs
        // - If an admin user is 'superadmin', do not restrict (can see all)
        // - If a student is logged in via the students guard, show only their logs
        $adminUser = Auth::user();
        if ($adminUser) {
            $role = $adminUser->role ?? null;
            if (in_array($role, ['stsg', 'selecom'])) {
                $query->where('user_id', $adminUser->id);
            }
            // superadmin -> no restriction
        } elseif (Auth::guard('students')->check()) {
            $student = Auth::guard('students')->user();
            if ($student) {
                $query->where('user_id', $student->id);
            }
        }

        $activityLogs = $query->orderBy('created_at', 'desc')->paginate($this->perPage);

        // Get unique actions for filter dropdown
        $actions = ActionLog::distinct()->pluck('action')->sort()->values();

        // Get unique users for filter dropdown
        $userIds = ActionLog::distinct()->pluck('user_id')->filter();
        $users = collect();
        
        foreach ($userIds as $userId) {
            // Try to find in User model first (admin/staff)
            $user = \App\Models\User::find($userId);
            if ($user) {
                $users->push([
                    'id' => $userId,
                    'name' => $user->name ?? 'Unknown User'
                ]);
                continue;
            }
            
            // Try to find in students model
            $student = \App\Models\students::find($userId);
            if ($student) {
                $name = trim($student->first_name . ' ' . $student->middle_name . ' ' . $student->last_name);
                $suffix = $student->suffix ? ', ' . $student->suffix : '';
                $users->push([
                    'id' => $userId,
                    'name' => $name . $suffix ?: 'Unknown Student'
                ]);
                continue;
            }
            
            // If not found in either model, add as unknown
            $users->push([
                'id' => $userId,
                'name' => 'Unknown User'
            ]);
        }
        
        $users = $users->unique('id')->sortBy('name')->values();

        // Determine if the current session has restricted visibility (non-superadmin or student)
        $isRestricted = false;
        $restrictedUserName = null;
        $adminUser = Auth::user();
        if ($adminUser) {
            $role = $adminUser->role ?? null;
            if (in_array($role, ['stsg', 'selecom'])) {
                $isRestricted = true;
                $restrictedUserName = $adminUser->name ?? null;
            }
        } elseif (Auth::guard('students')->check()) {
            $student = Auth::guard('students')->user();
            if ($student) {
                $isRestricted = true;
                $restrictedUserName = trim(($student->first_name ?? '') . ' ' . ($student->middle_name ?? '') . ' ' . ($student->last_name ?? '')) ?: ($student->student_id ?? 'Student');
            }
        }

        return view('livewire.activity-logs.activity-logs', [
            'activityLogs' => $activityLogs,
            'actions' => $actions,
            'users' => $users,
            'isRestricted' => $isRestricted,
            'restrictedUserName' => $restrictedUserName,
        ]);
    }
}
