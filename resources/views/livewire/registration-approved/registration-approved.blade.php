<div>
    <!-- Toast Notification Template -->
    <x-menu.notification-toast seconds="10" layout="compact" animated="true" />
    
    <h2 class="mt-10 text-lg font-medium">Approved Student Registrations</h2>
    <div class="mt-5 grid grid-cols-12 gap-6">
        <div class="col-span-12 mt-2 flex flex-wrap items-center sm:flex-nowrap">
            
            <div class="mx-auto hidden opacity-70 md:block">
                @if($students->total() > 0)
                    Showing {{ $students->firstItem() }} to {{ $students->lastItem() }} of {{ $students->total() }} approved registrations
                    @if(!empty($search))
                        (filtered from {{ \App\Models\students::where('status', 'active')->count() }} total approved entries)
                    @endif
                @else
                    No approved registrations found
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
                        placeholder="Search student ID, name, email...">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="search" class="lucide lucide-search size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 absolute inset-y-0 right-0 my-auto mr-3 h-4 w-4"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.3-4.3"></path></svg>
                </div>
            </div>
        </div>
        
        <!-- View Student Details Modal -->
        <x-menu.modal 
            :showButton="false" 
            modalId="view-student-modal" 
            title="Approved Student Registration Details" 
            description="View approved student information and approval details"
            size="3xl"
            :isOpen="$showViewModal">
            
            <div class="max-h-[60vh] overflow-y-auto">
                <div class="box relative p-5 before:absolute before:inset-0 before:mx-3 before:-mb-3 before:border before:border-foreground/10 before:bg-background/30 before:shadow-[0px_3px_5px_#0000000b] before:z-[-1] before:rounded-xl after:absolute after:inset-0 after:border after:border-foreground/10 after:bg-background after:shadow-[0px_3px_5px_#0000000b] after:rounded-xl after:z-[-1] after:backdrop-blur-md mt-5">
                <div class="bg-primary/[.01] border-primary/20 relative overflow-hidden rounded-xl border text-center sm:text-left before:bg-noise before:absolute before:inset-0 before:opacity-30 after:bg-accent after:absolute after:inset-0 after:opacity-30 after:blur-2xl">
                    <div class="px-5 py-6 sm:px-20 sm:py-10">
                        <div class="text-primary text-lg font-semibold">STUDENT REGISTRATION</div>
                        <div class="mt-1 text-sm">
                            Student ID <span class="font-medium">#{{ $student_id }}</span>
                        </div>
                        <div class="mt-1 text-sm">{{ $created_at ? \Carbon\Carbon::parse($created_at)->format('M d, Y') : 'N/A' }}</div>
                    </div>
                    <div class="flex flex-col px-5 pb-6 pt-6 sm:px-20 sm:pb-10 sm:pt-10 lg:flex-row">
                        <div>
                            <div class="text-sm opacity-70">Student Details</div>
                            <div class="text-primary mt-1 text-base font-medium">
                                {{ $first_name }} {{ $middle_name }} {{ $last_name }} {{ $suffix }}
                            </div>
                            <div class="mt-1 text-sm">{{ $email }}</div>
                            <div class="mt-1 text-sm">{{ $address }}</div>
                            <div class="mt-1 text-sm">{{ ucfirst($gender) }} • Age: {{ $age }}</div>
                        </div>
                        <div class="mt-6 lg:ml-auto lg:mt-0 lg:text-right">
                            <div class="text-sm opacity-70">Academic Information</div>
                            <div class="text-primary mt-1 text-base font-medium">
                                {{ $selectedStudent->course->course_name ?? 'N/A' }}
                            </div>
                            <div class="mt-1 text-sm">{{ $selectedStudent->department->department_name ?? 'N/A' }}</div>
                            <div class="mt-1 text-sm">{{ $selectedStudent->school_year_and_semester->school_year ?? 'N/A' }} - {{ $selectedStudent->school_year_and_semester->semester ?? 'N/A' }}</div>
                    </div>
                    </div>
                </div>
                <div class="px-5 py-6 sm:px-16 sm:py-10">
                    <div class="relative w-full overflow-auto">
                        <table class="w-full caption-bottom">
                            <thead class="[&amp;_tr]:border-b">
                                <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                                    <th class="border-y border-foreground/10 first:border-l last:border-r bg-background h-8 px-4 text-left align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0 whitespace-nowrap border-none pb-3 text-sm">
                                        INFORMATION
                                    </th>
                                    <th class="border-y border-foreground/10 first:border-l last:border-r bg-background h-8 px-4 align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0 whitespace-nowrap border-none pb-3 text-sm text-right">
                                        DETAILS
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="[&amp;_tr:last-child]:border-0">
                                <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                                    <td class="box rounded-none p-3 align-middle [&amp;:has([role=checkbox])]:pr-0 border-y border-foreground/10 bg-background first:border-l last:border-r">
                                        <div class="whitespace-nowrap font-medium text-sm">
                                            Date of Birth
                                        </div>
                                        <div class="mt-0.5 whitespace-nowrap text-xs opacity-70">
                                            Personal Information
                                        </div>
                                    </td>
                                    <td class="box rounded-none p-3 align-middle [&amp;:has([role=checkbox])]:pr-0 border-y border-foreground/10 bg-background first:border-l last:border-r text-right text-sm">
                                        {{ $date_of_birth ? \Carbon\Carbon::parse($date_of_birth)->format('M d, Y') : 'N/A' }}
                                    </td>
                                </tr>
                                <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                                    <td class="box rounded-none p-3 align-middle [&amp;:has([role=checkbox])]:pr-0 border-y border-foreground/10 bg-background first:border-l last:border-r">
                                        <div class="whitespace-nowrap font-medium text-sm">
                                            Registration Status
                                        </div>
                                        <div class="mt-0.5 whitespace-nowrap text-xs opacity-70">
                                            Current Status
                                        </div>
                                    </td>
                                    <td class="box rounded-none p-3 align-middle [&amp;:has([role=checkbox])]:pr-0 border-y border-foreground/10 bg-background first:border-l last:border-r text-right">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Registration Approved
                                        </span>
                                    </td>
                                </tr>
                                <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                                    <td class="box rounded-none p-3 align-middle [&amp;:has([role=checkbox])]:pr-0 border-y border-foreground/10 bg-background first:border-l last:border-r">
                                        <div class="whitespace-nowrap font-medium text-sm">
                                            Approval Details
                                        </div>
                                        <div class="mt-0.5 whitespace-nowrap text-xs opacity-70">
                                            Registration approval information
                                        </div>
                                    </td>
                                    <td class="box rounded-none p-3 align-middle [&amp;:has([role=checkbox])]:pr-0 border-y border-foreground/10 bg-background first:border-l last:border-r text-right text-sm">
                                        <div class="text-left max-w-xs">
                                            {{ $approval_reason }}
                                        </div>
                                    </td>
                                </tr>
                                <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                                    <td class="box rounded-none p-3 align-middle [&amp;:has([role=checkbox])]:pr-0 border-y border-foreground/10 bg-background first:border-l last:border-r">
                                        <div class="whitespace-nowrap font-medium text-sm">
                                            Profile Image
                                        </div>
                                        <div class="mt-0.5 whitespace-nowrap text-xs opacity-70">
                                            Student Photo
                                        </div>
                                    </td>
                                    <td class="box rounded-none p-3 align-middle [&amp;:has([role=checkbox])]:pr-0 border-y border-foreground/10 bg-background first:border-l last:border-r text-right">
                                        @if($profile_image_base64)
                                            <a href="{{ route('attachments.student-image', ['student' => $selectedStudent->id ?? $student_id, 'type' => 'profile']) }}" target="_blank">
                                                <img src="{{ $profile_image_base64 }}" alt="Profile" class="w-10 h-10 object-cover rounded-md mx-auto cursor-pointer">
                                            </a>
                                        @else
                                            <div class="w-10 h-10 bg-gray-200 rounded-md flex items-center justify-center text-gray-500 mx-auto text-xs">No Image</div>
                                        @endif
                                    </td>
                                </tr>
                                <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                                    <td class="box rounded-none p-3 align-middle [&amp;:has([role=checkbox])]:pr-0 border-y border-foreground/10 bg-background first:border-l last:border-r">
                                        <div class="whitespace-nowrap font-medium text-sm">
                                            Student ID Image
                    </div>
                                        <div class="mt-0.5 whitespace-nowrap text-xs opacity-70">
                                            ID Verification
                    </div>
                                    </td>
                                    <td class="box rounded-none p-3 align-middle [&amp;:has([role=checkbox])]:pr-0 border-y border-foreground/10 bg-background first:border-l last:border-r text-right">
                                        @if($student_id_image_base64)
                                            <a href="{{ route('attachments.student-image', ['student' => $selectedStudent->id ?? $student_id, 'type' => 'id']) }}" target="_blank">
                                                <img src="{{ $student_id_image_base64 }}" alt="Student ID" class="w-10 h-10 object-cover rounded-md mx-auto cursor-pointer">
                                            </a>
                                        @else
                                            <div class="w-10 h-10 bg-gray-200 rounded-md flex items-center justify-center text-gray-500 mx-auto text-xs">No Image</div>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            <x-slot:footer>
                    <button data-tw-dismiss="modal" type="button" wire:click="cancelAction" class="cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 text-foreground hover:bg-foreground/5 bg-background border-foreground/20 h-10 px-4 py-2 mr-1 w-24">
                    Cancel
                </button>
            </x-slot:footer>
                
            </div>
            </div>

        </x-menu.modal>
        <!-- BEGIN: Data List -->
        <div class="col-span-12 overflow-auto lg:overflow-visible">
            <div class="relative w-full overflow-auto">
                <table class="w-full caption-bottom border-separate border-spacing-y-[10px] -mt-2">
                    <thead class="[&amp;_tr]:border-b-0 [&amp;_tr_th]:h-10">
                        <tr class="transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted border-b-0">
                            <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0">
                                PHOTO
                            </th>
                            <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0">
                                STUDENT ID
                            </th>
                            <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0">
                                STUDENT NAME
                            </th>
                            <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0">
                                EMAIL
                            </th>
                            <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0">
                                COURSE
                            </th>
                            <th class="h-12 px-4 align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0 text-center">
                                REGISTRATION DATE
                            </th>
                            <th class="h-12 px-4 align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0 text-center">
                                ACTIONS
                            </th>
                        </tr>
                    </thead>
                    <tbody class="[&amp;_tr:last-child]:border-0">
                        @forelse($students as $student)
                        <tr class="transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted border-b-0">
                            <td class="shadow-[3px_3px_5px_#0000000b] first:rounded-l-xl last:rounded-r-xl box rounded-none p-4 align-middle [&amp;:has([role=checkbox])]:pr-0 border-y border-foreground/10 bg-background first:border-l last:border-r">
                                @php
                                    $profileImageBase64 = null;
                                    if ($student->profile_image) {
                                        try {
                                            $possiblePaths = [
                                                $student->profile_image,
                                                'public/' . $student->profile_image,
                                                'private/public/' . $student->profile_image,
                                            ];
                                            
                                            foreach ($possiblePaths as $path) {
                                                if (Storage::disk('public')->exists($path)) {
                                                    $imageData = Storage::disk('public')->get($path);
                                                    $mimeType = Storage::disk('public')->mimeType($path);
                                                    $profileImageBase64 = 'data:' . $mimeType . ';base64,' . base64_encode($imageData);
                                                    break;
                                                }
                                            }
                                            
                                            if (!$profileImageBase64) {
                                                foreach ($possiblePaths as $path) {
                                                    if (Storage::disk('local')->exists($path)) {
                                                        $imageData = Storage::disk('local')->get($path);
                                                        $mimeType = Storage::disk('local')->mimeType($path);
                                                        $profileImageBase64 = 'data:' . $mimeType . ';base64,' . base64_encode($imageData);
                                                        break;
                                                    }
                                                }
                                            }
                                        } catch (\Exception $e) {
                                            $profileImageBase64 = null;
                                        }
                                    }
                                @endphp
                                
                                @if($profileImageBase64)
                                    <img src="{{ $profileImageBase64 }}" alt="Profile" class="w-12 h-12 object-cover rounded-full mx-auto cursor-pointer hover:opacity-80 transition-opacity" wire:click="openPhotoSlider({{ $student->id }})">
                                @else
                                    <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center text-gray-500 mx-auto cursor-pointer hover:opacity-80 transition-opacity" wire:click="openPhotoSlider({{ $student->id }})">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6">
                                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="12" cy="7" r="4"></circle>
                                        </svg>
                                    </div>
                                @endif
                            </td>
                            <td class="shadow-[3px_3px_5px_#0000000b] first:rounded-l-xl last:rounded-r-xl box rounded-none p-4 align-middle [&amp;:has([role=checkbox])]:pr-0 border-y border-foreground/10 bg-background first:border-l last:border-r">
                                <div class="font-medium">{{ $student->student_id }}</div>
                            </td>
                            <td class="shadow-[3px_3px_5px_#0000000b] first:rounded-l-xl last:rounded-r-xl box rounded-none p-4 align-middle [&amp;:has([role=checkbox])]:pr-0 border-y border-foreground/10 bg-background first:border-l last:border-r">
                                <div class="font-medium">
                                    {{ $student->first_name }} {{ $student->middle_name }} {{ $student->last_name }} {{ $student->suffix }}
                                </div>
                            </td>
                            <td class="shadow-[3px_3px_5px_#0000000b] first:rounded-l-xl last:rounded-r-xl box rounded-none p-4 align-middle [&amp;:has([role=checkbox])]:pr-0 border-y border-foreground/10 bg-background first:border-l last:border-r">
                                <div class="text-sm">{{ $student->email }}</div>
                            </td>
                            <td class="shadow-[3px_3px_5px_#0000000b] first:rounded-l-xl last:rounded-r-xl box rounded-none p-4 align-middle [&amp;:has([role=checkbox])]:pr-0 border-y border-foreground/10 bg-background first:border-l last:border-r">
                                <div class="text-sm">{{ $student->course->course_name ?? 'N/A' }}</div>
                            </td>
                            <td class="shadow-[3px_3px_5px_#0000000b] first:rounded-l-xl last:rounded-r-xl box rounded-none p-4 align-middle [&amp;:has([role=checkbox])]:pr-0 border-y border-foreground/10 bg-background first:border-l last:border-r text-center">
                                <div class="text-sm">{{ optional($student->created_at)->format('M d, Y') ?? 'N/A' }}</div>
                                <div class="text-xs opacity-70">{{ optional($student->created_at)->format('h:i A') ?? '' }}</div>
                            </td>
                            <td class="shadow-[3px_3px_5px_#0000000b] first:rounded-l-xl last:rounded-r-xl box rounded-none p-4 align-middle [&amp;:has([role=checkbox])]:pr-0 border-y border-foreground/10 bg-background first:border-l last:border-r">
                                <div class="flex items-center justify-center gap-2">
                                    <button wire:click="viewStudent({{ $student->id }})" class="flex items-center text-blue-600 hover:text-blue-800">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 mr-1">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                            <circle cx="12" cy="12" r="3"></circle>
                                        </svg>
                                        View Details
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-8 text-gray-500">
                                No approved registration requests found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <!-- END: Data List -->
        <!-- BEGIN: Pagination -->
        <x-menu.pagination :paginator="$students" :perPageOptions="[10, 25, 35, 50]" />
        <!-- END: Pagination -->
    </div>

    <!-- BEGIN: Photo Slider Modal -->
    @if($showPhotoSlider)
    <x-menu.modal size="3xl" backdrop :isOpen="$showPhotoSlider" title="Student Photos" :showButton="false">
        <div class="flex justify-center">
            <div class="w-full">
                <!-- Debug Info -->
                <!-- <div class="mb-4 p-2 bg-gray-100 rounded text-xs">
                    <div>Profile Image: {{ $profile_image_base64 ? 'Found' : 'Not Found' }}</div>
                    <div>Student ID Image: {{ $student_id_image_base64 ? 'Found' : 'Not Found' }}</div>
                </div>
                 -->
                <div x-data="{ 
                    init() {
                        this.$nextTick(() => {
                            if (typeof tns !== 'undefined') {
                                this.slider = tns({
                                    container: this.$el,
                                    mouseDrag: true,
                                    autoplay: false,
                                    controls: true,
                                    nav: true,
                                    responsive: {
                                        600: {
                                            items: 2
                                        }
                                    }
                                });
                            }
                        });
                    }
                }" class="photo-slider">
                    @if($profile_image_base64)
                    <div class="h-56 px-2">
                        <div class="image-fit h-full overflow-hidden rounded-xl">
                            <img src="{{ $profile_image_base64 }}" alt="Profile Image" class="w-full h-full object-cover">
                        </div>
                    </div>
                    @endif
                    
                    @if($student_id_image_base64)
                    <div class="h-56 px-2">
                        <div class="image-fit h-full overflow-hidden rounded-xl">
                            <img src="{{ $student_id_image_base64 }}" alt="Student ID Image" class="w-full h-full object-cover">
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        <x-slot name="footer">
            <div class="flex justify-end gap-2">
                <button data-tw-dismiss="modal" wire:click="closePhotoSlider" class="cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 text-foreground hover:bg-foreground/5 bg-background border-foreground/20 h-10 px-4 py-2 w-24">
                    Close
                </button>
            </div>
        </x-slot>
    </x-menu.modal>
    @endif
    <!-- END: Photo Slider Modal -->
 
</div>