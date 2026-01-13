<div>
    <!-- Toast Notification Template -->
    <x-menu.notification-toast seconds="10" layout="compact" animated="true" />
    
    <h2 class="mt-10 text-lg font-medium">System Settings</h2>
    <div class="mt-5 grid grid-cols-12 gap-6">
        <div class="col-span-12 mt-2 flex flex-wrap items-center sm:flex-nowrap">
            <!-- <button wire:click="openAddModal" class="cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 bg-(--color)/20 border-(--color)/60 text-(--color) hover:bg-(--color)/5 [--color:var(--color-primary)] h-10 px-4 py-2 box mr-2">
                Add New Setting
            </button> -->
            
            <div class="mx-auto hidden opacity-70 md:block">
                @if($settings->total() > 0)
                    Showing {{ $settings->firstItem() }} to {{ $settings->lastItem() }} of {{ $settings->total() }} entries
                    @if(!empty($search))
                        (filtered from {{ \App\Models\system_settings::count() }} total entries)
                    @endif
                @else
                    No entries found
                    @if(!empty($search))
                        for "{{ $search }}"
                    @endif
                @endif
            </div>
            <div class="mt-3 w-full sm:ml-auto sm:mt-0 sm:w-auto md:ml-0">
                <div class="relative w-56">
                    <input 
                        wire:model.live.debounce.300ms="search" 
                        class="h-10 rounded-md border bg-background px-3 py-2 ring-offset-background file:border-0 file:bg-transparent file:font-medium file:text-foreground placeholder:text-foreground/70 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 box w-56 pr-10" 
                        type="text" 
                        placeholder="Search setting key, value, description...">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="search" class="lucide lucide-search size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 absolute inset-y-0 right-0 my-auto mr-3 h-4 w-4"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.3-4.3"></path></svg>
                </div>
            </div>
        </div>
        
        <!-- Add New Setting Modal -->
        <x-menu.modal 
            :showButton="false" 
            modalId="add-setting-modal" 
            title="Add New System Setting" 
            description="Fill in the details to add new system setting"
            size="lg"
            :isOpen="$showAddSettingModal">
            
            <form wire:submit.prevent="createSetting" class="space-y-4">
                <div class="grid gap-4 gap-y-3">
                    <div class="grid grid-cols-4 items-center gap-4">
                        <label class="text-right text-sm font-medium" for="add-key">
                            Setting Key
                        </label>
                        <input 
                            wire:model.defer="key" 
                            id="add-key"
                            class="col-span-3 w-full rounded-md border bg-gray-100 px-3 py-2 text-gray-500 cursor-not-allowed" 
                            type="text" 
                            placeholder="Enter setting key..."
                            readonly>
                    </div>
                    @error('key') <div class="col-start-2 col-span-3 text-danger text-xs">{{ $message }}</div> @enderror
                    
                    <div class="grid grid-cols-4 items-center gap-4">
                        <label class="text-right text-sm font-medium" for="add-value">
                            Value
                        </label>
                        <div class="col-span-3">
                            @if($type === 'image')
                                <input 
                                    wire:model="uploadedFile" 
                                    id="add-value"
                                    class="w-full rounded-md border bg-background px-3 py-2 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5" 
                                    type="file" 
                                    accept="image/*">
                                <div class="mt-2 text-xs text-gray-500">
                                    Current: {{ $value ?: 'No file selected' }}
                                </div>
                            @elseif($type === 'boolean')
                                <select 
                                    wire:model.defer="value" 
                                    id="add-value"
                                    class="w-full rounded-md border bg-background px-3 py-2 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5">
                                    <option value="">Select value</option>
                                    <option value="true">True</option>
                                    <option value="false">False</option>
                                </select>
                            @elseif($type === 'number')
                                <input 
                                    wire:model.defer="value" 
                                    id="add-value"
                                    class="w-full rounded-md border bg-background px-3 py-2 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5" 
                                    type="number" 
                                    placeholder="Enter number value...">
                            @elseif($type === 'email')
                                <input 
                                    wire:model.defer="value" 
                                    id="add-value"
                                    class="w-full rounded-md border bg-background px-3 py-2 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5" 
                                    type="email" 
                                    placeholder="Enter email address...">
                            @elseif($type === 'url')
                                <input 
                                    wire:model.defer="value" 
                                    id="add-value"
                                    class="w-full rounded-md border bg-background px-3 py-2 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5" 
                                    type="url" 
                                    placeholder="Enter URL...">
                            @else
                                <input 
                                    wire:model.defer="value" 
                                    id="add-value"
                                    class="w-full rounded-md border bg-background px-3 py-2 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5" 
                                    type="text" 
                                    placeholder="Enter setting value...">
                            @endif
                        </div>
                    </div>
                    @error('value') <div class="col-start-2 col-span-3 text-danger text-xs">{{ $message }}</div> @enderror
                    
                    <div class="grid grid-cols-4 items-center gap-4">
                        <label class="text-right text-sm font-medium" for="add-type">
                            Type
                        </label>
                        <select 
                            wire:model.defer="type" 
                            id="add-type"
                            class="col-span-3 w-full rounded-md border bg-gray-100 px-3 py-2 text-gray-500 cursor-not-allowed"
                            disabled>
                            <option value="text">Text</option>
                            <option value="number">Number</option>
                            <option value="boolean">Boolean</option>
                            <option value="json">JSON</option>
                            <option value="email">Email</option>
                            <option value="url">URL</option>
                            <option value="image">Image</option>
                        </select>
                    </div>
                    @error('type') <div class="col-start-2 col-span-3 text-danger text-xs">{{ $message }}</div> @enderror
                    
                    <div class="grid grid-cols-4 items-center gap-4">
                        <label class="text-right text-sm font-medium" for="add-module-id">
                            Module ID
                        </label>
                        <input 
                            wire:model.defer="module_id" 
                            id="add-module-id"
                            class="col-span-3 w-full rounded-md border bg-gray-100 px-3 py-2 text-gray-500 cursor-not-allowed" 
                            type="number" 
                            placeholder="Enter module ID (optional)..."
                            disabled>
                    </div>
                    @error('module_id') <div class="col-start-2 col-span-3 text-danger text-xs">{{ $message }}</div> @enderror
                    
                    <div class="grid grid-cols-4 items-center gap-4">
                        <label class="text-right text-sm font-medium" for="add-description">
                            Description
                        </label>
                        <textarea 
                            wire:model.defer="description" 
                            id="add-description"
                            class="col-span-3 w-full rounded-md border bg-background px-3 py-2 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5" 
                            rows="3" 
                            placeholder="Enter setting description..."></textarea>
                    </div>
                    @error('description') <div class="col-start-2 col-span-3 text-danger text-xs">{{ $message }}</div> @enderror
                    
                    <div class="grid grid-cols-4 items-center gap-4">
                        <label class="text-right text-sm font-medium" for="add-status">
                            Status
                        </label>
                        <select 
                            wire:model.defer="status" 
                            id="add-status"
                            class="col-span-3 w-full rounded-md border bg-gray-100 px-3 py-2 text-gray-500 cursor-not-allowed"
                            disabled>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    @error('status') <div class="col-start-2 col-span-3 text-danger text-xs">{{ $message }}</div> @enderror
                </div>
            </form>

            <x-slot:footer>
                <button data-tw-dismiss="modal" type="button" wire:click="$set('showAddSettingModal', false)" class="cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 text-foreground hover:bg-foreground/5 bg-background border-foreground/20 h-10 px-4 py-2 mr-1 w-24">
                    Cancel
                </button>
                <button type="button" wire:click="createSetting" class="cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 bg-(--color)/20 border-(--color)/60 text-(--color) hover:bg-(--color)/5 [--color:var(--color-primary)] h-10 px-4 py-2 w-24">
                    Submit
                </button>
            </x-slot:footer>
        </x-menu.modal>

        <!-- Edit Setting Modal -->
        <x-menu.modal 
            :showButton="false" 
            modalId="edit-setting-modal" 
            title="Edit System Setting" 
            description="Update the system setting details"
            size="lg"
            :isOpen="$showEditSettingModal">
            
            <form wire:submit.prevent="updateSetting" class="space-y-4">
                <div class="grid gap-4 gap-y-3">
                    <div class="grid grid-cols-4 items-center gap-4">
                        <label class="text-right text-sm font-medium" for="edit-key">
                            Setting Key
                        </label>
                        <input 
                            wire:model.defer="key" 
                            id="edit-key"
                            class="col-span-3 w-full rounded-md border bg-gray-100 px-3 py-2 text-gray-500 cursor-not-allowed" 
                            type="text" 
                            placeholder="Enter setting key..."
                            readonly>
                    </div>
                    @error('key') <div class="col-start-2 col-span-3 text-danger text-xs">{{ $message }}</div> @enderror
                    
                    <div class="grid grid-cols-4 items-center gap-4">
                        <label class="text-right text-sm font-medium" for="edit-value">
                            Value
                        </label>
                        <div class="col-span-3">
                            @if($type === 'image')
                                <input 
                                    wire:model="uploadedFile" 
                                    id="edit-value"
                                    class="w-full rounded-md border bg-background px-3 py-2 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5" 
                                    type="file" 
                                    accept="image/*">
                                <div class="mt-2 text-xs text-gray-500">
                                    Current: {{ $value ?: 'No file selected' }}
                                </div>
                            @elseif($type === 'boolean')
                                <select 
                                    wire:model.defer="value" 
                                    id="edit-value"
                                    class="w-full rounded-md border bg-background px-3 py-2 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5">
                                    <option value="">Select value</option>
                                    <option value="true">True</option>
                                    <option value="false">False</option>
                                </select>
                            @elseif($type === 'number')
                                <input 
                                    wire:model.defer="value" 
                                    id="edit-value"
                                    class="w-full rounded-md border bg-background px-3 py-2 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5" 
                                    type="number" 
                                    placeholder="Enter number value...">
                            @elseif($type === 'email')
                                <input 
                                    wire:model.defer="value" 
                                    id="edit-value"
                                    class="w-full rounded-md border bg-background px-3 py-2 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5" 
                                    type="email" 
                                    placeholder="Enter email address...">
                            @elseif($type === 'url')
                                <input 
                                    wire:model.defer="value" 
                                    id="edit-value"
                                    class="w-full rounded-md border bg-background px-3 py-2 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5" 
                                    type="url" 
                                    placeholder="Enter URL...">
                            @else
                                <input 
                                    wire:model.defer="value" 
                                    id="edit-value"
                                    class="w-full rounded-md border bg-background px-3 py-2 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5" 
                                    type="text" 
                                    placeholder="Enter setting value...">
                            @endif
                        </div>
                    </div>
                    @error('value') <div class="col-start-2 col-span-3 text-danger text-xs">{{ $message }}</div> @enderror
                    
                    <div class="grid grid-cols-4 items-center gap-4">
                        <label class="text-right text-sm font-medium" for="edit-type">
                            Type
                        </label>
                        <select 
                            wire:model.defer="type" 
                            id="edit-type"
                            class="col-span-3 w-full rounded-md border bg-gray-100 px-3 py-2 text-gray-500 cursor-not-allowed"
                            disabled>
                            <option value="text">Text</option>
                            <option value="number">Number</option>
                            <option value="boolean">Boolean</option>
                            <option value="json">JSON</option>
                            <option value="email">Email</option>
                            <option value="url">URL</option>
                            <option value="image">Image</option>
                        </select>
                    </div>
                    @error('type') <div class="col-start-2 col-span-3 text-danger text-xs">{{ $message }}</div> @enderror
                    
                    <div class="grid grid-cols-4 items-center gap-4">
                        <label class="text-right text-sm font-medium" for="edit-module-id">
                            Module ID
                        </label>
                        <input 
                            wire:model.defer="module_id" 
                            id="edit-module-id"
                            class="col-span-3 w-full rounded-md border bg-gray-100 px-3 py-2 text-gray-500 cursor-not-allowed" 
                            type="number" 
                            placeholder="Enter module ID (optional)..."
                            disabled>
                    </div>
                    @error('module_id') <div class="col-start-2 col-span-3 text-danger text-xs">{{ $message }}</div> @enderror
                    
                    <div class="grid grid-cols-4 items-center gap-4">
                        <label class="text-right text-sm font-medium" for="edit-description">
                            Description
                        </label>
                        <textarea 
                            wire:model.defer="description" 
                            id="edit-description"
                            class="col-span-3 w-full rounded-md border bg-background px-3 py-2 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5" 
                            rows="3" 
                            placeholder="Enter setting description..."></textarea>
                    </div>
                    @error('description') <div class="col-start-2 col-span-3 text-danger text-xs">{{ $message }}</div> @enderror
                    
                    <div class="grid grid-cols-4 items-center gap-4">
                        <label class="text-right text-sm font-medium" for="edit-status">
                            Status
                        </label>
                        <select 
                            wire:model.defer="status" 
                            id="edit-status"
                            class="col-span-3 w-full rounded-md border bg-gray-100 px-3 py-2 text-gray-500 cursor-not-allowed"
                            disabled>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    @error('status') <div class="col-start-2 col-span-3 text-danger text-xs">{{ $message }}</div> @enderror
                    
                </div>
            </form>

            <x-slot:footer>
                <button data-tw-dismiss="modal" type="button" wire:click="$set('showEditSettingModal', false)" class="cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 text-foreground hover:bg-foreground/5 bg-background border-foreground/20 h-10 px-4 py-2 mr-1 w-24">
                    Cancel
                </button>
                <button type="button" wire:click="updateSetting" class="cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 bg-(--color)/20 border-(--color)/60 text-(--color) hover:bg-(--color)/5 [--color:var(--color-primary)] h-10 px-4 py-2 w-24">
                    Update
                </button>
            </x-slot:footer>
        </x-menu.modal>
        <!-- BEGIN: Data List -->
        <div class="col-span-12 overflow-auto lg:overflow-visible">
            <div class="relative w-full overflow-auto">
                <table class="w-full caption-bottom border-separate border-spacing-y-[10px] -mt-2">
                    <thead class="[&amp;_tr]:border-b-0 [&amp;_tr_th]:h-10">
                        <tr class="transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted border-b-0">
                            <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0">
                                SETTING KEY
                            </th>
                            <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0">
                                VALUE
                            </th>
                            <th class="h-12 px-4 align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0 text-center">
                                TYPE
                            </th>
                            <th class="h-12 px-4 align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0 text-center">
                                STATUS
                            </th>
                            <th class="h-12 px-4 align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0 text-center">
                                CREATED DATE
                            </th>
                            <th class="h-12 px-4 align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0 text-center">
                                ACTIONS
                            </th>
                        </tr>
                    </thead>
                    <tbody class="[&amp;_tr:last-child]:border-0">
                        @forelse($settings as $item)
                        <tr class="transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted border-b-0">
                            <td class="shadow-[3px_3px_5px_#0000000b] first:rounded-l-xl last:rounded-r-xl box rounded-none p-4 align-middle [&amp;:has([role=checkbox])]:pr-0 border-y border-foreground/10 bg-background first:border-l last:border-r">
                                <div class="font-medium">{{ $item->key }}</div>
                            </td>
                            <td class="shadow-[3px_3px_5px_#0000000b] first:rounded-l-xl last:rounded-r-xl box rounded-none p-4 align-middle [&amp;:has([role=checkbox])]:pr-0 border-y border-foreground/10 bg-background first:border-l last:border-r">
                                <div class="max-w-xs">
                                    {{ Str::limit($item->value, 50) }}
                                </div>
                            </td>
                            <td class="shadow-[3px_3px_5px_#0000000b] first:rounded-l-xl last:rounded-r-xl box rounded-none p-4 align-middle [&amp;:has([role=checkbox])]:pr-0 border-y border-foreground/10 bg-background first:border-l last:border-r">
                                <div class="flex items-center justify-center">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ ucfirst($item->type) }}
                                    </span>
                                </div>
                            </td>
                            <td class="shadow-[3px_3px_5px_#0000000b] first:rounded-l-xl last:rounded-r-xl box rounded-none p-4 align-middle [&amp;:has([role=checkbox])]:pr-0 border-y border-foreground/10 bg-background first:border-l last:border-r">
                                <div class="flex items-center justify-center">
                                    @if($item->status === 'active')
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                            Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                            </svg>
                                            Inactive
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="shadow-[3px_3px_5px_#0000000b] first:rounded-l-xl last:rounded-r-xl box rounded-none p-4 align-middle [&amp;:has([role=checkbox])]:pr-0 border-y border-foreground/10 bg-background first:border-l last:border-r text-center">
                                <div class="text-sm">{{ optional($item->created_at)->format('M d, Y') ?? 'N/A' }}</div>
                                <div class="text-xs opacity-70">{{ optional($item->created_at)->format('h:i A') ?? '' }}</div>
                            </td>
                            <td class="shadow-[3px_3px_5px_#0000000b] first:rounded-l-xl last:rounded-r-xl box rounded-none p-4 align-middle [&amp;:has([role=checkbox])]:pr-0 border-y border-foreground/10 bg-background first:border-l last:border-r">
                                <div class="flex items-center justify-center">
                                    <button wire:click="editSetting({{ $item->id }})" class="mr-3 flex items-center text-blue-600 hover:text-blue-800">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 mr-1">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                        </svg>
                                        Edit
                                    </button>
                                    <!-- <button wire:click="deleteSetting({{ $item->id }})" class="text-red-600 hover:text-red-800 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 mr-1">
                                            <path d="M3 6h18"></path>
                                            <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                            <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                        </svg>
                                        Delete
                                    </button> -->
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-8 text-gray-500">
                                No system settings found. Be the first to add a setting!
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <!-- END: Data List -->
        <!-- BEGIN: Pagination -->
        <x-menu.pagination :paginator="$settings" :perPageOptions="[10, 25, 35, 50]" />
        <!-- END: Pagination -->
    </div>
    <!-- Delete Setting Modal -->
    @if($showDeleteSettingModal)
    <x-menu.modal 
        :showButton="false" 
        modalId="delete-setting-modal" 
        title="Delete System Setting" 
        description="This action cannot be undone."
        size="md"
        :isOpen="$showDeleteSettingModal">
        <div class="text-center py-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-10 h-10 text-red-500 mx-auto mb-3"><path d="M3 6h18"></path><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path></svg>
            <div class="mt-2 text-sm">Are you sure you want to delete this system setting?</div>
        </div>
        <x-slot:footer>
            <button data-tw-dismiss="modal" type="button" wire:click="cancelDelete" class="cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 text-foreground hover:bg-foreground/5 bg-background border-foreground/20 h-10 px-4 py-2 mr-1 w-24">
                Cancel
            </button>
            <button type="button" wire:click="deleteConfirmed" class="cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 bg-(--color)/20 border-(--color)/60 text-(--color) hover:bg-(--color)/5 [--color:var(--color-danger)] h-10 px-4 py-2 w-32">
                Delete
            </button>
        </x-slot:footer>
    </x-menu.modal>
    @endif
 
</div>