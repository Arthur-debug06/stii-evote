<?php

namespace App\Livewire\SystemSettings;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\system_settings;

class SystemSettings extends Component
{
    use WithPagination, WithFileUploads;

    public string $search = '';
    public bool $showAddSettingModal = false;
    public bool $showEditSettingModal = false;
    public bool $showDeleteSettingModal = false;
    public ?int $editingId = null;
    public ?int $deletingId = null;

    // Form fields
    public string $key = '';
    public ?string $value = '';
    public string $type = 'text';
    public ?int $module_id = null;
    public ?string $description = '';
    public ?string $status = 'active';
    public $uploadedFile = null;

    protected $rules = [
        'key' => 'required|string|max:255|unique:system_settings,key',
        'value' => 'nullable|string',
        'type' => 'required|string|in:text,number,boolean,json,email,url,image',
        'module_id' => 'nullable|integer',
        'description' => 'nullable|string|max:500',
        'status' => 'required|string|in:active,inactive',
    ];

    public function mount()
    {
        $this->loadSettings();
    }

    public function loadSettings()
    {
        // This method can be used to refresh data if needed
    }

    public function openAddModal(): void
    {
        $this->resetForm();
        $this->showAddSettingModal = true;
    }

    public function updatedKey(): void
    {
        // Auto-populate value when key changes
        $this->value = $this->getDefaultValueForKey($this->key);
    }

    private function getDefaultValueForKey(string $key): ?string
    {
        // Auto-populate default values based on the key
        switch ($key) {
            case 'sidebar_logo':
                return 'dist/images/logo.svg';
            
            // Add more cases for other settings
            // case 'header_logo':
            //     return 'dist/images/header-logo.svg';
            // case 'favicon':
            //     return 'dist/images/favicon.ico';
            
            default:
                return '';
        }
    }

    public function createSetting(): void
    {
        $this->validate();

        try {
            $value = $this->value;
            
            // Handle file upload for image type
            if ($this->type === 'image' && $this->uploadedFile) {
                $value = $this->handleFileUpload($this->uploadedFile);
            }

            system_settings::create([
                'key' => $this->key,
                'value' => $value,
                'type' => $this->type,
                'module_id' => $this->module_id,
                'description' => $this->description,
                'status' => $this->status,
            ]);

            $this->dispatch('show-toast', [
                'type' => 'success',
                'title' => 'System Setting Created!',
                'message' => 'The system setting has been created successfully.',
            ]);

            $this->resetForm();
            $this->showAddSettingModal = false;
            $this->dispatch('close-modal', id: 'add-setting-modal');
            $this->resetPage();
        } catch (\Exception $e) {
            $this->dispatch('show-toast', [
                'message' => 'Error creating system setting: ' . $e->getMessage(),
                'type' => 'error',
                'title' => 'Error!'
            ]);
        }
    }

    public function editSetting(int $id): void
    {
        try {
            $setting = system_settings::find($id);
            if (!$setting) {
                $this->dispatch('show-toast', [
                    'message' => 'System setting not found.',
                    'type' => 'error',
                    'title' => 'Not Found'
                ]);
                return;
            }

            $this->editingId = $setting->id;
            $this->key = $setting->key;
            $this->value = $this->getAutoValue($setting);
            $this->type = $setting->type;
            $this->module_id = $setting->module_id;
            $this->description = $setting->description;
            $this->status = $setting->status;

            $this->showEditSettingModal = true;
        } catch (\Exception $e) {
            $this->dispatch('show-toast', [
                'message' => 'Unable to load system setting for editing.',
                'type' => 'error',
                'title' => 'Error'
            ]);
        }
    }

    private function getAutoValue($setting): ?string
    {
        // Auto-populate value based on the setting key and type
        switch ($setting->key) {
            case 'sidebar_logo':
                if ($setting->type === 'image') {
                    return $setting->value ?: 'dist/images/logo.svg';
                }
                break;
            
            case 'header_logo':
                if ($setting->type === 'image') {
                    return $setting->value ?: 'dist/images/header-logo.svg';
                }
                break;
            
            case 'favicon':
                if ($setting->type === 'image') {
                    return $setting->value ?: 'dist/images/favicon.ico';
                }
                break;
            
            case 'site_name':
                if ($setting->type === 'text') {
                    return $setting->value ?: 'Voting System';
                }
                break;
            
            case 'site_email':
                if ($setting->type === 'email') {
                    return $setting->value ?: 'admin@votingsystem.com';
                }
                break;
            
            case 'site_url':
                if ($setting->type === 'url') {
                    return $setting->value ?: 'https://votingsystem.com';
                }
                break;
            
            case 'maintenance_mode':
                if ($setting->type === 'boolean') {
                    return $setting->value ?: 'false';
                }
                break;
            
            case 'max_upload_size':
                if ($setting->type === 'number') {
                    return $setting->value ?: '2048';
                }
                break;
            
            default:
                return $setting->value;
        }
        
        return $setting->value;
    }

    public function updateSetting(): void
    {
        if (!$this->editingId) {
            $this->dispatch('show-toast', [
                'message' => 'No system setting selected to update.',
                'type' => 'error',
                'title' => 'Error'
            ]);
            return;
        }

        // Update validation rules to ignore current record for unique key
        $this->rules['key'] = 'required|string|max:255|unique:system_settings,key,' . $this->editingId;
        $this->validate();

        try {
            $setting = system_settings::find($this->editingId);
            if (!$setting) {
                $this->dispatch('show-toast', [
                    'message' => 'System setting not found.',
                    'type' => 'error',
                    'title' => 'Not Found'
                ]);
                return;
            }

            $value = $this->value;
            
            // Handle file upload for image type
            if ($this->type === 'image' && $this->uploadedFile) {
                $value = $this->handleFileUpload($this->uploadedFile);
            }

            $setting->update([
                'key' => $this->key,
                'value' => $value,
                'type' => $this->type,
                'module_id' => $this->module_id,
                'description' => $this->description,
                'status' => $this->status,
            ]);

            $this->showEditSettingModal = false;
            $this->dispatch('close-modal', id: 'edit-setting-modal');
            $this->dispatch('show-toast', [
                'message' => 'System setting updated successfully.',
                'type' => 'success',
                'title' => 'Updated'
            ]);

            $this->resetForm();
            $this->resetPage();
        } catch (\Exception $e) {
            $this->dispatch('show-toast', [
                'message' => 'Error updating system setting: ' . $e->getMessage(),
                'type' => 'error',
                'title' => 'Error'
            ]);
        }
    }

    public function deleteSetting(int $id): void
    {
        try {
            $setting = system_settings::find($id);
            if (!$setting) {
                $this->dispatch('show-toast', [
                    'message' => 'System setting not found.',
                    'type' => 'error',
                    'title' => 'Not Found'
                ]);
                return;
            }

            $this->deletingId = $id;
            $this->showDeleteSettingModal = true;
        } catch (\Exception $e) {
            $this->dispatch('show-toast', [
                'message' => 'Unable to process deletion request.',
                'type' => 'error',
                'title' => 'Error'
            ]);
        }
    }

    public function cancelDelete(): void
    {
        $this->showDeleteSettingModal = false;
        $this->deletingId = null;
    }

    public function deleteConfirmed(): void
    {
        if (!$this->deletingId) {
            $this->dispatch('show-toast', [
                'message' => 'No item selected to delete.',
                'type' => 'error',
                'title' => 'Error'
            ]);
            return;
        }

        try {
            $setting = system_settings::find($this->deletingId);
            if (!$setting) {
                $this->dispatch('show-toast', [
                    'message' => 'System setting not found.',
                    'type' => 'error',
                    'title' => 'Not Found'
                ]);
                $this->cancelDelete();
                return;
            }

            $setting->delete();
            $this->dispatch('show-toast', [
                'message' => 'System setting deleted successfully.',
                'type' => 'success',
                'title' => 'Deleted'
            ]);
            $this->cancelDelete();
            $this->resetPage();
        } catch (\Exception $e) {
            $this->dispatch('show-toast', [
                'message' => 'Error deleting system setting: ' . $e->getMessage(),
                'type' => 'error',
                'title' => 'Error'
            ]);
        }
    }

    public function resetForm(): void
    {
        $this->key = '';
        $this->value = '';
        $this->type = 'text';
        $this->module_id = null;
        $this->description = '';
        $this->status = 'active';
        $this->editingId = null;
        $this->uploadedFile = null;
        $this->resetErrorBag();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    private function handleFileUpload($file)
    {
        // Create system_settings directory if it doesn't exist
        $directory = 'system_settings';
        if (!\Storage::disk('public')->exists($directory)) {
            \Storage::disk('public')->makeDirectory($directory);
        }

        // Generate unique filename
        $filename = time() . '_' . $file->getClientOriginalName();
        
        // Store the file
        $path = $file->storeAs($directory, $filename, 'public');
        
        // Return the path for database storage
        return $path;
    }

    public function render()
    {
        $perPage = (int) request()->get('perPage', 10);
        if ($perPage <= 0) { $perPage = 10; }

        $query = system_settings::query();

        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('key', 'like', '%' . $this->search . '%')
                  ->orWhere('value', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%')
                  ->orWhere('type', 'like', '%' . $this->search . '%');
            });
        }

        $settings = $query->orderBy('key')->paginate($perPage);

        return view('livewire.system-settings.system-settings', compact('settings'));
    }
}
