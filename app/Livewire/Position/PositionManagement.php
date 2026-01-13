<?php

namespace App\Livewire\Position;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\position;
use Illuminate\Support\Facades\Validator;

class PositionManagement extends Component
{
    use WithPagination;

    // Search functionality
    public $search = '';

    // Modal states
    public $showAddPositionModal = false;
    public $showEditPositionModal = false;
    public $showDeletePositionModal = false;

    // Form data
    public $position_name = '';
    public $allowed_number_to_vote = '';
    public $status = 'active';

    // Selected position for actions
    public $selectedPosition = null;

    // Validation rules
    protected $rules = [
        'position_name' => 'required|string|max:255|unique:position,position_name',
        'allowed_number_to_vote' => 'required|integer|min:1|max:10',
        'status' => 'required|in:active,inactive',
    ];

    protected $messages = [
        'position_name.required' => 'Position name is required.',
        'position_name.unique' => 'This position name already exists.',
        'allowed_number_to_vote.required' => 'Allowed number to vote is required.',
        'allowed_number_to_vote.integer' => 'Allowed number to vote must be a number.',
        'allowed_number_to_vote.min' => 'Allowed number to vote must be at least 1.',
        'allowed_number_to_vote.max' => 'Allowed number to vote cannot exceed 10.',
        'status.required' => 'Status is required.',
        'status.in' => 'Status must be either active or inactive.',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openAddModal()
    {
        $this->resetForm();
        $this->showAddPositionModal = true;
    }

    public function editPosition($positionId)
    {
        $position = position::find($positionId);
        if ($position) {
            $this->selectedPosition = $position;
            $this->position_name = $position->position_name;
            $this->allowed_number_to_vote = $position->allowed_number_to_vote;
            $this->status = $position->status;
            $this->showEditPositionModal = true;
        }
    }

    public function deletePosition($positionId)
    {
        $position = position::find($positionId);
        if ($position) {
            $this->selectedPosition = $position;
            $this->showDeletePositionModal = true;
        }
    }

    public function createPosition()
    {
        $this->validate();

        try {
            // Check if position already exists (case-insensitive)
            $existingPosition = position::whereRaw('LOWER(position_name) = ?', [strtolower($this->position_name)])->first();
            
            if ($existingPosition) {
                $this->dispatch('show-toast', [
                    'message' => 'Position "' . $this->position_name . '" already exists. Please choose a different name.',
                    'type' => 'error',
                    'title' => 'Position Already Exists!'
                ]);
                return;
            }

            position::create([
                'position_name' => $this->position_name,
                'allowed_number_to_vote' => $this->allowed_number_to_vote,
                'status' => $this->status,
            ]);

            $this->showAddPositionModal = false;
            $this->resetForm();

            $this->dispatch('show-toast', [
                'message' => 'Position created successfully.',
                'type' => 'success',
                'title' => 'Position Created!'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('show-toast', [
                'message' => 'Error creating position: ' . $e->getMessage(),
                'type' => 'error',
                'title' => 'Error'
            ]);
        }
    }

    public function updatePosition()
    {
        $this->rules['position_name'] = 'required|string|max:255|unique:position,position_name,' . $this->selectedPosition->id;
        $this->validate();

        try {
            // Check if position already exists (case-insensitive) excluding current position
            $existingPosition = position::whereRaw('LOWER(position_name) = ?', [strtolower($this->position_name)])
                ->where('id', '!=', $this->selectedPosition->id)
                ->first();
            
            if ($existingPosition) {
                $this->dispatch('show-toast', [
                    'message' => 'Position "' . $this->position_name . '" already exists. Please choose a different name.',
                    'type' => 'error',
                    'title' => 'Position Already Exists!'
                ]);
                return;
            }

            $this->selectedPosition->update([
                'position_name' => $this->position_name,
                'allowed_number_to_vote' => $this->allowed_number_to_vote,
                'status' => $this->status,
            ]);

            $this->showEditPositionModal = false;
            $this->selectedPosition = null;
            $this->resetForm();

            $this->dispatch('show-toast', [
                'message' => 'Position updated successfully.',
                'type' => 'success',
                'title' => 'Position Updated!'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('show-toast', [
                'message' => 'Error updating position: ' . $e->getMessage(),
                'type' => 'error',
                'title' => 'Error'
            ]);
        }
    }

    public function deleteConfirmed()
    {
        if (!$this->selectedPosition) {
            $this->dispatch('show-toast', [
                'message' => 'No position selected to delete.',
                'type' => 'error',
                'title' => 'Error'
            ]);
            return;
        }

        try {
            $this->selectedPosition->delete();
            $this->showDeletePositionModal = false;
            $this->selectedPosition = null;

            $this->dispatch('show-toast', [
                'message' => 'Position deleted successfully.',
                'type' => 'success',
                'title' => 'Position Deleted!'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('show-toast', [
                'message' => 'Error deleting position: ' . $e->getMessage(),
                'type' => 'error',
                'title' => 'Error'
            ]);
        }
    }

    public function cancelDelete()
    {
        $this->showDeletePositionModal = false;
        $this->selectedPosition = null;
    }

    private function resetForm()
    {
        $this->position_name = '';
        $this->allowed_number_to_vote = '';
        $this->status = 'active';
        $this->resetErrorBag();
    }

    public function render()
    {
        $positions = position::when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('position_name', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.position.position-management', compact('positions'));
    }
}
