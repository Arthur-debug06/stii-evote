@section('title', 'Candidates Position')

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

        // Password toggle functionality
        function togglePassword(button) {
            const passwordSpan = button.previousElementSibling;
            const currentText = passwordSpan.textContent;
            const actualPassword = passwordSpan.getAttribute('data-password');
            
            if (currentText === '••••••••') {
                passwordSpan.textContent = actualPassword;
                button.textContent = 'Hide';
                button.classList.remove('text-blue-600', 'hover:text-blue-800');
                button.classList.add('text-red-600', 'hover:text-red-800');
            } else {
                passwordSpan.textContent = '••••••••';
                button.textContent = 'Show';
                button.classList.remove('text-red-600', 'hover:text-red-800');
                button.classList.add('text-blue-600', 'hover:text-blue-800');
            }
        }
    </script>
    
    <h2 class="mt-10 text-lg font-medium">Candidates by Position</h2>
    <div class="mt-5 grid grid-cols-12 gap-6">
        <div class="col-span-12 mt-2 flex flex-wrap items-center sm:flex-nowrap">
        <a href="{{ route('pdf.candidates-list') }}" target="_blank" class="cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 bg-(--color)/20 border-(--color)/60 text-(--color) hover:bg-(--color)/5 [--color:var(--color-primary)] h-10 px-4 py-2 box mr-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="printer" class="lucide lucide-printer size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 h-4 w-4"><path d="M6 9V2h12v7"></path><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><path d="M6 14h12v8H6z"></path></svg>
            Print Candidates List
        </a>
            <div class="mx-auto hidden opacity-70 md:block">
                @if(count($approvedCandidacies) > 0)
                    Showing {{ count($approvedCandidacies) }} approved candidate(s)
                    @if(!empty($search))
                        (filtered from {{ \App\Models\applied_candidacy::where('status', 'approved')->count() }} total entries)
                    @endif
                @else
                    No approved candidates found
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
                        placeholder="Search candidate name, student ID, position...">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="search" class="lucide lucide-search size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 absolute inset-y-0 right-0 my-auto mr-3 h-4 w-4"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.3-4.3"></path></svg>
                </div>
            </div>
        </div>
        
        <!-- BEGIN: Candidates by Position -->
        @if(count($candidatesByPosition) > 0)
            @foreach($candidatesByPosition as $positionName => $candidates)
                <!-- Position Header -->
                <div class="col-span-12 mb-4">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-blue-800">{{ $positionName }}</h3>
                        <p class="text-sm text-blue-600">
                            {{ count($candidates) }} approved candidate(s) for this position
                        </p>
                    </div>
                </div>

                <!-- Candidates Grid -->
                @foreach($candidates as $candidacy)
                    @php
                        $student = $candidacy->students;
                        $position = $candidacy->position;
                    @endphp
                    <div class="col-span-12 md:col-span-6 lg:col-span-4 xl:col-span-3">
                        <div class="box relative before:absolute before:inset-0 before:mx-3 before:-mb-3 before:border before:border-foreground/10 before:bg-background/30 before:shadow-[0px_3px_5px_#0000000b] before:z-[-1] before:rounded-xl after:absolute after:inset-0 after:border after:border-foreground/10 after:bg-background after:shadow-[0px_3px_5px_#0000000b] after:rounded-xl after:z-[-1] after:backdrop-blur-md p-0">
                            <div class="p-5">
                                <div class="image-fit h-40 overflow-hidden rounded-lg before:absolute before:left-0 before:top-0 before:z-10 before:block before:h-full before:w-full before:bg-gradient-to-t before:from-black before:to-black/10 2xl:h-56">
                                    @php
                                        $imageSrc = asset('images/placeholders/placeholder.jpg'); // Default placeholder

                                        // Prefer explicit DB paths using route-based serving
                                        if (!empty($student->profile_image) && \Illuminate\Support\Facades\Storage::disk('public')->exists($student->profile_image)) {
                                            $imageSrc = route('public.file', ['path' => $student->profile_image]);
                                        } elseif (!empty($student->student_id_image) && \Illuminate\Support\Facades\Storage::disk('public')->exists($student->student_id_image)) {
                                            $imageSrc = route('public.file', ['path' => $student->student_id_image]);
                                        } else {
                                            // Fallback: try to discover by pattern in student_images directory
                                            $studentImagesPath = storage_path('app/public/student_images/');

                                            // Look for profile images with the student's numeric PK or identifier in filename
                                            $profilePattern = $studentImagesPath . '*_profile_*' . $student->id . '*';
                                            $profileFiles = glob($profilePattern);

                                            if (!empty($profileFiles)) {
                                                $profileFile = basename($profileFiles[0]);
                                                $imageSrc = route('public.file', ['path' => 'student_images/' . $profileFile]);
                                            } else {
                                                // Look for ID images as fallback
                                                $idPattern = $studentImagesPath . '*_id_*' . $student->id . '*';
                                                $idFiles = glob($idPattern);

                                                if (!empty($idFiles)) {
                                                    $idFile = basename($idFiles[0]);
                                                    $imageSrc = route('public.file', ['path' => 'student_images/' . $idFile]);
                                                }
                                            }
                                        }
                                    @endphp
                                    <img class="rounded-lg" src="{{ $imageSrc }}" alt="Candidate Photo">
                                    <div class="flex cursor-pointer items-center rounded-full border px-2 py-px text-xs border-(--color-pending) bg-(--color-pending)/70 absolute top-0 z-10 m-5 text-white [--color:var(--color-pending)]">
                                        {{ $positionName }}
                    </div>
                                    <div class="absolute bottom-0 z-10 px-5 pb-6 text-white">
                                        <a class="block text-base font-medium" href="">
                                            {{ $student->first_name }} {{ $student->last_name }}
                                        </a>
                                        <span class="mt-3 text-xs opacity-70">
                                            {{ $student->course->course_name ?? 'N/A' }}
                                        </span>
                    </div>
                                </div>
                                <div class="mt-5 opacity-70">
                                <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="link" class="lucide lucide-link size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 mr-2 h-4 w-4"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path></svg>
                                        Student ID: {{ $student->student_id }}
                                        </div>
                                    <div class="mt-2 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="mail" class="lucide lucide-mail size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 mr-2 h-4 w-4"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><path d="M22 6l-10 7L2 6"></path></svg>
                                        {{ $student->email }}
                                </div>
                                    <div class="mt-2 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="check-square" class="lucide lucide-check-square size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 mr-2 h-4 w-4"><path d="M21 10.5V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h12.5"></path><path d="m9 11 3 3L22 4"></path></svg>
                                        <span class="text-sm">Status: </span>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 ml-1">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                            Approved
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endforeach
        @else
            <div class="col-span-12 text-center py-12">
                <div class="mx-auto h-12 w-12 text-gray-400">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                </div>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No Approved Candidates</h3>
                <p class="mt-1 text-sm text-gray-500">There are currently no approved candidates for any position.</p>
            </div>
        @endif
        <!-- END: Candidates by Position -->
    </div>
 
</div>