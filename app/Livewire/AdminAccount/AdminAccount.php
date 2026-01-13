<?php

namespace App\Livewire\AdminAccount;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\ActionLog;
use Illuminate\Support\Facades\Auth;

class AdminAccount extends Component
{
    use WithPagination, WithFileUploads;

    // Search and filter properties
    public $search = '';
    public $filterRole = '';
    public $filterStatus = '';
    public $perPage = 10;

    // Modal properties
    public $showAddModal = false;
    public $showEditModal = false;
    public $showDeleteModal = false;

    // Form properties
    public $user_id;
    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $role = 'stsg';
    public $status = 'active';
    public $profile_image;
    public $temp_profile_image;

    // Delete confirmation
    public $deleteUserId;

    protected $queryString = [
        'search' => ['except' => ''],
        'filterRole' => ['except' => ''],
        'filterStatus' => ['except' => ''],
    ];

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:8|confirmed',
    'role' => 'required|in:superadmin,stsg,selecom',
        'status' => 'required|in:active,inactive',
        'profile_image' => 'nullable|file|mimes:jpg,jpeg,png,gif|max:10240',
    ];

    protected $messages = [
        'name.required' => 'Name is required.',
        'email.required' => 'Email is required.',
        'email.email' => 'Please enter a valid email address.',
        'email.unique' => 'This email is already taken.',
        'password.required' => 'Password is required.',
        'password.min' => 'Password must be at least 8 characters.',
        'password.confirmed' => 'Password confirmation does not match.',
        'role.required' => 'Role is required.',
        'status.required' => 'Status is required.',
        'profile_image.image' => 'Profile image must be an image file.',
        'profile_image.max' => 'Profile image must not exceed 10MB.',
    ];

    public function mount()
    {
        $this->perPage = 10;
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterRole()
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
        $this->filterRole = '';
        $this->filterStatus = '';
        $this->resetPage();
    }

    public function openAddModal()
    {
        $this->resetForm();
        $this->showAddModal = true;
    }

    public function openEditModal($userId)
    {
        $user = User::findOrFail($userId);
        $this->user_id = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->password = '';
        $this->password_confirmation = '';
        $this->role = $user->role;
        $this->status = $user->status;
        $this->temp_profile_image = $user->profile_image;
        $this->profile_image = null;
        $this->showEditModal = true;
    }

    public function openDeleteModal($userId)
    {
        $this->deleteUserId = $userId;
        $this->showDeleteModal = true;
    }

    public function cancelDelete()
    {
        $this->deleteUserId = null;
        $this->showDeleteModal = false;
    }

    public function createUser()
    {
        $this->validate();

        try {
            $profileImagePath = null;
            
            if ($this->profile_image) {
                $profileImagePath = $this->profile_image->store('profile_picture', 'public');
            }

            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'role' => $this->role,
                'status' => $this->status,
                'profile_image' => $profileImagePath,
            ]);

            // Log the admin account creation
            $user->logActivity('admin_account_created', [
                'message' => 'Admin account created: ' . $this->name . ' (' . $this->email . ')',
                'role' => $this->role,
                'status' => $this->status,
                'profile_image_uploaded' => $profileImagePath ? true : false,
            ]);

            $this->dispatch('show-toast', [
                'type' => 'success',
                'title' => 'Success!',
                'message' => 'Admin account created successfully.',
            ]);

            $this->resetForm();
            $this->showAddModal = false;
        } catch (\Exception $e) {
            $this->dispatch('show-toast', [
                'type' => 'error',
                'title' => 'Error!',
                'message' => 'Failed to create admin account: ' . $e->getMessage(),
            ]);
        }
    }

    public function updateUser()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->user_id,
            'role' => 'required|in:superadmin,stsg,selecom',
            'status' => 'required|in:active,inactive',
            'profile_image' => 'nullable|file|mimes:jpg,jpeg,png,gif|max:10240',
        ];

        if (!empty($this->password)) {
            $rules['password'] = 'min:8|confirmed';
        }

        $this->validate($rules);

        try {
            $user = User::findOrFail($this->user_id);
            
            $updateData = [
                'name' => $this->name,
                'email' => $this->email,
                'role' => $this->role,
                'status' => $this->status,
            ];

            if (!empty($this->password)) {
                $updateData['password'] = Hash::make($this->password);
            }

            if ($this->profile_image) {
                // Delete old profile image if exists
                if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
                    Storage::disk('public')->delete($user->profile_image);
                }
                
                $updateData['profile_image'] = $this->profile_image->store('profile_picture', 'public');
            }

            $user->update($updateData);

            // Log the admin account update
            $user->logActivity('admin_account_updated', [
                'message' => 'Admin account updated: ' . $this->name . ' (' . $this->email . ')',
                'role' => $this->role,
                'status' => $this->status,
                'password_changed' => !empty($this->password),
                'profile_image_updated' => $this->profile_image ? true : false,
            ]);

            $this->dispatch('show-toast', [
                'type' => 'success',
                'title' => 'Success!',
                'message' => 'Admin account updated successfully.',
            ]);

            $this->resetForm();
            $this->showEditModal = false;
        } catch (\Exception $e) {
            $this->dispatch('show-toast', [
                'type' => 'error',
                'title' => 'Error!',
                'message' => 'Failed to update admin account: ' . $e->getMessage(),
            ]);
        }
    }

    public function deleteConfirmed()
    {
        try {
            $user = User::findOrFail($this->deleteUserId);
            
            // Log the admin account deletion before deleting
            $user->logActivity('admin_account_deleted', [
                'message' => 'Admin account deleted: ' . $user->name . ' (' . $user->email . ')',
                'role' => $user->role,
                'status' => $user->status,
            ]);

            // Delete profile image if exists
            if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
                Storage::disk('public')->delete($user->profile_image);
            }
            
            $user->delete();

            $this->dispatch('show-toast', [
                'type' => 'success',
                'title' => 'Success!',
                'message' => 'Admin account deleted successfully.',
            ]);

            $this->cancelDelete();
        } catch (\Exception $e) {
            $this->dispatch('show-toast', [
                'type' => 'error',
                'title' => 'Error!',
                'message' => 'Failed to delete admin account: ' . $e->getMessage(),
            ]);
        }
    }

    private function resetForm()
    {
        $this->user_id = null;
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->role = 'stsg';
        $this->status = 'active';
        $this->profile_image = null;
        $this->temp_profile_image = null;
        $this->deleteUserId = null;
    }


    /**
     * Get current logged-in user ID from either guard
     */
    private function getCurrentUserId(): ?int
    {
        // Try to get from default guard (User model)
        if (Auth::check()) {
            return Auth::id();
        }

        // Try to get from students guard
        if (Auth::guard('students')->check()) {
            return Auth::guard('students')->id();
        }

        // Return null if no user is authenticated
        return null;
    }

    public function render()
    {
        $query = User::query();

        // Apply search filter
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        // Apply role filter
        if (!empty($this->filterRole)) {
            $query->where('role', $this->filterRole);
        }

        // Apply status filter
        if (!empty($this->filterStatus)) {
            $query->where('status', $this->filterStatus);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate($this->perPage);

        return view('livewire.admin-account.admin-account', [
            'users' => $users,
        ]);
    }
}
