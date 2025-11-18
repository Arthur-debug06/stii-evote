<div>
    <!-- Toast Notification Template -->
    <x-menu.notification-toast seconds="10" layout="compact" animated="true" />
    
    <h2 class="mt-10 text-lg font-medium">Inventory Management</h2>
    <div class="mt-5 grid grid-cols-12 gap-6">
        <div class="col-span-12 mt-2 flex flex-wrap items-center sm:flex-nowrap">
            <button wire:click="openAddModal" class="cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 bg-(--color)/20 border-(--color)/60 text-(--color) hover:bg-(--color)/5 [--color:var(--color-primary)] h-10 px-4 py-2 box mr-2">
                Add New Item
            </button>
            
            <div class="mx-auto hidden opacity-70 md:block">
                @if($inventory->total() > 0)
                    Showing {{ $inventory->firstItem() }} to {{ $inventory->lastItem() }} of {{ $inventory->total() }} entries
                    @if(!empty($search))
                        (filtered from {{ \App\Models\tbl_inventory::count() }} total entries)
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
                        placeholder="Search items, categories, price...">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="search" class="lucide lucide-search size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 absolute inset-y-0 right-0 my-auto mr-3 h-4 w-4"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.3-4.3"></path></svg>
                </div>
            </div>
        </div>
        
        <!-- Add New Inventory Modal -->
        <x-menu.modal 
            :showButton="false" 
            modalId="add-inventory-modal" 
            title="Add New Inventory Item" 
            description="Fill in the details to add new inventory item"
            size="lg"
            :isOpen="$showAddInventoryModal">
            
            <form wire:submit.prevent="createInventory" class="space-y-4">
                <div class="grid gap-4 gap-y-3">
                    <div class="grid grid-cols-4 items-center gap-4">
                        <label class="text-right text-sm font-medium" for="add-item-name">
                            Item Name
                        </label>
                        <input 
                            wire:model.defer="item_name" 
                            id="add-item-name"
                            type="text"
                            class="col-span-3 w-full rounded-md border bg-background px-3 py-2 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5" 
                            placeholder="Enter item name...">
                    </div>
                    @error('item_name') <div class="col-start-2 col-span-3 text-danger text-xs">{{ $message }}</div> @enderror
                    
                    <div class="grid grid-cols-4 items-center gap-4">
                        <label class="text-right text-sm font-medium" for="add-category">
                            Category
                        </label>
                        <select 
                            wire:model.defer="category_id" 
                            id="add-category"
                            class="col-span-3 w-full rounded-md border bg-background px-3 py-2 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5">
                            <option value="">Select category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('category_id') <div class="col-start-2 col-span-3 text-danger text-xs">{{ $message }}</div> @enderror

                    <div class="grid grid-cols-4 items-center gap-4">
                        <label class="text-right text-sm font-medium" for="add-quantity">
                            Quantity
                        </label>
                        <input 
                            wire:model.defer="quantity" 
                            id="add-quantity"
                            type="number"
                            min="0"
                            class="col-span-3 w-full rounded-md border bg-background px-3 py-2 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5" 
                            placeholder="Enter quantity...">
                    </div>
                    @error('quantity') <div class="col-start-2 col-span-3 text-danger text-xs">{{ $message }}</div> @enderror

                    <div class="grid grid-cols-4 items-center gap-4">
                        <label class="text-right text-sm font-medium" for="add-price">
                            Price
                        </label>
                        <input 
                            wire:model.defer="price" 
                            id="add-price"
                            type="number"
                            step="0.01"
                            min="0"
                            class="col-span-3 w-full rounded-md border bg-background px-3 py-2 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5" 
                            placeholder="Enter price...">
                    </div>
                    @error('price') <div class="col-start-2 col-span-3 text-danger text-xs">{{ $message }}</div> @enderror

                    <div class="grid grid-cols-4 items-center gap-4">
                        <label class="text-right text-sm font-medium" for="add-image">
                            Image
                        </label>
                        <input 
                            wire:model="image" 
                            id="add-image"
                            type="file"
                            accept="image/*"
                            class="col-span-3 w-full rounded-md border bg-background px-3 py-2 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5">
                    </div>
                    @error('image') <div class="col-start-2 col-span-3 text-danger text-xs">{{ $message }}</div> @enderror

                    <div class="grid grid-cols-4 items-center gap-4">
                        <label class="text-right text-sm font-medium" for="add-status">
                            Status
                        </label>
                        <select 
                            wire:model.defer="status" 
                            id="add-status"
                            class="col-span-3 w-full rounded-md border bg-background px-3 py-2 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    @error('status') <div class="col-start-2 col-span-3 text-danger text-xs">{{ $message }}</div> @enderror

                    <div class="grid grid-cols-4 items-center gap-4">
                        <label class="text-right text-sm font-medium">
                            Low Quantity Alert
                        </label>
                        <div class="col-span-3">
                            <div class="text-sm text-gray-600 bg-yellow-50 border border-yellow-200 rounded-md p-2">
                                ℹ️ Items with 5 or fewer quantity will automatically be marked as low stock
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <x-slot:footer>
                <button data-tw-dismiss="modal" type="button" wire:click="$set('showAddInventoryModal', false)" class="cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 text-foreground hover:bg-foreground/5 bg-background border-foreground/20 h-10 px-4 py-2 mr-1 w-24">
                    Cancel
                </button>
                <button type="button" wire:click="createInventory" class="cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 bg-(--color)/20 border-(--color)/60 text-(--color) hover:bg-(--color)/5 [--color:var(--color-primary)] h-10 px-4 py-2 w-24">
                    Submit
                </button>
            </x-slot:footer>
        </x-menu.modal>

        <!-- Edit Inventory Modal -->
        <x-menu.modal 
            :showButton="false" 
            modalId="edit-inventory-modal" 
            title="Edit Inventory Item" 
            description="Update the inventory item details"
            size="lg"
            :isOpen="$showEditInventoryModal">
            
            <form wire:submit.prevent="updateInventory" class="space-y-4">
                <div class="grid gap-4 gap-y-3">
                    <div class="grid grid-cols-4 items-center gap-4">
                        <label class="text-right text-sm font-medium" for="edit-item-name">
                            Item Name
                        </label>
                        <input 
                            wire:model.defer="item_name" 
                            id="edit-item-name"
                            type="text"
                            class="col-span-3 w-full rounded-md border bg-background px-3 py-2 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5" 
                            placeholder="Enter item name...">
                    </div>
                    @error('item_name') <div class="col-start-2 col-span-3 text-danger text-xs">{{ $message }}</div> @enderror
                    
                    <div class="grid grid-cols-4 items-center gap-4">
                        <label class="text-right text-sm font-medium" for="edit-category">
                            Category
                        </label>
                        <select 
                            wire:model.defer="category_id" 
                            id="edit-category"
                            class="col-span-3 w-full rounded-md border bg-background px-3 py-2 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5">
                            <option value="">Select category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('category_id') <div class="col-start-2 col-span-3 text-danger text-xs">{{ $message }}</div> @enderror

                    <div class="grid grid-cols-4 items-center gap-4">
                        <label class="text-right text-sm font-medium" for="edit-quantity">
                            Quantity
                        </label>
                        <input 
                            wire:model.defer="quantity" 
                            id="edit-quantity"
                            type="number"
                            min="0"
                            class="col-span-3 w-full rounded-md border bg-background px-3 py-2 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5" 
                            placeholder="Enter quantity...">
                    </div>
                    @error('quantity') <div class="col-start-2 col-span-3 text-danger text-xs">{{ $message }}</div> @enderror

                    <div class="grid grid-cols-4 items-center gap-4">
                        <label class="text-right text-sm font-medium" for="edit-price">
                            Price
                        </label>
                        <input 
                            wire:model.defer="price" 
                            id="edit-price"
                            type="number"
                            step="0.01"
                            min="0"
                            class="col-span-3 w-full rounded-md border bg-background px-3 py-2 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5" 
                            placeholder="Enter price...">
                    </div>
                    @error('price') <div class="col-start-2 col-span-3 text-danger text-xs">{{ $message }}</div> @enderror

                    <div class="grid grid-cols-4 items-center gap-4">
                        <label class="text-right text-sm font-medium" for="edit-image">
                            Image
                        </label>
                        <input 
                            wire:model="image" 
                            id="edit-image"
                            type="file"
                            accept="image/*"
                            class="col-span-3 w-full rounded-md border bg-background px-3 py-2 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5">
                    </div>
                    @error('image') <div class="col-start-2 col-span-3 text-danger text-xs">{{ $message }}</div> @enderror

                    <div class="grid grid-cols-4 items-center gap-4">
                        <label class="text-right text-sm font-medium" for="edit-status">
                            Status
                        </label>
                        <select 
                            wire:model.defer="status" 
                            id="edit-status"
                            class="col-span-3 w-full rounded-md border bg-background px-3 py-2 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    @error('status') <div class="col-start-2 col-span-3 text-danger text-xs">{{ $message }}</div> @enderror

                    <div class="grid grid-cols-4 items-center gap-4">
                        <label class="text-right text-sm font-medium">
                            Low Quantity Alert
                        </label>
                        <div class="col-span-3">
                            <div class="text-sm text-gray-600 bg-yellow-50 border border-yellow-200 rounded-md p-2">
                                ℹ️ Items with 5 or fewer quantity will automatically be marked as low stock
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <x-slot:footer>
                <button data-tw-dismiss="modal" type="button" wire:click="$set('showEditInventoryModal', false)" class="cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 text-foreground hover:bg-foreground/5 bg-background border-foreground/20 h-10 px-4 py-2 mr-1 w-24">
                    Cancel
                </button>
                <button type="button" wire:click="updateInventory" class="cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 bg-(--color)/20 border-(--color)/60 text-(--color) hover:bg-(--color)/5 [--color:var(--color-primary)] h-10 px-4 py-2 w-24">
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
                                IMAGE
                            </th>
                            <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0">
                                ITEM NAME
                            </th>
                            <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0">
                                CATEGORY
                            </th>
                            <th class="h-12 px-4 align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0 text-center">
                                QUANTITY
                            </th>
                            <th class="h-12 px-4 align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0 text-center">
                                PRICE
                            </th>
                            <th class="h-12 px-4 align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0 text-center">
                                STATUS
                            </th>
                            <th class="h-12 px-4 align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0 text-center">
                                ACTIONS
                            </th>
                        </tr>
                    </thead>
                    <tbody class="[&amp;_tr:last-child]:border-0">
                        @forelse($inventory as $item)
                        <tr class="transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted border-b-0">
                            <td class="shadow-[3px_3px_5px_#0000000b] first:rounded-l-xl last:rounded-r-xl box rounded-none p-4 align-middle [&amp;:has([role=checkbox])]:pr-0 border-y border-foreground/10 bg-background first:border-l last:border-r">
                                @if($item->image)
                                    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->item_name }}" class="w-16 h-16 object-cover rounded-lg">
                                @else
                                    <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-8 h-8 text-gray-400">
                                            <rect width="18" height="18" x="3" y="3" rx="2" ry="2"/>
                                            <circle cx="9" cy="9" r="2"/>
                                            <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/>
                                        </svg>
                                    </div>
                                @endif
                            </td>
                            <td class="shadow-[3px_3px_5px_#0000000b] first:rounded-l-xl last:rounded-r-xl box rounded-none p-4 align-middle [&amp;:has([role=checkbox])]:pr-0 border-y border-foreground/10 bg-background first:border-l last:border-r">
                                <div class="font-medium">{{ $item->item_name }}</div>
                                @if($item->is_low_quantity)
                                    <div class="text-xs text-red-600 font-medium">⚠️ Low Stock</div>
                                @endif
                            </td>
                            <td class="shadow-[3px_3px_5px_#0000000b] first:rounded-l-xl last:rounded-r-xl box rounded-none p-4 align-middle [&amp;:has([role=checkbox])]:pr-0 border-y border-foreground/10 bg-background first:border-l last:border-r">
                                <div class="text-sm">{{ $item->category->category_name ?? 'No Category' }}</div>
                            </td>
                            <td class="shadow-[3px_3px_5px_#0000000b] first:rounded-l-xl last:rounded-r-xl box rounded-none p-4 align-middle [&amp;:has([role=checkbox])]:pr-0 border-y border-foreground/10 bg-background first:border-l last:border-r text-center">
                                <div class="font-medium">{{ $item->quantity }}</div>
                            </td>
                            <td class="shadow-[3px_3px_5px_#0000000b] first:rounded-l-xl last:rounded-r-xl box rounded-none p-4 align-middle [&amp;:has([role=checkbox])]:pr-0 border-y border-foreground/10 bg-background first:border-l last:border-r text-center">
                                <div class="font-medium">₱{{ number_format($item->price, 2) }}</div>
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
                            <td class="shadow-[3px_3px_5px_#0000000b] first:rounded-l-xl last:rounded-r-xl box rounded-none p-4 align-middle [&amp;:has([role=checkbox])]:pr-0 border-y border-foreground/10 bg-background first:border-l last:border-r">
                                <div class="flex items-center justify-center">
                                    <button wire:click="editInventory({{ $item->id }})" class="mr-3 flex items-center text-blue-600 hover:text-blue-800">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 mr-1">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                        </svg>
                                        Edit
                                    </button>
                                    <button wire:click="deleteInventory({{ $item->id }})" class="text-red-600 hover:text-red-800 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 mr-1">
                                            <path d="M3 6h18"></path>
                                            <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                            <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                        </svg>
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-8 text-gray-500">
                                No inventory items found. Be the first to add an item!
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <!-- END: Data List -->
        <!-- BEGIN: Pagination -->
        <x-menu.pagination :paginator="$inventory" :perPageOptions="[10, 25, 35, 50]" />
        <!-- END: Pagination -->
    </div>
    
    <!-- Delete Inventory Modal -->
    @if($showDeleteInventoryModal)
    <x-menu.modal 
        :showButton="false" 
        modalId="delete-inventory-modal" 
        title="Delete Inventory Item" 
        description="This action cannot be undone."
        size="md"
        :isOpen="$showDeleteInventoryModal">
        <div class="text-center py-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-10 h-10 text-red-500 mx-auto mb-3"><path d="M3 6h18"></path><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path></svg>
            <div class="mt-2 text-sm">Are you sure you want to delete this inventory item?</div>
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