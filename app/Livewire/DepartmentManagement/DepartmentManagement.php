<?php

namespace App\Livewire\DepartmentManagement;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\department;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class DepartmentManagement extends Component
{
    use WithPagination;

    public string $department_name = '';
    public string $description = '';
    public string $status = 'active';
    public string $search = '';
    public bool $showAddDepartmentModal = false;
    public bool $showEditDepartmentModal = false;
    public bool $showDeleteDepartmentModal = false;
    public ?int $editingId = null;
    public ?int $deletingId = null;

    protected $rules = [
        'department_name' => 'required|string|min:2|max:255',
        'description' => 'nullable|string|max:1000',
        'status' => 'required|string|in:active,inactive',
    ];

    protected $messages = [
        'department_name.unique' => 'The department name has already been taken. Please choose a different name.',
        'department_name.required' => 'Please enter a department name.',
    ];

    public function createDepartment(): void
    {
        $this->department_name = trim($this->department_name);

        $validatedData = $this->validate([
            'department_name' => [
                'required', 'string', 'min:2', 'max:255',
                Rule::unique('department', 'department_name')->whereNull('deleted_at'),
            ],
            'description' => 'nullable|string|max:1000',
            'status' => 'required|string|in:active,inactive',
        ]);

        try {
            department::create([
                'department_name' => $validatedData['department_name'],
                'description' => $validatedData['description'] ?? null,
                'status' => $validatedData['status'],
            ]);

            // Reset form fields
            $this->reset(['department_name', 'description']);
            $this->status = 'active';

            // Close modal and show success message
            $this->showAddDepartmentModal = false;
            $this->dispatch('close-modal', id: 'add-department-modal');
            
            // Show success toast
            $this->dispatch('show-toast', [
                'message' => 'Department has been successfully created.',
                'type' => 'success',
                'title' => 'Department Created!'
            ]);
            
            $this->resetPage();
            
        } catch (\Exception $e) {
            $this->dispatch('show-toast', [
                'message' => 'Error creating department: ' . $e->getMessage(),
                'type' => 'error',
                'title' => 'Error!'
            ]);
        }
    }

    public function editDepartment(int $id): void
    {
        try {
            $department = department::find($id);
            if (!$department) {
                $this->dispatch('show-toast', [
                    'message' => 'Department not found.',
                    'type' => 'error',
                    'title' => 'Not Found'
                ]);
                return;
            }

            // Set editing state and populate form with existing data
            $this->editingId = $department->id;
            $this->department_name = (string) $department->department_name;
            $this->description = (string) $department->description;
            $this->status = (string) $department->status;

            // Open edit modal
            $this->showEditDepartmentModal = true;
        } catch (\Exception $e) {
            $this->dispatch('show-toast', [
                'message' => 'Unable to load department for editing.',
                'type' => 'error',
                'title' => 'Error'
            ]);
        }
    }

    public function updateDepartment(): void
    {
        if (!$this->editingId) {
            $this->dispatch('show-toast', [
                'message' => 'No department selected to update.',
                'type' => 'error',
                'title' => 'Error'
            ]);
            return;
        }

        $this->department_name = trim($this->department_name);

        $validatedData = $this->validate([
            'department_name' => [
                'required', 'string', 'min:2', 'max:255',
                Rule::unique('department', 'department_name')->ignore($this->editingId, 'id')->whereNull('deleted_at'),
            ],
            'description' => 'nullable|string|max:1000',
            'status' => 'required|string|in:active,inactive',
        ]);

        try {
            $department = department::find($this->editingId);
            if (!$department) {
                $this->dispatch('show-toast', [
                    'message' => 'Department not found.',
                    'type' => 'error',
                    'title' => 'Not Found'
                ]);
                return;
            }

            $department->department_name = $validatedData['department_name'];
            $department->description = $validatedData['description'] ?? null;
            $department->status = $validatedData['status'];
            $department->save();

            $this->showEditDepartmentModal = false;
            $this->dispatch('close-modal', id: 'edit-department-modal');
            $this->dispatch('show-toast', [
                'message' => 'Department updated successfully.',
                'type' => 'success',
                'title' => 'Updated'
            ]);

            $this->resetEditingState();
            $this->resetPage();
        } catch (\Exception $e) {
            $this->dispatch('show-toast', [
                'message' => 'Error updating department: ' . $e->getMessage(),
                'type' => 'error',
                'title' => 'Error'
            ]);
        }
    }

    public function deleteDepartment(int $id): void
    {
        // Open confirmation modal
        $this->deletingId = $id;
        $this->showDeleteDepartmentModal = true;
    }

    public function cancelDelete(): void
    {
        $this->showDeleteDepartmentModal = false;
        $this->deletingId = null;
    }

    public function deleteConfirmed(): void
    {
        if (!$this->deletingId) {
            $this->dispatch('show-toast', [ 'message' => 'No item selected to delete.', 'type' => 'error', 'title' => 'Error' ]);
            return;
        }
        try {
            $department = department::find($this->deletingId);
            if (!$department) {
                $this->dispatch('show-toast', [ 'message' => 'Department not found.', 'type' => 'error', 'title' => 'Not Found' ]);
                $this->cancelDelete();
                return;
            }
            $department->delete();
            $this->dispatch('show-toast', [ 'message' => 'Department deleted.', 'type' => 'success', 'title' => 'Deleted' ]);
            $this->cancelDelete();
            $this->resetPage();
        } catch (\Exception $e) {
            $this->dispatch('show-toast', [ 'message' => 'Error deleting department: '.$e->getMessage(), 'type' => 'error', 'title' => 'Error' ]);
        }
    }

    public function openAddModal(): void
    {
        $this->resetEditingState();
        $this->showAddDepartmentModal = true;
    }

    public function resetEditingState(): void
    {
        $this->reset(['department_name', 'description']);
        $this->status = 'active';
        $this->editingId = null;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $perPage = (int) request()->get('perPage', 10);
        if ($perPage <= 0) { $perPage = 10; }

        $query = department::orderByDesc('id');

        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('department_name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        $departments = $query->paginate($perPage);

        return view('livewire.department-management.department-management', compact('departments'));
    }
}
