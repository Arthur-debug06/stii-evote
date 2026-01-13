<?php

namespace App\Livewire\Feedback;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\tbl_feedback;
use Illuminate\Support\Facades\Auth;

class Feedback extends Component
{
    use WithPagination;

    public string $comments = '';
    public string $rating = '';
    public string $status = 'active';
    public string $search = '';
    public bool $showAddFeedbackModal = false;
    public bool $showEditFeedbackModal = false;
    public bool $showDeleteFeedbackModal = false;
    public ?int $editingId = null;
    public ?int $deletingId = null;

    protected $rules = [
        'comments' => 'required|string|min:3',
        'rating' => 'required|string|in:1,2,3,4,5',
        'status' => 'required|string|in:active,inactive',
    ];

    public function createFeedback(): void
    {
        $this->validate();

        // Get current logged-in user ID
        $userId = Auth::id();
        
        // Make sure user is authenticated
        if (!$userId) {
            $this->dispatch('show-toast', [
                'message' => 'You must be logged in to submit feedback.',
                'type' => 'error',
                'title' => 'Authentication Required!'
            ]);
            return;
        }

        try {
            tbl_feedback::create([
                'users_id' => $userId,
                'comments' => $this->comments,
                'rating' => (int) $this->rating,
                'status' => $this->status,
            ]);

            // Reset form fields
            $this->reset(['comments', 'rating']);
            $this->rating = '';
            $this->status = 'active';

            // Close modal and show success message
            $this->showAddFeedbackModal = false;
            $this->dispatch('close-modal', id: 'add-feedback-modal');
            
            // Show success toast
            $this->dispatch('show-toast', [
                'message' => 'Your feedback has been successfully submitted.',
                'type' => 'success',
                'title' => 'Feedback Saved!'
            ]);
            
            $this->resetPage();
            
        } catch (\Exception $e) {
            $this->dispatch('show-toast', [
                'message' => 'Error saving feedback: ' . $e->getMessage(),
                'type' => 'error',
                'title' => 'Error!'
            ]);
        }
    }

    public function editFeedback(int $id): void
    {
        try {
            $feedback = tbl_feedback::find($id);
            if (!$feedback) {
                $this->dispatch('show-toast', [
                    'message' => 'Feedback not found.',
                    'type' => 'error',
                    'title' => 'Not Found'
                ]);
                return;
            }

            // Set editing state and populate form with existing data
            $this->editingId = $feedback->id;
            $this->comments = (string) $feedback->comments;
            $this->rating = (string) $feedback->rating;
            $this->status = (string) $feedback->status;

            // Open edit modal
            $this->showEditFeedbackModal = true;
        } catch (\Exception $e) {
            $this->dispatch('show-toast', [
                'message' => 'Unable to load feedback for editing.',
                'type' => 'error',
                'title' => 'Error'
            ]);
        }
    }

    public function updateFeedback(): void
    {
        if (!$this->editingId) {
            $this->dispatch('show-toast', [
                'message' => 'No feedback selected to update.',
                'type' => 'error',
                'title' => 'Error'
            ]);
            return;
        }

        $this->validate();

        try {
            $feedback = tbl_feedback::find($this->editingId);
            if (!$feedback) {
                $this->dispatch('show-toast', [
                    'message' => 'Feedback not found.',
                    'type' => 'error',
                    'title' => 'Not Found'
                ]);
                return;
            }

            $feedback->comments = $this->comments;
            $feedback->rating = (int) $this->rating;
            $feedback->status = $this->status;
            $feedback->save();

            $this->showEditFeedbackModal = false;
            $this->dispatch('close-modal', id: 'edit-feedback-modal');
            $this->dispatch('show-toast', [
                'message' => 'Feedback updated successfully.',
                'type' => 'success',
                'title' => 'Updated'
            ]);

            $this->resetEditingState();
            $this->resetPage();
        } catch (\Exception $e) {
            $this->dispatch('show-toast', [
                'message' => 'Error updating feedback: ' . $e->getMessage(),
                'type' => 'error',
                'title' => 'Error'
            ]);
        }
    }

    public function deleteFeedback(int $id): void
    {
        // Open confirmation modal
        $this->deletingId = $id;
        $this->showDeleteFeedbackModal = true;
    }

    public function cancelDelete(): void
    {
        $this->showDeleteFeedbackModal = false;
        $this->deletingId = null;
    }

    public function deleteConfirmed(): void
    {
        if (!$this->deletingId) {
            $this->dispatch('show-toast', [ 'message' => 'No item selected to delete.', 'type' => 'error', 'title' => 'Error' ]);
            return;
        }
        try {
            $feedback = tbl_feedback::find($this->deletingId);
            if (!$feedback) {
                $this->dispatch('show-toast', [ 'message' => 'Feedback not found.', 'type' => 'error', 'title' => 'Not Found' ]);
                $this->cancelDelete();
                return;
            }
            $feedback->delete();
            $this->dispatch('show-toast', [ 'message' => 'Feedback deleted.', 'type' => 'success', 'title' => 'Deleted' ]);
            $this->cancelDelete();
            $this->resetPage();
        } catch (\Exception $e) {
            $this->dispatch('show-toast', [ 'message' => 'Error deleting feedback: '.$e->getMessage(), 'type' => 'error', 'title' => 'Error' ]);
        }
    }

    public function openAddModal(): void
    {
        $this->resetEditingState();
        $this->showAddFeedbackModal = true;
    }

    public function resetEditingState(): void
    {
        $this->reset(['comments', 'rating']);
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

        $query = tbl_feedback::with('user')->orderByDesc('id');

        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('comments', 'like', '%' . $this->search . '%')
                  ->orWhereHas('user', function($userQuery) {
                      $userQuery->where('name', 'like', '%' . $this->search . '%')
                               ->orWhere('email', 'like', '%' . $this->search . '%');
                  });
            });
        }

        $feedback = $query->paginate($perPage);

        return view('livewire.feedback.feedback', compact('feedback'));
    }
}
