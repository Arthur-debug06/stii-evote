@section('title', 'Candidates Election - Election Results')

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
        <a href="{{ route('pdf.candidates-election', ['search' => $search, 'school_year' => $schoolYearFilter, 'voting_exclusive' => $votingExclusiveFilter]) }}" target="_blank" class="cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 bg-(--color)/20 border-(--color)/60 text-(--color) hover:bg-(--color)/5 [--color:var(--color-primary)] h-10 px-4 py-2 box mr-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="printer" class="lucide lucide-printer size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 h-4 w-4"><path d="M6 9V2h12v7"></path><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><path d="M6 14h12v8H6z"></path></svg>
            Print Election Results
        </a>
            <div class="mx-auto hidden opacity-70 md:block">
                @if(!empty($voteCounts) && count($voteCounts) > 0)
                    Showing {{ count($voteCounts) }} candidate(s) with vote counts
                    @if(!empty($search) || !empty($schoolYearFilter) || !empty($votingExclusiveFilter))
                        (filtered from {{ \App\Models\voting_vote_count::count() }} total entries)
                    @endif
                @else
                    No election results found
                    @if(!empty($search))
                        for "{{ $search }}"
                    @endif
                @endif
            </div>
            
            <!-- Voting Exclusive Filter -->
            <div class="mt-3 w-full sm:ml-auto sm:mt-0 sm:w-auto md:ml-0 mr-2">
                <div class="relative w-64">
                    <select 
                        wire:model.live="votingExclusiveFilter"
                        class="h-10 rounded-md border bg-background px-3 py-2 ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 box w-64">
                        <option value="">All Elections</option>
                        @foreach($votingExclusives as $ve)
                            <option value="{{ $ve->id }}">
                                @php
                                    $sy = $ve->schoolYear;
                                    $dept = $ve->department;
                                    $course = $ve->course;
                                    $startDate = \Carbon\Carbon::parse($ve->start_datetime)->format('M d, Y');
                                @endphp
                                {{ $sy ? $sy->school_year : 'N/A' }} - 
                                @if($dept)
                                    {{ $dept->department_name }}
                                @else
                                    STSG
                                @endif
                                @if($course) - {{ $course->course_name ?? $course->name }}@endif
                                ({{ $startDate }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <!-- School Year Filter -->
            <div class="mt-3 w-full sm:ml-auto sm:mt-0 sm:w-auto md:ml-0 mr-2">
                <div class="relative w-56">
                    <select 
                        wire:model.live="schoolYearFilter"
                        class="h-10 rounded-md border bg-background px-3 py-2 ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 box w-56">
                        <option value="">All School Years</option>
                        @foreach($schoolYears as $sy)
                            <option value="{{ $sy->id }}">
                                {{ $sy->school_year }} @if($sy->semester)({{ $sy->semester }})@endif
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <!-- Search Input -->
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
                            {{ count($candidates) }} candidate(s) for this position
                        </p>
                    </div>
                </div>

                <!-- Candidates Grid -->
                @foreach($candidates as $voteCount)
                    @php
                        $student = $voteCount->student;
                        $position = $voteCount->appliedCandidacy?->position;
                        $status = $voteCount->status;
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
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="bar-chart" class="lucide lucide-bar-chart size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 mr-2 h-4 w-4"><path d="M12 20V10"></path><path d="M18 20V4"></path><path d="M6 20v-4"></path></svg>
                                        <span class="text-sm">Votes: </span>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 ml-1">
                                            {{ $voteCount->number_of_vote }}
                                        </span>
                                    </div>
                                    <div class="mt-2 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="award" class="lucide lucide-award size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 mr-2 h-4 w-4"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"></path><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"></path><path d="M4 22h16"></path><path d="M10 14.66V17c0 .55-.47.98-.97 1.21l-1 .42c-.52.22-1.03-.22-1.03-.79 0-.28.12-.54.32-.72L8 16.5l-1.32-1.12c-.2-.18-.32-.44-.32-.72 0-.57.51-1.01 1.03-.79l1 .42c.5.23.97.66.97 1.21v-2.34z"></path><path d="M14 14.66V17c0 .55.47.98.97 1.21l1 .42c.52.22 1.03-.22 1.03-.79 0-.28-.12-.54-.32-.72L16 16.5l1.32-1.12c.2-.18.32-.44.32-.72 0-.57-.51-1.01-1.03-.79l-1 .42c-.5.23-.97.66-.97 1.21v-2.34z"></path></svg>
                                        <span class="text-sm">Status: </span>
                                        @php $isWinner = ($status === 'win'); @endphp
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ml-1 {{ $isWinner ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            @if($isWinner)
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                </svg>
                                            @else
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 110-16 8 8 0 010 16zM8.293 7.293a1 1 0 011.414 0L10 7.586l.293-.293a1 1 0 111.414 1.414L11.414 9l.293.293a1 1 0 01-1.414 1.414L10 10.414l-.293.293A1 1 0 018.293 9.293L8.586 9l-.293-.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                </svg>
                                            @endif
                                            {{ strtoupper($status ?? 'UNKNOWN') }}
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
                <h3 class="mt-2 text-sm font-medium text-gray-900">No Candidates Found</h3>
                <p class="mt-1 text-sm text-gray-500">There are currently no candidates with vote counts for any position.</p>
            </div>
        @endif
        <!-- END: Candidates by Position -->
    </div>
 
</div>
</div>