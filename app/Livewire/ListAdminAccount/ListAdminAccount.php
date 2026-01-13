<?php

namespace App\Livewire\ListAdminAccount;

use Livewire\Component;
use App\Models\User;

class ListAdminAccount extends Component
{
    public $search = '';
    public $admins = [];

    public function mount()
    {
        $this->loadAdmins();
    }

    public function loadAdmins()
    {
        $query = User::query();

        // Apply search filter if provided
        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
                  ->orWhere('role', 'like', '%' . $this->search . '%');
            });
        }

        $this->admins = $query->orderBy('name')->get();
    }

    public function updatedSearch()
    {
        $this->loadAdmins();
    }

    public function render()
    {
        return view('livewire.list-admin-account.list-admin-account');
    }
}
