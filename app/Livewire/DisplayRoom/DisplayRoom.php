<?php

namespace App\Livewire\DisplayRoom;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\room_campaign;
use Illuminate\Support\Str;

class DisplayRoom extends Component
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
        $query = room_campaign::with(['students.applied_candidacies.partylist']);

        // Apply search filter
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('room_name', 'like', '%' . $this->search . '%')
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

        $campaigns = $query->orderBy('created_at', 'desc')->paginate($this->perPage);

        return view('livewire.display-room.display-room', [
            'campaigns' => $campaigns,
        ]);
    }
}
