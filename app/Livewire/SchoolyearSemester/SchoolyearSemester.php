<?php

namespace App\Livewire\SchoolyearSemester;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\school_year_and_semester;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class SchoolyearSemester extends Component
{
    use WithPagination;

    public string $school_year = '';
    public string $semester = '';
    public string $status = 'active';
    public string $search = '';
    public bool $showAddSchoolYearModal = false;
    public bool $showEditSchoolYearModal = false;
    public bool $showDeleteSchoolYearModal = false;
    public ?int $editingId = null;
    public ?int $deletingId = null;

    protected $rules = [
        'school_year' => 'required|string|min:4|max:255',
        'semester' => 'required|string|in:1st Semester,2nd Semester,Summer',
        'status' => 'required|string|in:active,inactive',
    ];

    protected $messages = [
        'school_year.required' => 'Please enter a school year (e.g., 2024-2025).',
        'semester.required' => 'Please select a semester.',
    ];

    public function createSchoolYear(): void
    {
        // Normalize inputs
        $this->school_year = trim($this->school_year);
        $this->semester = trim($this->semester);

        $validatedData = $this->validate([
            'school_year' => ['required','string','min:4','max:255'],
            'semester' => ['required','string', Rule::in(['1st Semester','2nd Semester','Summer'])],
            'status' => ['required','string', Rule::in(['active','inactive'])],
        ]);

        // Prevent duplicate pair of school_year + semester (case-insensitive)
        $existing = school_year_and_semester::whereRaw('LOWER(school_year) = ? AND LOWER(semester) = ?', [strtolower($this->school_year), strtolower($this->semester)])->first();
        if ($existing) {
            $this->dispatch('show-toast', [
                'message' => 'The selected School Year and Semester combination already exists.',
                'type' => 'error',
                'title' => 'Duplicate Entry'
            ]);
            return;
        }

        try {
            // If setting as active, deactivate all other school years
            if ($this->status === 'active') {
                school_year_and_semester::where('status', 'active')->update(['status' => 'inactive']);
            }

            school_year_and_semester::create([
                'school_year' => $validatedData['school_year'],
                'semester' => $validatedData['semester'],
                'status' => $validatedData['status'],
            ]);

            // Reset form fields
            $this->reset(['school_year', 'semester']);
            $this->status = 'active';

            // Close modal and show success message
            $this->showAddSchoolYearModal = false;
            $this->dispatch('close-modal', id: 'add-schoolyear-modal');
            
            // Show success toast
            $this->dispatch('show-toast', [
                'message' => 'School Year and Semester has been successfully created.',
                'type' => 'success',
                'title' => 'School Year Created!'
            ]);
            
            $this->resetPage();
            
        } catch (\Exception $e) {
            $this->dispatch('show-toast', [
                'message' => 'Error creating school year: ' . $e->getMessage(),
                'type' => 'error',
                'title' => 'Error!'
            ]);
        }
    }

    public function editSchoolYear(int $id): void
    {
        try {
            $schoolYear = school_year_and_semester::find($id);
            if (!$schoolYear) {
                $this->dispatch('show-toast', [
                    'message' => 'School Year not found.',
                    'type' => 'error',
                    'title' => 'Not Found'
                ]);
                return;
            }

            // Set editing state and populate form with existing data
            $this->editingId = $schoolYear->id;
            $this->school_year = (string) $schoolYear->school_year;
            $this->semester = (string) $schoolYear->semester;
            $this->status = (string) $schoolYear->status;

            // Open edit modal
            $this->showEditSchoolYearModal = true;
        } catch (\Exception $e) {
            $this->dispatch('show-toast', [
                'message' => 'Unable to load school year for editing.',
                'type' => 'error',
                'title' => 'Error'
            ]);
        }
    }

    public function updateSchoolYear(): void
    {
        if (!$this->editingId) {
            $this->dispatch('show-toast', [
                'message' => 'No school year selected to update.',
                'type' => 'error',
                'title' => 'Error'
            ]);
            return;
        }

        // Normalize inputs
        $this->school_year = trim($this->school_year);
        $this->semester = trim($this->semester);

        $validatedData = $this->validate([
            'school_year' => ['required','string','min:4','max:255'],
            'semester' => ['required','string', Rule::in(['1st Semester','2nd Semester','Summer'])],
            'status' => ['required','string', Rule::in(['active','inactive'])],
        ]);

        // Prevent duplicate pair when updating (exclude current)
        $existing = school_year_and_semester::whereRaw('LOWER(school_year) = ? AND LOWER(semester) = ?', [strtolower($this->school_year), strtolower($this->semester)])
                    ->where('id', '!=', $this->editingId)
                    ->first();
        if ($existing) {
            $this->dispatch('show-toast', [
                'message' => 'The selected School Year and Semester combination already exists.',
                'type' => 'error',
                'title' => 'Duplicate Entry'
            ]);
            return;
        }

        try {
            $schoolYear = school_year_and_semester::find($this->editingId);
            if (!$schoolYear) {
                $this->dispatch('show-toast', [
                    'message' => 'School Year not found.',
                    'type' => 'error',
                    'title' => 'Not Found'
                ]);
                return;
            }

            // If setting as active, deactivate all other school years
            if ($this->status === 'active') {
                school_year_and_semester::where('status', 'active')
                    ->where('id', '!=', $this->editingId)
                    ->update(['status' => 'inactive']);
            }

            $schoolYear->school_year = $validatedData['school_year'];
            $schoolYear->semester = $validatedData['semester'];
            $schoolYear->status = $validatedData['status'];
            $schoolYear->save();

            $this->showEditSchoolYearModal = false;
            $this->dispatch('close-modal', id: 'edit-schoolyear-modal');
            $this->dispatch('show-toast', [
                'message' => 'School Year updated successfully.',
                'type' => 'success',
                'title' => 'Updated'
            ]);

            $this->resetEditingState();
            $this->resetPage();
        } catch (\Exception $e) {
            $this->dispatch('show-toast', [
                'message' => 'Error updating school year: ' . $e->getMessage(),
                'type' => 'error',
                'title' => 'Error'
            ]);
        }
    }

    public function deleteSchoolYear(int $id): void
    {
        // Open confirmation modal
        $this->deletingId = $id;
        $this->showDeleteSchoolYearModal = true;
    }

    public function cancelDelete(): void
    {
        $this->showDeleteSchoolYearModal = false;
        $this->deletingId = null;
    }

    public function deleteConfirmed(): void
    {
        if (!$this->deletingId) {
            $this->dispatch('show-toast', [ 'message' => 'No item selected to delete.', 'type' => 'error', 'title' => 'Error' ]);
            return;
        }
        try {
            $schoolYear = school_year_and_semester::find($this->deletingId);
            if (!$schoolYear) {
                $this->dispatch('show-toast', [ 'message' => 'School Year not found.', 'type' => 'error', 'title' => 'Not Found' ]);
                $this->cancelDelete();
                return;
            }
            $schoolYear->delete();
            $this->dispatch('show-toast', [ 'message' => 'School Year deleted.', 'type' => 'success', 'title' => 'Deleted' ]);
            $this->cancelDelete();
            $this->resetPage();
        } catch (\Exception $e) {
            $this->dispatch('show-toast', [ 'message' => 'Error deleting school year: '.$e->getMessage(), 'type' => 'error', 'title' => 'Error' ]);
        }
    }

    public function openAddModal(): void
    {
        $this->resetEditingState();
        $this->showAddSchoolYearModal = true;
    }

    public function resetEditingState(): void
    {
        $this->reset(['school_year', 'semester']);
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

        $query = school_year_and_semester::orderByDesc('id');

        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('school_year', 'like', '%' . $this->search . '%')
                  ->orWhere('semester', 'like', '%' . $this->search . '%');
            });
        }

        $schoolYears = $query->paginate($perPage);

        return view('livewire.schoolyear-semester.schoolyear-semester', compact('schoolYears'));
    }
}
