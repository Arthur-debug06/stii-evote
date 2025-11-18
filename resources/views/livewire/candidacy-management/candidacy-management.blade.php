@section('title', 'Candidacy Management - Student')

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
    
    @php
        $student = auth()->guard('students')->user();
    @endphp

    <h2 class="mt-10 text-lg font-medium">My Candidacy Applications</h2>
    <div class="mt-5 grid grid-cols-12 gap-6">
        <div class="col-span-12 mt-2 flex flex-wrap items-center sm:flex-nowrap">
            <button wire:click="openAddModal" 
                    @if($activeVoting) disabled @endif
                    class="cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 @if($activeVoting) bg-gray-200 border-gray-300 text-gray-500 cursor-not-allowed @else bg-(--color)/20 border-(--color)/60 text-(--color) hover:bg-(--color)/5 [--color:var(--color-primary)] @endif h-10 px-4 py-2 box mr-2">
                @if($activeVoting)
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1">
                        <circle cx="12" cy="12" r="10"></circle>
                        <path d="M12 6v6l4 2"></path>
                    </svg>
                    Election in Progress
                @else
                    Add New Candidacy
                @endif
            </button>
            
            
            
            @if($activeVoting)
                <div class="ml-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-yellow-600 mr-2">
                            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                            <line x1="12" x2="12" y1="9" y2="13"></line>
                            <line x1="12" x2="12.01" y1="17" y2="17"></line>
                        </svg>
                        <div class="text-sm text-yellow-800">
                            <strong>Election Period Active:</strong> Candidacy applications are temporarily disabled during voting. 
                            Election ends on {{ \Carbon\Carbon::parse($activeVoting->end_datetime)->format('M d, Y \a\t h:i A') }}.
                        </div>
                    </div>
                </div>
            @elseif($upcomingVoting)
                <div class="ml-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-600 mr-2">
                            <path d="M12 2a10 10 0 100 20 10 10 0 000-20z"></path>
                        </svg>
                        <div class="text-sm text-blue-800">
                            <strong>Upcoming Election:</strong> A voting period is scheduled to start on {{ \Carbon\Carbon::parse($upcomingVoting->start_datetime)->format('M d, Y \a\t h:i A') }} and ends on {{ \Carbon\Carbon::parse($upcomingVoting->end_datetime)->format('M d, Y \a\t h:i A') }}.
                        </div>
                    </div>
                </div>
            @endif
            
            <div class="mx-auto hidden opacity-70 md:block">
                @if($candidacies->total() > 0)
                    Showing {{ $candidacies->firstItem() }} to {{ $candidacies->lastItem() }} of {{ $candidacies->total() }} of your applications
                    @if(!empty($search))
                        (filtered from your total applications)
                    @endif
                @else
                    No applications found
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
                        placeholder="Search name, position, school year, status...">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="search" class="lucide lucide-search size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 absolute inset-y-0 right-0 my-auto mr-3 h-4 w-4"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.3-4.3"></path></svg>
                </div>
            </div>
        </div>
        
        <!-- Add New Candidacy Modal -->
        <x-menu.modal 
            :showButton="false" 
            modalId="add-candidacy-modal" 
            title="Add New Candidacy Application" 
            description="Fill in the details to add new candidacy application"
            size="3xl"
            :isOpen="$showAddCandidacyModal">
            
            <form wire:submit.prevent="createCandidacy" class="space-y-6" enctype="multipart/form-data">
                <!-- Certificate of Candidacy Form -->
                <div class="intro-y box p-5">
                    <div class="border border-slate-200/60 dark:border-darkmode-400 rounded-md p-5">
                        <div class="font-medium text-base mb-3">CERTIFICATE OF CANDIDACY FOR SIBUGAY TECH STUDENT GOVERNMENT</div>
                        
                        <div class="font-medium text-base mb-3">INSTRUCTIONS:</div>
                        <div class="text-slate-600 dark:text-slate-500">
                            1. Attach to this certificate, the certificate of no violation record from the DSA, ACADEMIC DEAN, STSG PRESIDENT, STSG MODERATOR<br>
                            2. Attach to this certificate, the evaluate of grades not lower than 85 as general average
                        </div>
                    </div>

                    <div class="mt-5">
                        <div class="font-medium text-base mb-3">I HEREBY ANNOUNCE MY CANDIDACY FOR THE POSITION OF <span class="uppercase font-bold position-text">POSITION</span> IN THE SIBUGAY TECHNICAL INSTITUTE INCORPORATED, LOWER TAWAY, IIL, ZAMBOANGA SIBUGAY in {{ date('Y') }} ELECTION DAY. I HEREBY STATE THE FOLLOWING:</div>
                        
                        <!-- Name Section -->
                        <div class="grid grid-cols-12 gap-6 gap-y-4">
                            <div class="col-span-12">
                                <label class="form-label mb-2">1. NAME</label>
                            </div>
                            <div class="col-span-4">
                                <label class="form-label mb-2">Last Name</label>
                                <input 
                                    wire:model.defer="last_name" 
                                    type="text" 
                                    class="form-control" 
                                    placeholder="Enter Last Name"
                                    required>
                                @error('last_name') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-span-4">
                                <label class="form-label mb-2">Given Name</label>
                                <input 
                                    wire:model.defer="first_name" 
                                    type="text" 
                                    class="form-control" 
                                    placeholder="Enter Given Name"
                                    required>
                                @error('first_name') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-span-4">
                                <label class="form-label mb-2">Middle Name</label>
                                <input 
                                    wire:model.defer="middle_name" 
                                    type="text" 
                                    class="form-control" 
                                    placeholder="Enter Middle Name">
                                @error('middle_name') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <!-- Course/Strand -->
                        <div class="mt-4">
                            <label class="form-label mb-2">2. COURSE/STRAND</label>
                                <input 
                                    type="text" 
                                    class="form-control bg-slate-100" 
                                    value="{{ $student && $student->course ? $student->course->course_name : 'N/A' }}" 
                                    readonly>
                        </div>

                        <!-- Personal Info -->
                        <div class="grid grid-cols-12 gap-6 gap-y-4 mt-4">
                            <div class="col-span-3">
                                <label class="form-label mb-2">3. GENDER</label>
                                <input 
                                    type="text" 
                                    class="form-control bg-slate-100" 
                                    value="{{ $student->gender ?? 'N/A' }}" 
                                    readonly>
                            </div>
                            <div class="col-span-3">
                                <label class="form-label mb-2">4. BIRTHDATE</label>
                                <input 
                                    type="text" 
                                    class="form-control bg-slate-100" 
                                    value="{{ $student && $student->date_of_birth ? \Carbon\Carbon::parse($student->date_of_birth)->format('F j, Y') : 'N/A' }}" 
                                    readonly>
                            </div>
                            <div class="col-span-3">
                                <label class="form-label mb-2">5. AGE</label>
                                <input 
                                    type="text" 
                                    class="form-control bg-slate-100" 
                                    value="{{ $student->age ?? 'N/A' }}" 
                                    readonly>
                            </div>
                            <div class="col-span-3">
                                <label class="form-label mb-2">6. STATUS</label>
                                <input 
                                    type="text" 
                                    class="form-control bg-slate-100" 
                                    value="{{ $student->marital_status ?? 'N/A' }}" 
                                    readonly>
                            </div>
                        </div>

                        <!-- Student Type -->
                        <div class="mt-4">
                            <label class="form-label">7. I am</label>
                            <select 
                                wire:model.defer="is_regular_student" 
                                class="form-select" 
                                required>
                                <option value="">Select Student Type</option>
                                <option value="1">Regular student of SIBUGAY TECHNICAL INSTITUTE INC.</option>
                                <option value="0">Irregular student of SIBUGAY TECHNICAL INSTITUTE INC.</option>
                            </select>
                            @error('is_regular_student') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                        </div>

                        <!-- Position -->
                        <div class="mt-4">
                            <label class="form-label">8. I am deserving for the position/office I seek to be elected to.</label>
                        <select 
                            wire:model.defer="position_id" 
                                class="form-select w-full" 
                                required>
                            <option value="">Select Position</option>
                            @foreach($positions as $position)
                                <option value="{{ $position->id }}">{{ $position->position_name }}</option>
                            @endforeach
                        </select>
                            @error('position_id') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                        </div>

                        <!-- Declarations -->
                        <div class="mt-4">
                            <div class="form-check mt-3">
                                <label class="form-check-label">9. I will abide by the rules and regulations of the school.</label>
                            </div>
                            <div class="form-check mt-3">
                                <label class="form-check-label">10. I AM automatically release from my position if I can disobey the school protocols.</label>
                            </div>
                        </div>

                        <!-- Partylist -->
                        <div class="mt-4">
                            <label class="form-label">11. Partylist</label>
                            <select 
                                wire:model.defer="partylist_id" 
                                class="form-select">
                                <option value="">Select Partylist</option>
                                <option value="">N/A (No Party Affiliation)</option>
                                @foreach($partylists as $partylist)
                                    <option value="{{ $partylist->id }}">{{ $partylist->partylist_name }}</option>
                                @endforeach
                            </select>
                            @error('partylist_id') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                        </div>

                        <!-- Grade Attachment -->
                        <div class="mt-4">
                            <label class="form-label">12. Grade Attachment</label>
                            <input 
                                wire:model.defer="grade_attachment" 
                                type="file" 
                                class="form-control" 
                                accept="image/*,.pdf"
                                required>
                            <div class="text-xs text-gray-500 mt-1">Upload a clear picture or PDF of your evaluated grades (not lower than 85 as general average)</div>
                            @error('grade_attachment') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                        </div>

                        <!-- Doc Number and Series -->
                        <div class="grid grid-cols-12 gap-4 gap-y-3 mt-3">
                            <div class="col-span-6">
                                <label class="form-label">Doc. No.</label>
                            </div>
                            <div class="col-span-6">
                                <label class="form-label">Series of</label>
                            </div>
                        </div>

                        <!-- Prepared by and Signatories Section -->
                        <div class="mt-6">
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                                <!-- Prepared by Section -->
                                <div class="text-left">
                                    <div class="text-sm font-medium mb-2">Prepared by:</div>
                                    <div class="text-sm font-semibold mb-1">
                                        {{ $student->first_name ?? '' }} 
                                        {{ $student->middle_name ?? '' }} 
                                        {{ $student->last_name ?? '' }}
                                    </div>
                                    @php
                                        $fullName = trim(($student->first_name ?? '') . ' ' . ($student->middle_name ?? '') . ' ' . ($student->last_name ?? ''));
                                        $nameLength = strlen($fullName);
                                        $lineWidth = max(80, min(200, $nameLength * 8)); // Minimum 80px, maximum 200px, 8px per character
                                    @endphp
                                    <div class="border-b border-gray-400 mb-2 h-2" style="width: {{ $lineWidth }}px;"></div>
                                    <div class="text-sm font-medium right-5">Student</div>
                                </div>

                                <!-- Signatories Section -->
                                @if(isset($signatories) && $signatories->count() > 0)
                                <div class="text-left">
                                    <div class="grid grid-cols-1 gap-4">
                                        @foreach($signatories as $signatory)
                                        <div class="text-left">
                                            <div class="text-sm font-medium mb-2">{{ $signatory->action_name ?? 'Signatory' }}</div>
                                            <div class="text-sm font-semibold mb-1">{{ $signatory->position ?? 'Name' }}</div>
                                            @if($signatory->academic_suffix)
                                            <div class="border-b border-gray-400 mb-2 h-2 w-32"></div>
                                            <div class="text-xs text-gray-600">{{ $signatory->academic_suffix }}</div>
                                            @endif
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <x-slot:footer>
                <button data-tw-dismiss="modal" type="button" wire:click="$set('showAddCandidacyModal', false)" class="cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 text-foreground hover:bg-foreground/5 bg-background border-foreground/20 h-10 px-4 py-2 mr-1 w-24">
                    Cancel
                </button>
                <button type="button" wire:click="createCandidacy" class="cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 bg-(--color)/20 border-(--color)/60 text-(--color) hover:bg-(--color)/5 [--color:var(--color-primary)] h-10 px-4 py-2 w-24">
                    Submit
                </button>
            </x-slot:footer>
        </x-menu.modal>

        <!-- Edit Candidacy Modal -->
        <x-menu.modal 
            :showButton="false" 
            modalId="edit-candidacy-modal" 
            title="Edit Candidacy Application" 
            description="Update the candidacy application details"
            size="3xl"
            :isOpen="$showEditCandidacyModal">
            
            <form wire:submit.prevent="updateCandidacy" class="space-y-6" enctype="multipart/form-data">
                <!-- Certificate of Candidacy Form -->
                <div class="intro-y box p-5">
                    <div class="border border-slate-200/60 dark:border-darkmode-400 rounded-md p-5">
                        <div class="font-medium text-base mb-3">CERTIFICATE OF CANDIDACY FOR SIBUGAY TECH STUDENT GOVERNMENT</div>
                        
                        <div class="font-medium text-base mb-3">INSTRUCTIONS:</div>
                        <div class="text-slate-600 dark:text-slate-500">
                            1. Attach to this certificate, the certificate of no violation record from the DSA, ACADEMIC DEAN, STSG PRESIDENT, STSG MODERATOR<br>
                            2. Attach to this certificate, the evaluate of grades not lower than 85 as general average
                        </div>
                    </div>

                    <div class="mt-5">
                        <div class="font-medium text-base mb-3">I HEREBY ANNOUNCE MY CANDIDACY FOR THE POSITION OF <span class="uppercase font-bold position-text">POSITION</span> IN THE SIBUGAY TECHNICAL INSTITUTE INCORPORATED, LOWER TAWAY, IIL, ZAMBOANGA SIBUGAY in {{ date('Y') }} ELECTION DAY. I HEREBY STATE THE FOLLOWING:</div>
                        
                        <!-- Name Section -->
                        <div class="grid grid-cols-12 gap-6 gap-y-4">
                            <div class="col-span-12">
                                <label class="form-label mb-2">1. NAME</label>
                            </div>
                            <div class="col-span-4">
                                <label class="form-label mb-2">Last Name</label>
                                <input 
                                    wire:model.defer="last_name" 
                                    type="text" 
                                    class="form-control" 
                                    placeholder="Enter Last Name"
                                    required>
                                @error('last_name') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-span-4">
                                <label class="form-label mb-2">Given Name</label>
                                <input 
                                    wire:model.defer="first_name" 
                                    type="text" 
                                    class="form-control" 
                                    placeholder="Enter Given Name"
                                    required>
                                @error('first_name') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-span-4">
                                <label class="form-label mb-2">Middle Name</label>
                                <input 
                                    wire:model.defer="middle_name" 
                                    type="text" 
                                    class="form-control" 
                                    placeholder="Enter Middle Name">
                                @error('middle_name') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <!-- Course/Strand -->
                        <div class="mt-4">
                            <label class="form-label mb-2">2. COURSE/STRAND</label>
                            <input 
                                type="text" 
                                class="form-control bg-slate-100" 
                                value="{{ $student && $student->course ? $student->course->course_name : 'N/A' }}" 
                                readonly>
                        </div>

                        <!-- Personal Info -->
                        <div class="grid grid-cols-12 gap-4 gap-y-3 mt-3">
                            <div class="col-span-4">
                                <label class="form-label">3. GENDER</label>
                                <input 
                                    type="text" 
                                    class="form-control bg-slate-100" 
                                    value="{{ $student->gender ?? 'N/A' }}" 
                                    readonly>
                            </div>
                            <div class="col-span-4">
                                <label class="form-label">4. BIRTHDATE</label>
                                <input 
                                    type="text" 
                                    class="form-control bg-slate-100" 
                                    value="{{ $student && $student->date_of_birth ? \Carbon\Carbon::parse($student->date_of_birth)->format('F j, Y') : 'N/A' }}" 
                                    readonly>
                            </div>
                            <div class="col-span-2">
                                <label class="form-label">AGE</label>
                                <input 
                                    type="text" 
                                    class="form-control bg-slate-100" 
                                    value="{{ $student->age ?? 'N/A' }}" 
                                    readonly>
                            </div>
                            <div class="col-span-2">
                                <label class="form-label">5. STATUS</label>
                                <input 
                                    type="text" 
                                    class="form-control bg-slate-100" 
                                    value="{{ $student->marital_status ?? 'N/A' }}" 
                                    readonly>
                            </div>
                        </div>

                        <!-- Student Type -->
                        <div class="mt-4">
                            <label class="form-label">7. I am</label>
                            <select 
                                wire:model.defer="is_regular_student" 
                                class="form-select" 
                                required>
                                <option value="">Select Student Type</option>
                                <option value="1">Regular student of SIBUGAY TECHNICAL INSTITUTE INC.</option>
                                <option value="0">Irregular student of SIBUGAY TECHNICAL INSTITUTE INC.</option>
                            </select>
                            @error('is_regular_student') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                    </div>

                        <!-- Position -->
                        <div class="mt-4">
                            <label class="form-label">8. I am deserving for the position/office I seek to be elected to.</label>
                        <select 
                            wire:model.defer="position_id" 
                                class="form-select w-full" 
                                required>
                            <option value="">Select Position</option>
                            @foreach($positions as $position)
                                <option value="{{ $position->id }}">{{ $position->position_name }}</option>
                            @endforeach
                        </select>
                            @error('position_id') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                        </div>

                        <!-- Declarations -->
                        <div class="mt-4">
                            <div class="form-check mt-3">
                                <label class="form-check-label">9. I will abide by the rules and regulations of the school.</label>
                            </div>
                            <div class="form-check mt-3">
                                <label class="form-check-label">10. I AM automatically release from my position if I can disobey the school protocols.</label>
                            </div>
                        </div>

                        <!-- Partylist -->
                        <div class="mt-4">
                            <label class="form-label">11. Partylist</label>
                            <select 
                                wire:model.defer="partylist_id" 
                                class="form-select">
                                <option value="">Select Partylist</option>
                                <option value="">N/A (No Party Affiliation)</option>
                                @foreach($partylists as $partylist)
                                    <option value="{{ $partylist->id }}">{{ $partylist->partylist_name }}</option>
                                @endforeach
                            </select>
                            @error('partylist_id') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                        </div>

                        <!-- Grade Attachment -->
                        <div class="mt-4">
                            <label class="form-label">12. Grade Attachment</label>
                            <input 
                                wire:model.defer="grade_attachment" 
                                type="file" 
                                class="form-control" 
                                accept="image/*,.pdf"
                                required>
                            <div class="text-xs text-gray-500 mt-1">Upload a clear picture or PDF of your evaluated grades (not lower than 85 as general average)</div>
                            @error('grade_attachment') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                        </div>

                        <!-- Doc Number and Series -->
                        <div class="grid grid-cols-12 gap-4 gap-y-3 mt-3">
                            <div class="col-span-6">
                                <label class="form-label">Doc. No.</label>
                                <input type="text" class="form-control bg-slate-100" value="STII-{{ str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT) }}" readonly>
                            </div>
                            <div class="col-span-6">
                                <label class="form-label">Series of</label>
                                <input type="text" class="form-control bg-slate-100" value="{{ date('Y') }}" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <x-slot:footer>
                <button data-tw-dismiss="modal" type="button" wire:click="$set('showEditCandidacyModal', false)" class="cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 text-foreground hover:bg-foreground/5 bg-background border-foreground/20 h-10 px-4 py-2 mr-1 w-24">
                    Cancel
                </button>
                <button type="button" wire:click="updateCandidacy" class="cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 bg-(--color)/20 border-(--color)/60 text-(--color) hover:bg-(--color)/5 [--color:var(--color-primary)] h-10 px-4 py-2 w-24">
                    Update
                </button>
            </x-slot:footer>
        </x-menu.modal>

        <!-- View Candidacy Modal -->
        <x-menu.modal 
            :showButton="false" 
            modalId="view-candidacy-modal" 
            title="View Candidacy Application" 
            description="View candidacy application details"
            size="3xl"
            :isOpen="$showViewCandidacyModal">
            
            @if($viewingCandidacy)
            <div class="space-y-6">
                <!-- Certificate of Candidacy Form -->
                <div class="intro-y box p-5">
                    <div class="border border-slate-200/60 dark:border-darkmode-400 rounded-md p-5">
                        <div class="font-medium text-base mb-3">CERTIFICATE OF CANDIDACY FOR SIBUGAY TECH STUDENT GOVERNMENT</div>
                        
                        <div class="font-medium text-base mb-3">INSTRUCTIONS:</div>
                        <div class="text-slate-600 dark:text-slate-500">
                            1. Attach to this certificate, the certificate of no violation record from the DSA, ACADEMIC DEAN, STSG PRESIDENT, STSG MODERATOR<br>
                            2. Attach to this certificate, the evaluate of grades not lower than 85 as general average
                        </div>
                    </div>

                    <div class="mt-5">
                        <div class="font-medium text-base mb-3">I HEREBY ANNOUNCE MY CANDIDACY FOR THE POSITION OF <span class="uppercase font-bold position-text">{{ $viewingCandidacy->position->position_name ?? 'POSITION' }}</span> IN THE SIBUGAY TECHNICAL INSTITUTE INCORPORATED, LOWER TAWAY, IIL, ZAMBOANGA SIBUGAY in {{ $viewingCandidacy->created_at->format('Y') }} ELECTION DAY. I HEREBY STATE THE FOLLOWING:</div>
                        
                        <!-- Name Section -->
                        <div class="grid grid-cols-12 gap-6 gap-y-4">
                            <div class="col-span-12">
                                <label class="form-label mb-2">1. NAME</label>
                            </div>
                            <div class="col-span-4">
                                <label class="form-label mb-2">Last Name</label>
                                <input 
                                    type="text" 
                                    class="form-control bg-slate-100" 
                                    value="{{ $viewingCandidacy->students->last_name ?? '' }}" 
                                    readonly>
                            </div>
                            <div class="col-span-4">
                                <label class="form-label mb-2">Given Name</label>
                                <input 
                                    type="text" 
                                    class="form-control bg-slate-100" 
                                    value="{{ $viewingCandidacy->students->first_name ?? '' }}" 
                                    readonly>
                            </div>
                            <div class="col-span-4">
                                <label class="form-label mb-2">Middle Name</label>
                                <input 
                                    type="text" 
                                    class="form-control bg-slate-100" 
                                    value="{{ $viewingCandidacy->students->middle_name ?? '' }}" 
                                    readonly>
                            </div>
                        </div>

                        <!-- Course/Strand -->
                        <div class="mt-4">
                            <label class="form-label mb-2">2. COURSE/STRAND</label>
                            <input 
                                type="text" 
                                class="form-control bg-slate-100" 
                                value="{{ $viewingCandidacy->students->course->course_name ?? 'N/A' }}" 
                                readonly>
                        </div>

                        <!-- Personal Info -->
                        <div class="grid grid-cols-12 gap-6 gap-y-4 mt-4">
                            <div class="col-span-3">
                                <label class="form-label mb-2">3. GENDER</label>
                                <input 
                                    type="text" 
                                    class="form-control bg-slate-100" 
                                    value="{{ $viewingCandidacy->students->gender ?? 'N/A' }}" 
                                    readonly>
                            </div>
                            <div class="col-span-3">
                                <label class="form-label mb-2">4. BIRTHDATE</label>
                                <input 
                                    type="text" 
                                    class="form-control bg-slate-100" 
                                    value="{{ $viewingCandidacy->students->date_of_birth ? \Carbon\Carbon::parse($viewingCandidacy->students->date_of_birth)->format('F j, Y') : 'N/A' }}" 
                                    readonly>
                            </div>
                            <div class="col-span-3">
                                <label class="form-label mb-2">5. AGE</label>
                                <input 
                                    type="text" 
                                    class="form-control bg-slate-100" 
                                    value="{{ $viewingCandidacy->students->age ?? 'N/A' }}" 
                                    readonly>
                            </div>
                            <div class="col-span-3">
                                <label class="form-label mb-2">6. STATUS</label>
                                <input 
                                    type="text" 
                                    class="form-control bg-slate-100" 
                                    value="{{ $viewingCandidacy->students->marital_status ?? 'N/A' }}" 
                                    readonly>
                            </div>
                        </div>

                        <!-- Student Type -->
                        <div class="mt-4">
                            <label class="form-label">7. I am</label>
                            <input 
                                type="text" 
                                class="form-control bg-slate-100" 
                                value="{{ $viewingCandidacy->is_regular_student ? 'Regular student of SIBUGAY TECHNICAL INSTITUTE INC.' : 'Irregular student of SIBUGAY TECHNICAL INSTITUTE INC.' }}" 
                                readonly>
                        </div>

                        <!-- Position -->
                        <div class="mt-4">
                            <label class="form-label">8. I am deserving for the position/office I seek to be elected to.</label>
                            <input 
                                type="text" 
                                class="form-control bg-slate-100" 
                                value="{{ $viewingCandidacy->position->position_name ?? 'N/A' }}" 
                                readonly>
                        </div>

                        <!-- Declarations -->
                        <div class="mt-4">
                            <div class="form-check mt-3">
                                <label class="form-check-label">9. I will abide by the rules and regulations of the school.</label>
                            </div>
                            <div class="form-check mt-3">
                                <label class="form-check-label">10. I AM automatically release from my position if I can disobey the school protocols.</label>
                            </div>
                        </div>

                        <!-- Partylist -->
                        <div class="mt-4">
                            <label class="form-label">11. Partylist</label>
                            <input 
                                type="text" 
                                class="form-control bg-slate-100" 
                                value="{{ $viewingCandidacy->partylist ? $viewingCandidacy->partylist->partylist_name : 'N/A (No Party Affiliation)' }}" 
                                readonly>
                        </div>

                        <!-- Grade Attachment -->
                        <div class="mt-4">
                            <label class="form-label">12. Grade Attachment</label>
                            @if($viewingCandidacy->grade_attachment)
                                <div class="mt-2">
                                    <a href="{{ asset('storage/' . $viewingCandidacy->grade_attachment) }}" target="_blank" class="text-blue-600 hover:text-blue-800 underline">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline w-4 h-4 mr-1">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                            <polyline points="14,2 14,8 20,8"></polyline>
                                            <line x1="16" y1="13" x2="8" y2="13"></line>
                                            <line x1="16" y1="17" x2="8" y2="17"></line>
                                            <polyline points="10,9 9,9 8,9"></polyline>
                                        </svg>
                                        View Grade Attachment
                                    </a>
                                </div>
                            @else
                                <input 
                                    type="text" 
                                    class="form-control bg-slate-100" 
                                    value="No attachment uploaded" 
                                    readonly>
                            @endif
                        </div>

                        <!-- Doc Number and Series -->
                        <div class="grid grid-cols-12 gap-4 gap-y-3 mt-3">
                            <div class="col-span-6">
                                <label class="form-label">Doc. No.</label>
                                <input type="text" class="form-control bg-slate-100" value="STII-{{ str_pad($viewingCandidacy->id, 4, '0', STR_PAD_LEFT) }}" readonly>
                            </div>
                            <div class="col-span-6">
                                <label class="form-label">Series of</label>
                                <input type="text" class="form-control bg-slate-100" value="{{ $viewingCandidacy->created_at->format('Y') }}" readonly>
                            </div>
                        </div>

                        <!-- Prepared by and Signatories Section -->
                        <div class="mt-6">
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                                <!-- Prepared by Section -->
                                <div class="text-left">
                                    <div class="text-sm font-medium mb-2">Prepared by:</div>
                                    <div class="text-sm font-semibold mb-1">
                                        {{ $viewingCandidacy->students->first_name ?? '' }} 
                                        {{ $viewingCandidacy->students->middle_name ?? '' }} 
                                        {{ $viewingCandidacy->students->last_name ?? '' }}
                                    </div>
                                    @php
                                        $fullName = trim(($viewingCandidacy->students->first_name ?? '') . ' ' . ($viewingCandidacy->students->middle_name ?? '') . ' ' . ($viewingCandidacy->students->last_name ?? ''));
                                        $nameLength = strlen($fullName);
                                        $lineWidth = max(80, min(200, $nameLength * 8)); // Minimum 80px, maximum 200px, 8px per character
                                    @endphp
                                    <div class="border-b border-gray-400 mb-2 h-2" style="width: {{ $lineWidth }}px;"></div>
                                    <div class="text-sm font-medium right-5">Student</div>
                                </div>

                                <!-- Signatories Section -->
                                @if(isset($signatories) && $signatories->count() > 0)
                                <div class="text-left">
                                    <div class="grid grid-cols-1 gap-4">
                                        @foreach($signatories as $signatory)
                                        <div class="text-left">
                                            <div class="text-sm font-medium mb-2">{{ $signatory->action_name ?? 'Signatory' }}</div>
                                            <div class="text-sm font-semibold mb-1">{{ $signatory->position ?? 'Name' }}</div>
                                            @if($signatory->academic_suffix)
                                            <div class="border-b border-gray-400 mb-2 h-2 w-32"></div>
                                            <div class="text-xs text-gray-600">{{ $signatory->academic_suffix }}</div>
                                            @endif
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <x-slot:footer>
                <button data-tw-dismiss="modal" type="button" wire:click="$set('showViewCandidacyModal', false)" class="cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 text-foreground hover:bg-foreground/5 bg-background border-foreground/20 h-10 px-4 py-2 w-24">
                    Close
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
                                STUDENT NAME
                            </th>
                            <th class="h-12 px-4 align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0 text-center">
                                POSITION
                            </th>
                            <th class="h-12 px-4 align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0 text-center">
                                SCHOOL YEAR
                            </th>
                            <th class="h-12 px-4 align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0 text-center">
                                STUDENT TYPE
                            </th>
                            <th class="h-12 px-4 align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0 text-center">
                                STATUS
                            </th>
                            <th class="h-12 px-4 align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0 text-center">
                                APPLIED DATE
                            </th>
                            <th class="h-12 px-4 align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0 text-center">
                                ACTIONS
                            </th>
                        </tr>
                    </thead>
                    <tbody class="[&amp;_tr:last-child]:border-0">
                        @forelse($candidacies as $item)
                        <tr class="transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted border-b-0">
                            <td class="shadow-[3px_3px_5px_#0000000b] first:rounded-l-xl last:rounded-r-xl box rounded-none p-4 align-middle [&amp;:has([role=checkbox])]:pr-0 border-y border-foreground/10 bg-background first:border-l last:border-r">
                                <div class="font-medium">{{ $item->students->first_name ?? '' }} {{ $item->students->last_name ?? '' }}</div>
                            </td>
                            <td class="shadow-[3px_3px_5px_#0000000b] first:rounded-l-xl last:rounded-r-xl box rounded-none p-4 align-middle [&amp;:has([role=checkbox])]:pr-0 border-y border-foreground/10 bg-background first:border-l last:border-r">
                                <div class="text-center">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        {{ $item->position->position_name ?? 'N/A' }}
                                    </span>
                                </div>
                            </td>
                            <td class="shadow-[3px_3px_5px_#0000000b] first:rounded-l-xl last:rounded-r-xl box rounded-none p-4 align-middle [&amp;:has([role=checkbox])]:pr-0 border-y border-foreground/10 bg-background first:border-l last:border-r">
                                <div class="text-center">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        {{ $item->school_year_and_semester->school_year ?? 'N/A' }} - {{ $item->school_year_and_semester->semester ?? 'N/A' }}
                                    </span>
                                </div>
                            </td>
                            <td class="shadow-[3px_3px_5px_#0000000b] first:rounded-l-xl last:rounded-r-xl box rounded-none p-4 align-middle [&amp;:has([role=checkbox])]:pr-0 border-y border-foreground/10 bg-background first:border-l last:border-r">
                                <div class="flex items-center justify-center">
                                    @if($item->is_regular_student)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            Regular
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                            Irregular
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="shadow-[3px_3px_5px_#0000000b] first:rounded-l-xl last:rounded-r-xl box rounded-none p-4 align-middle [&amp;:has([role=checkbox])]:pr-0 border-y border-foreground/10 bg-background first:border-l last:border-r">
                                <div class="flex items-center justify-center">
                                    @if($item->status === 'approved')
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                            Approved
                                        </span>
                                    @elseif($item->status === 'rejected')
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                            </svg>
                                            Rejected
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                            </svg>
                                            Pending
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="shadow-[3px_3px_5px_#0000000b] first:rounded-l-xl last:rounded-r-xl box rounded-none p-4 align-middle [&amp;:has([role=checkbox])]:pr-0 border-y border-foreground/10 bg-background first:border-l last:border-r text-center">
                                <div class="text-sm">{{ $item->created_at->format('M d, Y') }}</div>
                                <div class="text-xs opacity-70">{{ optional($item->created_at)->format('h:i A') ?? '' }}</div>
                            </td>
                            <td class="shadow-[3px_3px_5px_#0000000b] first:rounded-l-xl last:rounded-r-xl box rounded-none p-4 align-middle [&amp;:has([role=checkbox])]:pr-0 border-y border-foreground/10 bg-background first:border-l last:border-r">
                                <div class="flex items-center justify-center">
                                    @if($item->status === 'approved')
                                        <!-- View button for approved status -->
                                        <button wire:click="viewCandidacy({{ $item->id }})" class="mr-2 flex items-center text-green-600 hover:text-green-800">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 mr-1">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                <circle cx="12" cy="12" r="3"></circle>
                                            </svg>
                                            View
                                        </button>
                                        <!-- Disabled buttons for approved status -->
                                        <button disabled class="mr-2 flex items-center text-gray-400 cursor-not-allowed opacity-50">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 mr-1">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                            </svg>
                                            Edit
                                        </button>
                                        <button disabled class="text-gray-400 cursor-not-allowed opacity-50 flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 mr-1">
                                                <path d="M3 6h18"></path>
                                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                            </svg>
                                            Delete
                                        </button>
                                    @else
                                        <!-- Active buttons for pending/rejected status -->
                                        <button wire:click="viewCandidacy({{ $item->id }})" class="mr-2 flex items-center text-green-600 hover:text-green-800">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 mr-1">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                <circle cx="12" cy="12" r="3"></circle>
                                            </svg>
                                            View
                                        </button>
                                        <button wire:click="editCandidacy({{ $item->id }})" class="mr-2 flex items-center text-blue-600 hover:text-blue-800">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 mr-1">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                            </svg>
                                            Edit
                                        </button>
                                        <button wire:click="deleteCandidacy({{ $item->id }})" class="text-red-600 hover:text-red-800 flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 mr-1">
                                                <path d="M3 6h18"></path>
                                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                            </svg>
                                            Delete
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-8 text-gray-500">
                                You haven't submitted any candidacy applications yet. Click "Add New Candidacy" to get started!
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <!-- END: Data List -->
        <!-- BEGIN: Pagination -->
        <x-menu.pagination :paginator="$candidacies" :perPageOptions="[10, 25, 35, 50]" />
        <!-- END: Pagination -->
    </div>
    <!-- Delete Candidacy Modal -->
    @if($showDeleteCandidacyModal)
    <x-menu.modal 
        :showButton="false" 
        modalId="delete-candidacy-modal" 
        title="Delete Candidacy Application" 
        description="This action cannot be undone."
        size="md"
        :isOpen="$showDeleteCandidacyModal">
        <div class="text-center py-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-10 h-10 text-red-500 mx-auto mb-3"><path d="M3 6h18"></path><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path></svg>
            <div class="mt-2 text-sm">Are you sure you want to delete this candidacy application?</div>
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