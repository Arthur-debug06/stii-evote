<?php

namespace App\Livewire\SetSignatory;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\set_signatory;
use App\Models\User;
use App\Models\signatory_action;
use Illuminate\Support\Facades\Validator;

class SetSignatory extends Component
{
    use WithPagination;

    // Search functionality
    public $search = '';

    // Modal states
    public $showAddSignatoryModal = false;
    public $showEditSignatoryModal = false;
    public $showDeleteSignatoryModal = false;

    // Form data
    public $users_id = '';
    public $position = '';
    public $academic_suffix = '';
    public $signatory_action_id = '';
    public $status = 'active';

    // Selected signatory for actions
    public $selectedSignatory = null;

    // Validation rules
    protected $rules = [
        'users_id' => 'required|exists:users,id',
        'position' => 'required|string|max:255',
        'academic_suffix' => 'nullable|string|max:50',
        'signatory_action_id' => 'required|exists:signatory_action,id',
        'status' => 'required|in:active,inactive',
    ];

    protected $messages = [
        'users_id.required' => 'User is required.',
        'users_id.exists' => 'Selected user does not exist.',
        'position.required' => 'Position is required.',
        'position.max' => 'Position cannot exceed 255 characters.',
        'academic_suffix.max' => 'Academic suffix cannot exceed 50 characters.',
        'signatory_action_id.required' => 'Signatory action is required.',
        'signatory_action_id.exists' => 'Selected signatory action does not exist.',
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
        $this->showAddSignatoryModal = true;
    }

    public function editSignatory($signatoryId)
    {
        $signatory = set_signatory::with(['users', 'signatory_action'])->find($signatoryId);
        if ($signatory) {
            $this->selectedSignatory = $signatory;
            $this->users_id = $signatory->users_id;
            $this->position = $signatory->position;
            $this->academic_suffix = $signatory->academic_suffix;
            $this->signatory_action_id = $signatory->signatory_action_id;
            $this->status = $signatory->status;
            $this->showEditSignatoryModal = true;
        }
    }

    public function deleteSignatory($signatoryId)
    {
        $signatory = set_signatory::find($signatoryId);
        if ($signatory) {
            $this->selectedSignatory = $signatory;
            $this->showDeleteSignatoryModal = true;
        }
    }

    public function createSignatory()
    {
        $this->validate();

        try {
            set_signatory::create([
                'users_id' => $this->users_id,
                'position' => $this->position,
                'academic_suffix' => $this->academic_suffix,
                'signatory_action_id' => $this->signatory_action_id,
                'status' => $this->status,
            ]);

            $this->showAddSignatoryModal = false;
            $this->resetForm();

            $this->dispatch('show-toast', [
                'message' => 'Signatory created successfully.',
                'type' => 'success',
                'title' => 'Signatory Created!'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('show-toast', [
                'message' => 'Error creating signatory: ' . $e->getMessage(),
                'type' => 'error',
                'title' => 'Error'
            ]);
        }
    }

    public function updateSignatory()
    {
        $this->validate();

        try {
            $this->selectedSignatory->update([
                'users_id' => $this->users_id,
                'position' => $this->position,
                'academic_suffix' => $this->academic_suffix,
                'signatory_action_id' => $this->signatory_action_id,
                'status' => $this->status,
            ]);

            $this->showEditSignatoryModal = false;
            $this->selectedSignatory = null;
            $this->resetForm();

            $this->dispatch('show-toast', [
                'message' => 'Signatory updated successfully.',
                'type' => 'success',
                'title' => 'Signatory Updated!'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('show-toast', [
                'message' => 'Error updating signatory: ' . $e->getMessage(),
                'type' => 'error',
                'title' => 'Error'
            ]);
        }
    }

    public function deleteConfirmed()
    {
        if (!$this->selectedSignatory) {
            $this->dispatch('show-toast', [
                'message' => 'No signatory selected to delete.',
                'type' => 'error',
                'title' => 'Error'
            ]);
            return;
        }

        try {
            $this->selectedSignatory->delete();
            $this->showDeleteSignatoryModal = false;
            $this->selectedSignatory = null;

            $this->dispatch('show-toast', [
                'message' => 'Signatory deleted successfully.',
                'type' => 'success',
                'title' => 'Signatory Deleted!'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('show-toast', [
                'message' => 'Error deleting signatory: ' . $e->getMessage(),
                'type' => 'error',
                'title' => 'Error'
            ]);
        }
    }

    public function cancelDelete()
    {
        $this->showDeleteSignatoryModal = false;
        $this->selectedSignatory = null;
    }

    private function resetForm()
    {
        $this->users_id = '';
        $this->position = '';
        $this->academic_suffix = '';
        $this->signatory_action_id = '';
        $this->status = 'active';
        $this->resetErrorBag();
    }

    public function render()
    {
        $signatories = set_signatory::with(['users', 'signatory_action'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('position', 'like', '%' . $this->search . '%')
                      ->orWhere('academic_suffix', 'like', '%' . $this->search . '%')
                      ->orWhereHas('users', function ($userQuery) {
                          $userQuery->where('name', 'like', '%' . $this->search . '%')
                                   ->orWhere('email', 'like', '%' . $this->search . '%');
                      });
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $users = User::orderBy('name')->get();
        $signatoryActions = signatory_action::where('status', 'active')->orderBy('action_name')->get();

        return view('livewire.set-signatory.set-signatory', compact('signatories', 'users', 'signatoryActions'));
    }
}
