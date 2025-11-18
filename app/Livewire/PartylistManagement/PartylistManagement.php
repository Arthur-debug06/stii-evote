<?php

namespace App\Livewire\PartylistManagement;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\partylist;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PartylistManagement extends Component
{
    use WithPagination, WithFileUploads;

    // Form fields
    public $partylist_name = '';
    public $description = '';
    public $partylist_image;
    public $status = 'active';
    
    // Modal states
    public $showAddModal = false;
    public $showEditModal = false;
    public $showDeleteModal = false;
    public $showForceDeleteModal = false;
    
    // Editing state
    public $editingId = null;
    public $deletingId = null;
    public $forceDeletingId = null;
    
    // Search
    public $search = '';
    public $showTrashed = false;

    protected $rules = [
        'partylist_name' => 'required|string|max:255',
        'description' => 'nullable|string|max:1000',
        'partylist_image' => 'nullable|image|max:2048',
        'status' => 'required|in:active,inactive',
    ];

    public function render()
    {
        $query = partylist::query();

        if ($this->showTrashed) {
            $query = $query->onlyTrashed();
        }

        $partylists = $query->where(function($query) {
                    if (!empty($this->search)) {
                        $query->where('partylist_name', 'like', '%' . $this->search . '%')
                              ->orWhere('description', 'like', '%' . $this->search . '%');
                    }
                })
                ->orderBy('created_at', 'desc')
                ->paginate(10);

        return view('livewire.partylist-management.partylist-management', [
            'partylists' => $partylists
        ]);
    }

    public function openAddModal()
    {
        $this->resetFormFields();
        $this->showAddModal = true;
    }

    public function openEditModal($id)
    {
        $partylist = partylist::find($id);
        if ($partylist) {
            $this->editingId = $id;
            $this->partylist_name = $partylist->partylist_name;
            $this->description = $partylist->description;
            $this->status = $partylist->status;
            $this->partylist_image = null; // Reset file upload
            $this->showEditModal = true;
        }
    }

    public function openDeleteModal($id)
    {
        $this->deletingId = $id;
        $this->showDeleteModal = true;
    }

    public function openForceDeleteModal($id)
    {
        $this->forceDeletingId = $id;
        $this->showForceDeleteModal = true;
    }

    public function toggleTrashView()
    {
        $this->showTrashed = ! $this->showTrashed;
        $this->resetPage();
    }

    public function createPartylist()
    {
        $this->validate();

        try {
            $data = [
                'partylist_name' => $this->partylist_name,
                'description' => $this->description,
                'status' => $this->status,
            ];

            // Handle image upload
            if ($this->partylist_image) {
                $filename = 'partylist_' . time() . '_' . Str::random(10) . '.' . $this->partylist_image->getClientOriginalExtension();
                // Store in public disk under partylist_images directory
                $path = $this->partylist_image->storeAs('partylist_images', $filename, 'public');
                $data['partylist_image'] = $path; // This will be 'partylist_images/filename.png'
            }

            partylist::create($data);

            $this->resetFormFields();
            $this->showAddModal = false;

            $this->dispatch('show-toast', [
                'message' => 'Partylist created successfully!',
                'type' => 'success',
                'title' => 'Success'
            ]);

        } catch (\Exception $e) {
            $this->dispatch('show-toast', [
                'message' => 'Failed to create partylist: ' . $e->getMessage(),
                'type' => 'error',
                'title' => 'Error'
            ]);
        }
    }

    public function updatePartylist()
    {
        $this->validate();

        try {
            $partylist = partylist::find($this->editingId);
            if (!$partylist) {
                $this->dispatch('show-toast', [
                    'message' => 'Partylist not found.',
                    'type' => 'error',
                    'title' => 'Error'
                ]);
                return;
            }

            $data = [
                'partylist_name' => $this->partylist_name,
                'description' => $this->description,
                'status' => $this->status,
            ];

            // Handle image upload
            if ($this->partylist_image) {
                // Delete old image if exists
                if ($partylist->partylist_image) {
                    Storage::disk('public')->delete($partylist->partylist_image);
                }

                $filename = 'partylist_' . time() . '_' . Str::random(10) . '.' . $this->partylist_image->getClientOriginalExtension();
                // Store in public disk under partylist_images directory
                $path = $this->partylist_image->storeAs('partylist_images', $filename, 'public');
                $data['partylist_image'] = $path; // This will be 'partylist_images/filename.png'
            }

            $partylist->update($data);

            $this->resetFormFields();
            $this->showEditModal = false;

            $this->dispatch('show-toast', [
                'message' => 'Partylist updated successfully!',
                'type' => 'success',
                'title' => 'Success'
            ]);

        } catch (\Exception $e) {
            $this->dispatch('show-toast', [
                'message' => 'Failed to update partylist: ' . $e->getMessage(),
                'type' => 'error',
                'title' => 'Error'
            ]);
        }
    }

    public function deletePartylist()
    {
        try {
            // Soft delete (move to trash)
            $partylist = partylist::find($this->deletingId);
            if (!$partylist) {
                $this->dispatch('show-toast', [
                    'message' => 'Partylist not found.',
                    'type' => 'error',
                    'title' => 'Error'
                ]);
                return;
            }

            $partylist->delete();

            $this->showDeleteModal = false;

            $this->dispatch('show-toast', [
                'message' => 'Partylist moved to trash!',
                'type' => 'success',
                'title' => 'Success'
            ]);

        } catch (\Exception $e) {
            $this->dispatch('show-toast', [
                'message' => 'Failed to move partylist to trash: ' . $e->getMessage(),
                'type' => 'error',
                'title' => 'Error'
            ]);
        }
    }

    public function restorePartylist($id)
    {
        try {
            $partylist = partylist::onlyTrashed()->find($id);
            if (!$partylist) {
                $this->dispatch('show-toast', [
                    'message' => 'Partylist not found in trash.',
                    'type' => 'error',
                    'title' => 'Error'
                ]);
                return;
            }

            $partylist->restore();

            $this->dispatch('show-toast', [
                'message' => 'Partylist restored successfully!',
                'type' => 'success',
                'title' => 'Success'
            ]);

        } catch (\Exception $e) {
            $this->dispatch('show-toast', [
                'message' => 'Failed to restore partylist: ' . $e->getMessage(),
                'type' => 'error',
                'title' => 'Error'
            ]);
        }
    }

    public function forceDeletePartylist()
    {
        try {
            $partylist = partylist::onlyTrashed()->find($this->forceDeletingId);
            if (!$partylist) {
                $this->dispatch('show-toast', [
                    'message' => 'Partylist not found in trash.',
                    'type' => 'error',
                    'title' => 'Error'
                ]);
                return;
            }

            // Delete image if exists on permanent deletion
            if ($partylist->partylist_image) {
                Storage::disk('public')->delete($partylist->partylist_image);
            }

            $partylist->forceDelete();

            $this->showForceDeleteModal = false;

            $this->dispatch('show-toast', [
                'message' => 'Partylist deleted permanently!',
                'type' => 'success',
                'title' => 'Success'
            ]);

        } catch (\Exception $e) {
            $this->dispatch('show-toast', [
                'message' => 'Failed to delete permanently: ' . $e->getMessage(),
                'type' => 'error',
                'title' => 'Error'
            ]);
        }
    }

    private function resetFormFields()
    {
        $this->partylist_name = '';
        $this->description = '';
        $this->partylist_image = null;
        $this->status = 'active';
        $this->editingId = null;
        $this->deletingId = null;
        $this->forceDeletingId = null;
    }
}
