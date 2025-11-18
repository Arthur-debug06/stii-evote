<div>
    <!-- Toast Notification Template -->
    <x-menu.notification-toast seconds="10" layout="compact" animated="true" />
    
    <!-- JavaScript Alert Listener -->
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('show-alert', (event) => {
                alert(event.title + '\n\n' + event.message);
            });
        });
    </script>
    
    <h2 class="mt-10 text-lg font-medium">Voting Exclusive Management</h2>
    <div class="mt-5 grid grid-cols-12 gap-6">
        <div class="col-span-12 mt-2 flex flex-wrap items-center sm:flex-nowrap">
            <button wire:click="openAddModal" class="cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 bg-(--color)/20 border-(--color)/60 text-(--color) hover:bg-(--color)/5 [--color:var(--color-primary)] h-10 px-4 py-2 box mr-2">
                Add New Voting Exclusive
            </button>
            
            <div class="mx-auto hidden opacity-70 md:block">
                @if($votingExclusives->total() > 0)
                    Showing {{ $votingExclusives->firstItem() }} to {{ $votingExclusives->lastItem() }} of {{ $votingExclusives->total() }} entries
                    @if(!empty($search))
                        (filtered from {{ \App\Models\voting_exclusive::count() }} total entries)
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
                        placeholder="Search department, course, status...">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="search" class="lucide lucide-search size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 absolute inset-y-0 right-0 my-auto mr-3 h-4 w-4"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.3-4.3"></path></svg>
                </div>
            </div>
        </div>
        
        <!-- Add New Voting Exclusive Modal -->
        <x-menu.modal 
            :showButton="false" 
            modalId="add-voting-exclusive-modal" 
            title="Add New Voting Exclusive" 
            description="Fill in the details to add new voting exclusive"
            size="lg"
            :isOpen="$showAddModal">
            
            <!-- Existing Voting Periods Reference -->
            @if($existingPeriods->count() > 0)
            <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                <h4 class="text-sm font-medium text-blue-800 mb-2">📅 Existing Voting Periods (for reference)</h4>
                <div class="text-xs text-blue-600 space-y-1 max-h-32 overflow-y-auto">
                    @foreach($existingPeriods as $period)
                    <div class="flex justify-between items-center">
                        <span>{{ $period['department'] }} - {{ $period['course'] }}</span>
                        <span class="text-blue-500">{{ $period['start_date'] }} to {{ $period['end_date'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <form wire:submit.prevent="createVotingExclusive" class="space-y-4">
                <div class="grid grid-cols-2 gap-2">
                    <div class="mb-2">
                        <label class="block text-sm mb-1" for="add-department">Department</label>
                        <select 
                            wire:model.live="department_id" 
                            id="add-department"
                            class="h-10 w-full rounded-md border bg-background px-3 py-2 ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                            <option value="">All Departments</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->department_name }}</option>
                            @endforeach
                        </select>
                        @error('department_id') <div class="error-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-2">
                        <label class="block text-sm mb-1" for="add-course">Course</label>
                        <select 
                            wire:model.defer="course_id" 
                            id="add-course"
                            class="h-10 w-full rounded-md border bg-background px-3 py-2 ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                            <option value="">All Courses</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}">{{ $course->course_name }}</option>
                            @endforeach
                        </select>
                        @error('course_id') <div class="error-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-2">
                        <label class="block text-sm mb-1" for="add-start-datetime">Start Date & Time</label>
                        <input 
                            wire:model.defer="start_datetime" 
                            id="add-start-datetime"
                            type="datetime-local"
                            class="h-10 w-full rounded-md border bg-background px-3 py-2 ring-offset-background file:border-0 file:bg-transparent file:font-medium file:text-foreground placeholder:text-foreground/70 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                        <div class="text-xs text-gray-500 mt-1">Must be in the future and have at least 1 hour gap from existing periods</div>
                        @error('start_datetime') <div class="error-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-2">
                        <label class="block text-sm mb-1" for="add-end-datetime">End Date & Time</label>
                        <input 
                            wire:model.defer="end_datetime" 
                            id="add-end-datetime"
                            type="datetime-local"
                            class="h-10 w-full rounded-md border bg-background px-3 py-2 ring-offset-background file:border-0 file:bg-transparent file:font-medium file:text-foreground placeholder:text-foreground/70 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                        <div class="text-xs text-gray-500 mt-1">Must be after start date and have at least 1 hour gap from existing periods</div>
                        @error('end_datetime') <div class="error-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-2">
                        <label class="block text-sm mb-1" for="add-status">Status</label>
                        <select 
                            wire:model.defer="status" 
                            id="add-status"
                            class="h-10 w-full rounded-md border bg-background px-3 py-2 ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                        @error('status') <div class="error-message">{{ $message }}</div> @enderror
                    </div>
                </div>
            </form>

            <x-slot:footer>
                <button data-tw-dismiss="modal" type="button" wire:click="$set('showAddModal', false)" class="cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 text-foreground hover:bg-foreground/5 bg-background border-foreground/20 h-10 px-4 py-2 mr-1 w-24">
                    Cancel
                </button>
                <button type="button" wire:click="createVotingExclusive" class="cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 bg-(--color)/20 border-(--color)/60 text-(--color) hover:bg-(--color)/5 [--color:var(--color-primary)] h-10 px-4 py-2 w-24">
                    Create
                </button>
            </x-slot:footer>
        </x-menu.modal>

        <!-- Edit Voting Exclusive Modal -->
        <x-menu.modal 
            :showButton="false" 
            modalId="edit-voting-exclusive-modal" 
            title="Edit Voting Exclusive" 
            description="Update the voting exclusive details"
            size="lg"
            :isOpen="$showEditModal">
            
            <!-- Existing Voting Periods Reference -->
            @if($existingPeriods->count() > 0)
            <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                <h4 class="text-sm font-medium text-blue-800 mb-2">📅 Existing Voting Periods (for reference)</h4>
                <div class="text-xs text-blue-600 space-y-1 max-h-32 overflow-y-auto">
                    @foreach($existingPeriods as $period)
                    <div class="flex justify-between items-center">
                        <span>{{ $period['department'] }} - {{ $period['course'] }}</span>
                        <span class="text-blue-500">{{ $period['start_date'] }} to {{ $period['end_date'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <form wire:submit.prevent="updateVotingExclusive" class="space-y-4">
                <div class="grid grid-cols-2 gap-2">
                    <div class="mb-2">
                        <label class="block text-sm mb-1" for="edit-department">Department</label>
                        <select 
                            wire:model.live="department_id" 
                            id="edit-department"
                            class="h-10 w-full rounded-md border bg-background px-3 py-2 ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                            <option value="">All Departments</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->department_name }}</option>
                            @endforeach
                        </select>
                        @error('department_id') <div class="error-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-2">
                        <label class="block text-sm mb-1" for="edit-course">Course</label>
                        <select 
                            wire:model.defer="course_id" 
                            id="edit-course"
                            class="h-10 w-full rounded-md border bg-background px-3 py-2 ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                            <option value="">All Courses</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}">{{ $course->course_name }}</option>
                            @endforeach
                        </select>
                        @error('course_id') <div class="error-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-2">
                        <label class="block text-sm mb-1" for="edit-status">Status</label>
                        <select 
                            wire:model.defer="status" 
                            id="edit-status"
                            class="h-10 w-full rounded-md border bg-background px-3 py-2 ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                        @error('status') <div class="error-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-2">
                        <label class="block text-sm mb-1" for="edit-start-datetime">Start Date & Time</label>
                        <input 
                            wire:model.defer="start_datetime" 
                            id="edit-start-datetime"
                            type="datetime-local"
                            class="h-10 w-full rounded-md border bg-background px-3 py-2 ring-offset-background file:border-0 file:bg-transparent file:font-medium file:text-foreground placeholder:text-foreground/70 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                        <div class="text-xs text-gray-500 mt-1">Must be in the future and have at least 1 hour gap from existing periods</div>
                        @error('start_datetime') <div class="error-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-2">
                        <label class="block text-sm mb-1" for="edit-end-datetime">End Date & Time</label>
                        <input 
                            wire:model.defer="end_datetime" 
                            id="edit-end-datetime"
                            type="datetime-local"
                            class="h-10 w-full rounded-md border bg-background px-3 py-2 ring-offset-background file:border-0 file:bg-transparent file:font-medium file:text-foreground placeholder:text-foreground/70 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                        <div class="text-xs text-gray-500 mt-1">Must be after start date and have at least 1 hour gap from existing periods</div>
                        @error('end_datetime') <div class="error-message">{{ $message }}</div> @enderror
                    </div>
                </div>
            </form>

            <x-slot:footer>
                <button data-tw-dismiss="modal" type="button" wire:click="$set('showEditModal', false)" class="cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 text-foreground hover:bg-foreground/5 bg-background border-foreground/20 h-10 px-4 py-2 mr-1 w-24">
                    Cancel
                </button>
                <button type="button" wire:click="updateVotingExclusive" class="cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 bg-(--color)/20 border-(--color)/60 text-(--color) hover:bg-(--color)/5 [--color:var(--color-primary)] h-10 px-4 py-2 w-24">
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
                                DEPARTMENT
                            </th>
                            <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0">
                                COURSE
                            </th>
                            <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0">
                                SCHOOL YEAR
                            </th>
                            <th class="h-12 px-4 align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0 text-center">
                                START DATE
                            </th>
                            <th class="h-12 px-4 align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0 text-center">
                                END DATE
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
                        @forelse($votingExclusives as $item)
                        <tr class="transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted border-b-0">
                            <td class="shadow-[3px_3px_5px_#0000000b] first:rounded-l-xl last:rounded-r-xl box rounded-none p-4 align-middle [&amp;:has([role=checkbox])]:pr-0 border-y border-foreground/10 bg-background first:border-l last:border-r">
                                <div class="font-medium">
                                    @if($item->department)
                                        {{ $item->department->department_name }}
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            All Departments
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="shadow-[3px_3px_5px_#0000000b] first:rounded-l-xl last:rounded-r-xl box rounded-none p-4 align-middle [&amp;:has([role=checkbox])]:pr-0 border-y border-foreground/10 bg-background first:border-l last:border-r">
                                <div class="font-medium">
                                    @if($item->course)
                                        {{ $item->course->course_name }}
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            All Courses
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="shadow-[3px_3px_5px_#0000000b] first:rounded-l-xl last:rounded-r-xl box rounded-none p-4 align-middle [&amp;:has([role=checkbox])]:pr-0 border-y border-foreground/10 bg-background first:border-l last:border-r">
                                <div class="text-sm">
                                    @if($item->schoolYear)
                                        {{ $item->schoolYear->school_year }} - {{ $item->schoolYear->semester }}
                                    @else
                                        <span class="text-gray-400">No school year</span>
                                    @endif
                                </div>
                            </td>
                            <td class="shadow-[3px_3px_5px_#0000000b] first:rounded-l-xl last:rounded-r-xl box rounded-none p-4 align-middle [&amp;:has([role=checkbox])]:pr-0 border-y border-foreground/10 bg-background first:border-l last:border-r text-center">
                                <div class="text-sm">{{ $item->start_datetime ? \Carbon\Carbon::parse($item->start_datetime)->format('M d, Y') : 'N/A' }}</div>
                                <div class="text-xs opacity-70">{{ $item->start_datetime ? \Carbon\Carbon::parse($item->start_datetime)->format('h:i A') : '' }}</div>
                            </td>
                            <td class="shadow-[3px_3px_5px_#0000000b] first:rounded-l-xl last:rounded-r-xl box rounded-none p-4 align-middle [&amp;:has([role=checkbox])]:pr-0 border-y border-foreground/10 bg-background first:border-l last:border-r text-center">
                                <div class="text-sm">{{ $item->end_datetime ? \Carbon\Carbon::parse($item->end_datetime)->format('M d, Y') : 'N/A' }}</div>
                                <div class="text-xs opacity-70">{{ $item->end_datetime ? \Carbon\Carbon::parse($item->end_datetime)->format('h:i A') : '' }}</div>
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
                                    <button wire:click="openEditModal({{ $item->id }})" class="mr-3 flex items-center text-blue-600 hover:text-blue-800">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 mr-1">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                        </svg>
                                        Edit
                                    </button>
                                    <button wire:click="openDeleteModal({{ $item->id }})" class="text-red-600 hover:text-red-800 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 mr-1">
                                            <path d="M3 6h18"></path>
                                            <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                            <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                        </svg>
                                        Move to Trash
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-8 text-gray-500">
                                No voting exclusives found. Be the first to add a voting exclusive!
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <!-- END: Data List -->
        <!-- BEGIN: Pagination -->
        <x-menu.pagination :paginator="$votingExclusives" :perPageOptions="[10, 25, 35, 50]" />
        <!-- END: Pagination -->
    </div>
    <!-- Move to Trash Voting Exclusive Modal -->
    @if($showDeleteModal)
    <x-menu.modal 
        :showButton="false" 
        modalId="delete-voting-exclusive-modal" 
        title="Move to Trash Voting Exclusive" 
        description="This action cannot be undone."
        size="md"
        :isOpen="$showDeleteModal">
        <div class="text-center py-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-10 h-10 text-red-500 mx-auto mb-3"><path d="M3 6h18"></path><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path></svg>
            <div class="mt-2 text-sm">Are you sure you want to move this voting exclusive to trash?</div>
        </div>
        <x-slot:footer>
            <button data-tw-dismiss="modal" type="button" wire:click="cancelDelete" class="cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 text-foreground hover:bg-foreground/5 bg-background border-foreground/20 h-10 px-4 py-2 mr-1 w-24">
                Cancel
            </button>
            <button type="button" wire:click="deleteConfirmed" class="cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 bg-(--color)/20 border-(--color)/60 text-(--color) hover:bg-(--color)/5 [--color:var(--color-danger)] h-10 px-4 py-2 w-32">
                Move to Trash
            </button>
        </x-slot:footer>
    </x-menu.modal>
    @endif
 
</div>