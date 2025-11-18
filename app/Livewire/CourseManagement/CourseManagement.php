<?php

namespace App\Livewire\CourseManagement;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\course;
use App\Models\department;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CourseManagement extends Component
{
    use WithPagination;

    public string $course_name = '';
    public string $description = '';
    public string $status = 'active';
    public ?int $department_id = null;
    public string $search = '';
    public bool $showAddCourseModal = false;
    public bool $showEditCourseModal = false;
    public bool $showDeleteCourseModal = false;
    public ?int $editingId = null;
    public ?int $deletingId = null;

    protected $rules = [
        'course_name' => 'required|string|min:2|max:255',
        'description' => 'nullable|string|max:1000',
        'status' => 'required|string|in:active,inactive',
        'department_id' => 'required|integer|exists:department,id',
    ];

    protected $messages = [
        'course_name.unique' => 'The course name has already been taken. Please choose a different name.',
        'course_name.required' => 'Please enter a course name.',
    ];

    public function createCourse(): void
    {
        // Normalize input
        $this->course_name = trim($this->course_name);

        $validatedData = $this->validate([
            'course_name' => [
                'required', 'string', 'min:2', 'max:255',
                Rule::unique('course', 'course_name')->whereNull('deleted_at'),
            ],
            'description' => 'nullable|string|max:1000',
            'status' => 'required|string|in:active,inactive',
            'department_id' => 'required|integer|exists:department,id',
        ]);

        try {
            course::create([
                'course_name' => $validatedData['course_name'],
                'description' => $validatedData['description'] ?? null,
                'status' => $validatedData['status'],
                'department_id' => $validatedData['department_id'],
            ]);

            // Reset form fields
            $this->reset(['course_name', 'description', 'department_id']);
            $this->status = 'active';

            // Close modal and show success message
            $this->showAddCourseModal = false;
            $this->dispatch('close-modal', id: 'add-course-modal');
            
            // Show success toast
            $this->dispatch('show-toast', [
                'message' => 'Course has been successfully created.',
                'type' => 'success',
                'title' => 'Course Created!'
            ]);
            
            $this->resetPage();
            
        } catch (\Exception $e) {
            $this->dispatch('show-toast', [
                'message' => 'Error creating course: ' . $e->getMessage(),
                'type' => 'error',
                'title' => 'Error!'
            ]);
        }
    }

    public function editCourse(int $id): void
    {
        try {
            $course = course::find($id);
            if (!$course) {
                $this->dispatch('show-toast', [
                    'message' => 'Course not found.',
                    'type' => 'error',
                    'title' => 'Not Found'
                ]);
                return;
            }

            // Set editing state and populate form with existing data
            $this->editingId = $course->id;
            $this->course_name = (string) $course->course_name;
            $this->description = (string) $course->description;
            $this->status = (string) $course->status;
            $this->department_id = $course->department_id;

            // Open edit modal
            $this->showEditCourseModal = true;
        } catch (\Exception $e) {
            $this->dispatch('show-toast', [
                'message' => 'Unable to load course for editing.',
                'type' => 'error',
                'title' => 'Error'
            ]);
        }
    }

    public function updateCourse(): void
    {
        if (!$this->editingId) {
            $this->dispatch('show-toast', [
                'message' => 'No course selected to update.',
                'type' => 'error',
                'title' => 'Error'
            ]);
            return;
        }

        // Normalize input
        $this->course_name = trim($this->course_name);

        $validatedData = $this->validate([
            'course_name' => [
                'required', 'string', 'min:2', 'max:255',
                Rule::unique('course', 'course_name')->ignore($this->editingId, 'id')->whereNull('deleted_at'),
            ],
            'description' => 'nullable|string|max:1000',
            'status' => 'required|string|in:active,inactive',
            'department_id' => 'required|integer|exists:department,id',
        ]);

        try {
            $course = course::find($this->editingId);
            if (!$course) {
                $this->dispatch('show-toast', [
                    'message' => 'Course not found.',
                    'type' => 'error',
                    'title' => 'Not Found'
                ]);
                return;
            }

            $course->course_name = $validatedData['course_name'];
            $course->description = $validatedData['description'] ?? null;
            $course->status = $validatedData['status'];
            $course->department_id = $validatedData['department_id'];
            $course->save();

            $this->showEditCourseModal = false;
            $this->dispatch('close-modal', id: 'edit-course-modal');
            $this->dispatch('show-toast', [
                'message' => 'Course updated successfully.',
                'type' => 'success',
                'title' => 'Updated'
            ]);

            $this->resetEditingState();
            $this->resetPage();
        } catch (\Exception $e) {
            $this->dispatch('show-toast', [
                'message' => 'Error updating course: ' . $e->getMessage(),
                'type' => 'error',
                'title' => 'Error'
            ]);
        }
    }

    public function deleteCourse(int $id): void
    {
        // Open confirmation modal
        $this->deletingId = $id;
        $this->showDeleteCourseModal = true;
    }

    public function cancelDelete(): void
    {
        $this->showDeleteCourseModal = false;
        $this->deletingId = null;
    }

    public function deleteConfirmed(): void
    {
        if (!$this->deletingId) {
            $this->dispatch('show-toast', [ 'message' => 'No item selected to delete.', 'type' => 'error', 'title' => 'Error' ]);
            return;
        }
        try {
            $course = course::find($this->deletingId);
            if (!$course) {
                $this->dispatch('show-toast', [ 'message' => 'Course not found.', 'type' => 'error', 'title' => 'Not Found' ]);
                $this->cancelDelete();
                return;
            }
            $course->delete();
            $this->dispatch('show-toast', [ 'message' => 'Course deleted.', 'type' => 'success', 'title' => 'Deleted' ]);
            $this->cancelDelete();
            $this->resetPage();
        } catch (\Exception $e) {
            $this->dispatch('show-toast', [ 'message' => 'Error deleting course: '.$e->getMessage(), 'type' => 'error', 'title' => 'Error' ]);
        }
    }

    public function openAddModal(): void
    {
        $this->resetEditingState();
        $this->showAddCourseModal = true;
    }

    public function resetEditingState(): void
    {
        $this->reset(['course_name', 'description', 'department_id']);
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

        $query = course::orderByDesc('id');

        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('course_name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        $courses = $query->with('department')->paginate($perPage);
        $departments = department::active()->orderBy('department_name')->get();

        return view('livewire.course-management.course-management', compact('courses', 'departments'));
    }
}
