<?php

namespace App\Livewire\DisplayMeetingAbanse;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\meeting_de_abanse;
use Illuminate\Support\Str;

class DisplayMeetingAbanse extends Component
{
    use WithPagination;

    // Search and filter properties
    public $search = '';
    public $filterStatus = '';
    public $perPage = 10;

    protected $queryString = [
        'search' => ['except' => ''],
        'filterStatus' => ['except' => ''],
    ];

    public function mount()
    {
        $this->perPage = 10;
    }

    public function updatedSearch()
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
        $this->filterStatus = '';
        $this->resetPage();
    }

    public function render()
    {
        $query = meeting_de_abanse::with('students');

        // Apply search filter
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('meeting_de_abanse_name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%')
                  ->orWhereHas('students', function ($studentQuery) {
                      $studentQuery->where('first_name', 'like', '%' . $this->search . '%')
                                   ->orWhere('last_name', 'like', '%' . $this->search . '%');
                  });
            });
        }

        // Apply status filter
        if (!empty($this->filterStatus)) {
            $query->where('status', $this->filterStatus);
        }

        $meetings = $query->orderBy('created_at', 'desc')->paginate($this->perPage);

        return view('livewire.display-meeting-abanse.display-meeting-abanse', [
            'meetings' => $meetings,
        ]);
    }
}