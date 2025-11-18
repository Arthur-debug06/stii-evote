<div>
    <!-- Toast Notification Template -->
    <x-menu.notification-toast seconds="10" layout="compact" animated="true" />
    
    <h2 class="mt-10 text-lg font-medium">Appointment Requests</h2>
    <div class="mt-5 grid grid-cols-12 gap-6">
        <div class="col-span-12 mt-2 flex flex-wrap items-center sm:flex-nowrap">
            <!-- <button wire:click="openAddModal" class="cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 bg-(--color)/20 border-(--color)/60 text-(--color) hover:bg-(--color)/5 [--color:var(--color-primary)] h-10 px-4 py-2 box mr-2">
                Add New Appointment
            </button>
             -->
            <div class="mx-auto hidden opacity-70 md:block">
                @if($appointments->total() > 0)
                    Showing {{ $appointments->firstItem() }} to {{ $appointments->lastItem() }} of {{ $appointments->total() }} entries
                    @if(!empty($search))
                        (filtered from {{ \App\Models\tbl_appointment::count() }} total entries)
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
                        placeholder="Search names, emails, contacts, reasons...">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="search" class="lucide lucide-search size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 absolute inset-y-0 right-0 my-auto mr-3 h-4 w-4"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.3-4.3"></path></svg>
                </div>
            </div>
        </div>
        
        <!-- Add New Appointment Modal -->
        <x-menu.modal 
            :showButton="false" 
            modalId="add-appointment-modal" 
            title="Add New Appointment" 
            description="Fill in the details to add new appointment"
            size="lg"
            :isOpen="$showAddAppointmentModal">
            
            <form wire:submit.prevent="createAppointment" class="space-y-4">
                <div class="grid gap-4 gap-y-3">
                    <div class="grid grid-cols-4 items-center gap-4">
                        <label class="text-right text-sm font-medium" for="add-fullname">
                            Full Name
                        </label>
                        <input 
                            wire:model.defer="fullname" 
                            id="add-fullname"
                            type="text"
                            class="col-span-3 w-full rounded-md border bg-background px-3 py-2 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5" 
                            placeholder="Enter full name">
                    </div>
                    @error('fullname') <div class="col-start-2 col-span-3 text-danger text-xs">{{ $message }}</div> @enderror
                    
                    <div class="grid grid-cols-4 items-center gap-4">
                        <label class="text-right text-sm font-medium" for="add-email">
                            Email
                        </label>
                        <input 
                            wire:model.defer="email" 
                            id="add-email"
                            type="email"
                            class="col-span-3 w-full rounded-md border bg-background px-3 py-2 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5" 
                            placeholder="Enter email address">
                    </div>
                    @error('email') <div class="col-start-2 col-span-3 text-danger text-xs">{{ $message }}</div> @enderror

                    <div class="grid grid-cols-4 items-center gap-4">
                        <label class="text-right text-sm font-medium" for="add-contact">
                            Contact Number
                        </label>
                        <input 
                            wire:model.defer="contact_number" 
                            id="add-contact"
                            type="tel"
                            class="col-span-3 w-full rounded-md border bg-background px-3 py-2 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5" 
                            placeholder="Enter contact number">
                    </div>
                    @error('contact_number') <div class="col-start-2 col-span-3 text-danger text-xs">{{ $message }}</div> @enderror

                    <div class="grid grid-cols-4 items-center gap-4">
                        <label class="text-right text-sm font-medium" for="add-datetime">
                            Appointment Date & Time
                        </label>
                        <input 
                            wire:model.defer="appointment_datetime" 
                            id="add-datetime"
                            type="datetime-local"
                            class="col-span-3 w-full rounded-md border bg-background px-3 py-2 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5" 
                            min="{{ date('Y-m-d\TH:i', strtotime('+1 day 9:00')) }}">
                    </div>
                    @error('appointment_datetime') <div class="col-start-2 col-span-3 text-danger text-xs">{{ $message }}</div> @enderror

                    <div class="grid grid-cols-4 items-center gap-4">
                        <label class="text-right text-sm font-medium" for="add-reason">
                            Reason
                        </label>
                        <textarea 
                            wire:model.defer="reason" 
                            id="add-reason"
                            class="col-span-3 w-full rounded-md border bg-background px-3 py-2 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5" 
                            rows="3" 
                            placeholder="Reason for visit..."></textarea>
                    </div>
                    @error('reason') <div class="col-start-2 col-span-3 text-danger text-xs">{{ $message }}</div> @enderror

                    <div class="grid grid-cols-4 items-center gap-4">
                        <label class="text-right text-sm font-medium" for="add-status">
                            Status
                        </label>
                        <select 
                            wire:model.defer="status" 
                            id="add-status"
                            class="col-span-3 w-full rounded-md border bg-background px-3 py-2 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5">
                            <option value="pending">Pending</option>
                            <option value="confirmed">Confirmed</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    @error('status') <div class="col-start-2 col-span-3 text-danger text-xs">{{ $message }}</div> @enderror
                </div>
            </form>

            <x-slot:footer>
                <button data-tw-dismiss="modal" type="button" wire:click="$set('showAddAppointmentModal', false)" class="cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 text-foreground hover:bg-foreground/5 bg-background border-foreground/20 h-10 px-4 py-2 mr-1 w-24">
                    Cancel
                </button>
                <button type="button" wire:click="createAppointment" class="cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 bg-(--color)/20 border-(--color)/60 text-(--color) hover:bg-(--color)/5 [--color:var(--color-primary)] h-10 px-4 py-2 w-24">
                    Submit
                </button>
            </x-slot:footer>
        </x-menu.modal>

        <!-- Edit Appointment Modal -->
        <x-menu.modal 
            :showButton="false" 
            modalId="edit-appointment-modal" 
            title="Edit Appointment" 
            description="Update the appointment details"
            size="lg"
            :isOpen="$showEditAppointmentModal">
            
            <form wire:submit.prevent="updateAppointment" class="space-y-4">
                <div class="grid gap-4 gap-y-3">
                    <div class="grid grid-cols-4 items-center gap-4">
                        <label class="text-right text-sm font-medium" for="edit-fullname">
                            Full Name
                        </label>
                        <input 
                            wire:model.defer="fullname" 
                            id="edit-fullname"
                            type="text"
                            class="col-span-3 w-full rounded-md border bg-background px-3 py-2 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5" 
                            placeholder="Enter full name">
                    </div>
                    @error('fullname') <div class="col-start-2 col-span-3 text-danger text-xs">{{ $message }}</div> @enderror
                    
                    <div class="grid grid-cols-4 items-center gap-4">
                        <label class="text-right text-sm font-medium" for="edit-email">
                            Email
                        </label>
                        <input 
                            wire:model.defer="email" 
                            id="edit-email"
                            type="email"
                            class="col-span-3 w-full rounded-md border bg-background px-3 py-2 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5" 
                            placeholder="Enter email address">
                    </div>
                    @error('email') <div class="col-start-2 col-span-3 text-danger text-xs">{{ $message }}</div> @enderror

                    <div class="grid grid-cols-4 items-center gap-4">
                        <label class="text-right text-sm font-medium" for="edit-contact">
                            Contact Number
                        </label>
                        <input 
                            wire:model.defer="contact_number" 
                            id="edit-contact"
                            type="tel"
                            class="col-span-3 w-full rounded-md border bg-background px-3 py-2 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5" 
                            placeholder="Enter contact number">
                    </div>
                    @error('contact_number') <div class="col-start-2 col-span-3 text-danger text-xs">{{ $message }}</div> @enderror

                    <div class="grid grid-cols-4 items-center gap-4">
                        <label class="text-right text-sm font-medium" for="edit-datetime">
                            Appointment Date & Time
                        </label>
                        <input 
                            wire:model.defer="appointment_datetime" 
                            id="edit-datetime"
                            type="datetime-local"
                            class="col-span-3 w-full rounded-md border bg-background px-3 py-2 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5" 
                            min="{{ date('Y-m-d\TH:i', strtotime('+1 day 9:00')) }}">
                    </div>
                    @error('appointment_datetime') <div class="col-start-2 col-span-3 text-danger text-xs">{{ $message }}</div> @enderror

                    <div class="grid grid-cols-4 items-center gap-4">
                        <label class="text-right text-sm font-medium" for="edit-reason">
                            Reason
                        </label>
                        <textarea 
                            wire:model.defer="reason" 
                            id="edit-reason"
                            class="col-span-3 w-full rounded-md border bg-background px-3 py-2 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5" 
                            rows="3" 
                            placeholder="Reason for visit..."></textarea>
                    </div>
                    @error('reason') <div class="col-start-2 col-span-3 text-danger text-xs">{{ $message }}</div> @enderror

                    <div class="grid grid-cols-4 items-center gap-4">
                        <label class="text-right text-sm font-medium" for="edit-status">
                            Status
                        </label>
                        <select 
                            wire:model.defer="status" 
                            id="edit-status"
                            class="col-span-3 w-full rounded-md border bg-background px-3 py-2 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5">
                            <option value="pending">Pending</option>
                            <option value="confirmed">Confirmed</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    @error('status') <div class="col-start-2 col-span-3 text-danger text-xs">{{ $message }}</div> @enderror
                </div>
            </form>

            <x-slot:footer>
                <button data-tw-dismiss="modal" type="button" wire:click="$set('showEditAppointmentModal', false)" class="cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 text-foreground hover:bg-foreground/5 bg-background border-foreground/20 h-10 px-4 py-2 mr-1 w-24">
                    Cancel
                </button>
                <button type="button" wire:click="updateAppointment" class="cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 bg-(--color)/20 border-(--color)/60 text-(--color) hover:bg-(--color)/5 [--color:var(--color-primary)] h-10 px-4 py-2 w-24">
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
                                PATIENT INFO
                            </th>
                            <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0">
                                CONTACT
                            </th>
                            <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0">
                                REASON
                            </th>
                            <th class="h-12 px-4 align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0 text-center">
                                APPOINTMENT DATE
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
                        @forelse($appointments as $item)
                        <tr class="transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted border-b-0">
                            <td class="shadow-[3px_3px_5px_#0000000b] first:rounded-l-xl last:rounded-r-xl box rounded-none p-4 align-middle [&amp;:has([role=checkbox])]:pr-0 border-y border-foreground/10 bg-background first:border-l last:border-r">
                                <div class="font-medium">{{ $item->fullname }}</div>
                                <div class="text-xs opacity-70">{{ $item->email }}</div>
                            </td>
                            <td class="shadow-[3px_3px_5px_#0000000b] first:rounded-l-xl last:rounded-r-xl box rounded-none p-4 align-middle [&amp;:has([role=checkbox])]:pr-0 border-y border-foreground/10 bg-background first:border-l last:border-r">
                                <div class="text-sm">{{ $item->contact_number }}</div>
                            </td>
                            <td class="shadow-[3px_3px_5px_#0000000b] first:rounded-l-xl last:rounded-r-xl box rounded-none p-4 align-middle [&amp;:has([role=checkbox])]:pr-0 border-y border-foreground/10 bg-background first:border-l last:border-r">
                                <div class="max-w-xs">
                                    {{ Str::limit($item->reason, 50) }}
                                </div>
                            </td>
                            <td class="shadow-[3px_3px_5px_#0000000b] first:rounded-l-xl last:rounded-r-xl box rounded-none p-4 align-middle [&amp;:has([role=checkbox])]:pr-0 border-y border-foreground/10 bg-background first:border-l last:border-r text-center">
                                <div class="text-sm">{{ optional($item->appointment_datetime)->format('M d, Y') ?? 'N/A' }}</div>
                                <div class="text-xs opacity-70">{{ optional($item->appointment_datetime)->format('h:i A') ?? '' }}</div>
                            </td>
                            <td class="shadow-[3px_3px_5px_#0000000b] first:rounded-l-xl last:rounded-r-xl box rounded-none p-4 align-middle [&amp;:has([role=checkbox])]:pr-0 border-y border-foreground/10 bg-background first:border-l last:border-r">
                                <div class="flex items-center justify-center">
                                    @if($item->status === 'pending')
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                            </svg>
                                            Pending
                                        </span>
                                    @elseif($item->status === 'confirmed')
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                            Confirmed
                                        </span>
                                    @elseif($item->status === 'completed')
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                            Completed
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                            </svg>
                                            Cancelled
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="shadow-[3px_3px_5px_#0000000b] first:rounded-l-xl last:rounded-r-xl box rounded-none p-4 align-middle [&amp;:has([role=checkbox])]:pr-0 border-y border-foreground/10 bg-background first:border-l last:border-r">
                                <div class="flex items-center justify-center">
                                    <!-- <button wire:click="editAppointment({{ $item->id }})" class="mr-3 flex items-center text-blue-600 hover:text-blue-800">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 mr-1">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                        </svg>
                                        Edit
                                    </button>
                                    <button wire:click="deleteAppointment({{ $item->id }})" class="text-red-600 hover:text-red-800 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 mr-1">
                                            <path d="M3 6h18"></path>
                                            <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                            <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                        </svg>
                                        Delete
                                    </button> -->
                                    @if($item->status === 'pending')
                                        <button wire:click="approveAppointment({{ $item->id }})" class="mr-2 text-green-600 hover:text-green-800 flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 mr-1">
                                                <path d="m9 12 2 2 4-4"></path>
                                                <path d="M21 12c.552 0 1-.448 1-1V8a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v3c0 .552.448 1 1 1h18z"></path>
                                                <path d="M21 12v4a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2v-4"></path>
                                            </svg>
                                            Approve
                                        </button>
                                        <button wire:click="declineAppointment({{ $item->id }})" class="text-red-600 hover:text-red-800 flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 mr-1">
                                                <path d="m21 21-6-6m0 0L9 9m6 6 6-6M9 9l-6 6"></path>
                                            </svg>
                                            Decline
                                        </button>
                                    @else
                                        <span class="text-sm text-gray-500">{{ ucfirst($item->status) }}</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-8 text-gray-500">
                                No appointments found. Be the first to add an appointment!
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <!-- END: Data List -->
        <!-- BEGIN: Pagination -->
        <x-menu.pagination :paginator="$appointments" :perPageOptions="[10, 25, 35, 50]" />
        <!-- END: Pagination -->
    </div>
    <!-- Delete Appointment Modal -->
    @if($showDeleteAppointmentModal)
    <x-menu.modal 
        :showButton="false" 
        modalId="delete-appointment-modal" 
        title="Delete Appointment" 
        description="This action cannot be undone."
        size="md"
        :isOpen="$showDeleteAppointmentModal">
        <div class="text-center py-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-10 h-10 text-red-500 mx-auto mb-3"><path d="M3 6h18"></path><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path></svg>
            <div class="mt-2 text-sm">Are you sure you want to delete this appointment?</div>
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

    <!-- Approve Appointment Modal -->
    @if($showApproveAppointmentModal)
    <x-menu.modal 
        :showButton="false" 
        modalId="approve-appointment-modal" 
        title="Approve Appointment" 
        description="This action will confirm the appointment."
        size="md"
        :isOpen="$showApproveAppointmentModal">
        <div class="text-center py-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-10 h-10 text-green-500 mx-auto mb-3">
                <path d="m9 12 2 2 4-4"></path>
                <path d="M21 12c.552 0 1-.448 1-1V8a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v3c0 .552.448 1 1 1h18z"></path>
                <path d="M21 12v4a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2v-4"></path>
            </svg>
            <div class="mt-2 text-sm">Are you sure you want to approve this appointment?</div>
        </div>
        <x-slot:footer>
            <button data-tw-dismiss="modal" type="button" wire:click="cancelApprove" class="cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 text-foreground hover:bg-foreground/5 bg-background border-foreground/20 h-10 px-4 py-2 mr-1 w-24">
                Cancel
            </button>
            <button type="button" wire:click="approveConfirmed" @if($isApproving) disabled @endif class="cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 bg-(--color)/20 border-(--color)/60 text-(--color) hover:bg-(--color)/5 [--color:var(--color-success)] h-10 px-4 py-2 w-32 @if($isApproving) opacity-50 cursor-not-allowed @endif">
                @if($isApproving)
                    <div class="flex items-center gap-2">
                        <div class="loader-spinner ![--color:--alpha(var(--color-success)/80%)]"></div>
                        <span>Approving...</span>
                    </div>
                @else
                    <span>Approve</span>
                @endif
            </button>
        </x-slot:footer>
    </x-menu.modal>
    @endif

    <!-- Decline Appointment Modal -->
    @if($showDeclineAppointmentModal)
    <x-menu.modal 
        :showButton="false" 
        modalId="decline-appointment-modal" 
        title="Decline Appointment" 
        description="Please provide a reason for declining this appointment."
        size="md"
        :isOpen="$showDeclineAppointmentModal">
        <form wire:submit.prevent="declineConfirmed">
            <div class="py-2">
                <div class="text-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-10 h-10 text-red-500 mx-auto mb-3">
                        <path d="m21 21-6-6m0 0L9 9m6 6 6-6M9 9l-6 6"></path>
                    </svg>
                    <div class="text-sm">Please provide a reason for declining this appointment:</div>
                </div>
                
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Reason for Decline</label>
                        <textarea 
                            wire:model.defer="declineReason" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            rows="3"
                            placeholder="Enter reason for declining the appointment..."
                            required></textarea>
                        @error('declineReason') 
                            <div class="text-red-500 text-xs mt-1">{{ $message }}</div> 
                        @enderror
                    </div>
                </div>
            </div>
        </form>
        <x-slot:footer>
            <button data-tw-dismiss="modal" type="button" wire:click="cancelDecline" class="cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 text-foreground hover:bg-foreground/5 bg-background border-foreground/20 h-10 px-4 py-2 mr-1 w-24">
                Cancel
            </button>
            <button type="button" wire:click="declineConfirmed" @if($isDeclining) disabled @endif class="cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 bg-(--color)/20 border-(--color)/60 text-(--color) hover:bg-(--color)/5 [--color:var(--color-danger)] h-10 px-4 py-2 w-32 @if($isDeclining) opacity-50 cursor-not-allowed @endif">
                @if($isDeclining)
                    <div class="flex items-center gap-2">
                        <div class="loader-spinner ![--color:--alpha(var(--color-danger)/80%)]"></div>
                        <span>Declining...</span>
                    </div>
                @else
                    <span>Decline</span>
                @endif
            </button>
        </x-slot:footer>
    </x-menu.modal>
    @endif

</div>