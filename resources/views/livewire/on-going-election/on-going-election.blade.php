<div>
    <h2 class="mt-10 text-lg font-medium">On Going Election for {{ $currentStudent ? ($currentStudent->department->department_name ?? 'All Departments') : 'All Departments' }} / STSG</h2>

    @if($currentStudent)
        <div class="mt-4 bg-gray-50 border border-gray-200 rounded-lg p-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-2 text-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        <span class="font-medium">{{ $currentStudent->first_name }} {{ $currentStudent->last_name }}</span>
                        <!-- <span class="text-sm text-gray-500">({{ $currentStudent->student_id }})</span> -->
                    </div>
                    <div class="text-sm text-gray-600">
                        {{ $currentStudent->course->course_name ?? 'N/A' }} - {{ $currentStudent->department->department_name ?? 'N/A' }}
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    @if($canVote)
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            Can Vote
                        </span>
                    @elseif($canViewCandidates)
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                            </svg>
                            View Only
                        </span>
                    @else
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd"></path>
                            </svg>
                            No Access
                        </span>
                    @endif
                </div>
            </div>
        </div>
    @endif

    @if(isset($upcomingVoting) && $upcomingVoting)
        <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
            <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-600 mr-2">
                    <path d="M12 2a10 10 0 100 20 10 10 0 000-20z"></path>
                </svg>
                <div class="text-sm text-blue-800">
                    <strong>Upcoming Election:</strong> Voting is scheduled from {{ \Carbon\Carbon::parse($upcomingVoting->start_datetime)->format('M d, Y \a\t h:i A') }} to {{ \Carbon\Carbon::parse($upcomingVoting->end_datetime)->format('M d, Y \a\t h:i A') }}.
                </div>
            </div>
        </div>
    @endif

    <div class="mt-5 grid grid-cols-12 gap-x-6 gap-y-8">
        <div class="col-span-12 mt-2 flex flex-wrap items-center sm:flex-nowrap">
            <div class="mx-auto hidden opacity-70 md:block">
                @if(count($activeVotingExclusives) > 0)
                    Showing {{ count($activeVotingExclusives) }} active election(s)
                @else
                    No active elections
                @endif
            </div>
            <!-- <div class="mt-3 w-full sm:ml-auto sm:mt-0 sm:w-auto md:ml-0">
                <div class="relative w-56">
                    <input class="h-10 rounded-md border bg-background px-3 py-2 ring-offset-background file:border-0 file:bg-transparent file:font-medium file:text-foreground placeholder:text-foreground/70 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 box w-56 pr-10" type="text" placeholder="Search...">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="search" class="lucide lucide-search size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 absolute inset-y-0 right-0 my-auto mr-3 h-4 w-4"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.3-4.3"></path></svg>
                </div>
            </div> -->
        </div>
        <!-- BEGIN: Data List -->
        
        @if(count($activeVotingExclusives) > 0)
            @foreach($activeVotingExclusives as $exclusive)
                @if(count($exclusive['candidates_by_position']) > 0)
                    @foreach($exclusive['candidates_by_position'] as $position => $positionData)
                        <!-- Position Header with Voting Limit -->
                        <div class="col-span-12 mb-4">
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <h3 class="text-lg font-semibold text-blue-800">{{ $position }}</h3>
                                <p class="text-sm text-blue-600">
                                    You can vote for up to <strong>{{ $positionData['allowed_votes'] }}</strong> candidate(s) for this position.
                                    @if(isset($positionVoteCounts[$position]) && $positionVoteCounts[$position] > 0)
                                        <span class="text-green-600">(Currently selected: {{ $positionVoteCounts[$position] }})</span>
                                    @endif
                                </p>
                        </div>
                        </div>
                        @foreach($positionData['candidates'] as $candidate)
                            @php
                                    $candidateId = isset($candidate->id) ? $candidate->id : $candidate->students_id;
                                    $student = isset($candidate->student) ? $candidate->student : $candidate->students;
                                    $voteCount = isset($candidate->number_of_vote) ? $candidate->number_of_vote : 0;
                                    $status = isset($candidate->status) ? $candidate->status : 'unknown';
                                    // Ensure $student is not null to avoid errors
                                    if (!$student) {
                                        // Optionally, you can skip rendering this candidate or set default values
                                        continue;
                                    }
                                @endphp

                            {{-- Server-side filtered: only candidates relevant to the logged-in student (or general) are provided by the component --}}

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
                                            <a href="#" wire:click.prevent="openImageModal('{{ $imageSrc }}')" aria-label="Open candidate image" class="relative z-20 block w-full h-full">
                                                <img class="w-full h-full object-cover rounded-lg" src="{{ $imageSrc }}" alt="Candidate Photo" loading="lazy">
                                            </a>
                        <div class="flex cursor-pointer items-center rounded-full border px-2 py-px text-xs border-(--color-pending) bg-(--color-pending)/70 absolute top-0 z-10 m-5 text-white [--color:var(--color-pending)]">
                                                {{ $position }}
                        </div>
                        <div class="absolute bottom-0 z-10 px-5 pb-6 text-white">
                            <a class="block text-base font-medium" href="">
                                                    {{ $student->first_name }} {{ $student->last_name }}
                            </a>
                            <span class="mt-3 text-xs opacity-70">
                                                    {{ $student->course->course_name ?? 'N/A' }} - {{ $student->department->department_name ?? 'N/A' }}
                                                    {{-- Candidate belongs to this election (department/general) --}}
                            </span>
                        </div>
                    </div>
                    <div class="mt-5 opacity-70">
                    <div class="mt-2 flex items-center">
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="badge" class="lucide lucide-badge size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 mr-2 h-4 w-4"><path d="M3 7v10a2 2 0 0 0 2 2h3l2 2 2-2h3a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2Z"></path></svg>
							<span class="text-sm">Name: </span>
							<span class="ml-1 text-sm font-medium">    {{ $student->first_name }} {{ $student->last_name }}
                            </span>
						</div>
						<div class="mt-2 flex items-center">
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="badge" class="lucide lucide-badge size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 mr-2 h-4 w-4"><path d="M3 7v10a2 2 0 0 0 2 2h3l2 2 2-2h3a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2Z"></path></svg>
							<span class="text-sm">Position: </span>
							<span class="ml-1 text-sm font-medium">{{ $position }}</span>
						</div>
						<div class="mt-1 flex items-center">
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="users" class="lucide lucide-users size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 mr-2 h-4 w-4"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
							<span class="text-sm">Partylist: </span>
							<span class="ml-1 text-sm font-medium">
								{{ optional(optional($candidate->appliedCandidacy)->partylist)->partylist_name ?? 'Independent' }}
							</span>
						</div>
                        <div class="mt-2 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="bar-chart" class="lucide lucide-bar-chart size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 mr-2 h-4 w-4"><path d="M12 20V10"></path><path d="M18 20V4"></path><path d="M6 20v-4"></path></svg>
                                        <span class="text-sm">Votes: </span>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 ml-1">
                                            {{ $voteCount }}
                                        </span>
                                    </div>
                        <div class="mt-2 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="check-square" class="lucide lucide-check-square size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 mr-2 h-4 w-4"><path d="M21 10.5V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h12.5"></path><path d="m9 11 3 3L22 4"></path></svg>
                            <span class="text-sm">Status: </span>
                            @if($status === 'win')
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 ml-1">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Winner
                                </span>
                            @elseif($status === 'loss')
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 ml-1">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                    </svg>
                                    Lost Election
                                </span>
                            @elseif($status === 'official')
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 ml-1">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                    </svg>
                                    Official Candidate
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 ml-1">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ ucfirst($status) }}
                                </span>
                            @endif
                        </div>
                        
                        @if($status === 'loss')
                            <div class="mt-2 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="trending-down" class="lucide lucide-trending-down size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 mr-2 h-4 w-4"><path d="M22 17l-8.5-8.5-5 5L2 7"></path><path d="M16 17h6v-6"></path></svg>
                                <span class="text-sm text-red-600">Did not win this position</span>
                            </div>
                        @endif
                    </div>
                </div>
                @if($this->canVoteInExclusive($exclusive))
                    <div class="flex items-center justify-center border-t p-5 lg:justify-end">
                        <input 
                            type="checkbox" 
                            wire:model="selectedCandidates" 
                            value="{{ $candidateId }}"
                            class="mr-3 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                            id="candidate_{{ $candidateId }}"
                            @if(!$this->canSelectCandidate($candidateId, $position))
                                disabled
                            @endif
                            wire:change="$refresh"
                        >
                        @if(!$this->canSelectCandidate($candidateId, $position) && !in_array($candidateId, $selectedCandidates))
                            <span class="text-xs text-red-500 ml-2">Max {{ $positionData['allowed_votes'] }} vote(s) for {{ $position }}</span>
                        @endif
                    </div>
                @else
                    <div class="flex items-center justify-center border-t p-5 lg:justify-end">
                        <div class="text-center">
                            <div class="text-sm text-gray-500 mb-2">
                                @if($exclusive['department_id'] && $exclusive['course_id'])
                                    <span class="text-blue-600">View Only</span>
                                    <br>
                                    <span class="text-xs">This election is for {{ $exclusive['course'] }} students only</span>
                                @elseif($exclusive['department_id'] && !$exclusive['course_id'])
                                    <span class="text-blue-600">View Only</span>
                                    <br>
                                    <span class="text-xs">This election is for {{ $exclusive['department'] }} students only</span>
                                @else
                                    <span class="text-gray-400">View Only</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
                        @endforeach
                    @endforeach
                @endif
            @endforeach
        @else
            <div class="col-span-12 text-center py-12">
                <div class="mx-auto h-12 w-12 text-gray-400">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No Active Elections</h3>
                <p class="mt-1 text-sm text-gray-500">There are currently no active voting periods.</p>
            </div>
        @endif
        
        @if(count($activeVotingExclusives) > 0 && $canVote)
            <div class="col-span-12 flex justify-end mt-6">
                @if($hasVoted)
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-2 text-green-600">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5">
                                <path d="M9 12l2 2 4-4"></path>
                                <path d="M21 12c.552 0 1-.448 1-1V8c0-.552-.448-1-1-1h-3.5l-1-1h-5l-1 1H3c-.552 0-1 .448-1 1v3c0 .552.448 1 1 1h18z"></path>
                            </svg>
                            <span class="font-medium">You have already voted!</span>
                        </div>
                        <button 
                            type="button" 
                            disabled
                            class="cursor-not-allowed inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg]:size-4 [&_svg]:shrink-0 bg-gray-300 border-gray-300 text-gray-500 h-10 px-4 py-2 box"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="vote" class="lucide lucide-vote size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25">
                                <path d="M9 12l2 2 4-4"></path>
                                <path d="M21 12c.552 0 1-.448 1-1V8c0-.552-.448-1-1-1h-3.5l-1-1h-5l-1 1H3c-.552 0-1 .448-1 1v3c0 .552.448 1 1 1h18z"></path>
                            </svg>
                            Submit Vote
                        </button>
                    </div>
                @else
                    <button 
                        type="button" 
                        wire:click="showVoteConfirmation"
                        class="cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg]:size-4 [&_svg]:shrink-0 bg-(--color)/20 border-(--color)/60 text-(--color) hover:bg-(--color)/5 [--color:var(--color-primary)] h-10 px-4 py-2 box"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="vote" class="lucide lucide-vote size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25">
                            <path d="M9 12l2 2 4-4"></path>
                            <path d="M21 12c.552 0 1-.448 1-1V8c0-.552-.448-1-1-1h-3.5l-1-1h-5l-1 1H3c-.552 0-1 .448-1 1v3c0 .552.448 1 1 1h18z"></path>
                        </svg>
                        Submit Vote
                    </button>
                @endif
            </div>
        @elseif(count($activeVotingExclusives) > 0 && !$canVote && $canViewCandidates)
            <div class="col-span-12 flex justify-center mt-6">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-center">
                    <div class="flex items-center justify-center gap-2 text-blue-600 mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5">
                            <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                            <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                        </svg>
                        <span class="font-medium">View Only Mode</span>
                    </div>
                    <p class="text-sm text-blue-600">
                        You can view the candidates but cannot vote in this election. 
                        This election has specific department/course restrictions.
                    </p>
                </div>
            </div>
        @endif
        </form>
        <!-- END: Data List -->
    <!-- BEGIN: Pagination -->
    @if($enablePagination)
    <div class="col-span-12 flex flex-wrap items-center sm:flex-row sm:flex-nowrap">
            <nav class="w-full sm:mr-auto sm:w-auto">
                <ul class="mr-0 flex w-full gap-1 sm:mr-auto sm:w-auto">
                    <li class="flex-1 sm:flex-initial">
                        <a class="[--color:var(--color-foreground)] cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 text-(--color) hover:bg-(--color)/5 h-10 px-4 py-2 border-transparent bg-transparent">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="chevrons-left" class="lucide lucide-chevrons-left size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25"><path d="m11 17-5-5 5-5"></path><path d="m18 17-5-5 5-5"></path></svg>
                        </a>
                    </li>
                    <li class="flex-1 sm:flex-initial">
                        <a class="[--color:var(--color-foreground)] cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 text-(--color) hover:bg-(--color)/5 h-10 px-4 py-2 border-transparent bg-transparent">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="chevron-left" class="lucide lucide-chevron-left size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25"><path d="m15 18-6-6 6-6"></path></svg>
                        </a>
                    </li>
                    <li class="flex-1 sm:flex-initial">
                        <a class="[--color:var(--color-foreground)] cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 text-(--color) hover:bg-(--color)/5 h-10 px-4 py-2 border-transparent bg-transparent">
                            ...
                        </a>
                    </li>
                    <li class="flex-1 sm:flex-initial">
                        <a class="[--color:var(--color-foreground)] cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 text-(--color) hover:bg-(--color)/5 h-10 px-4 py-2 border-transparent bg-transparent">
                            1
                        </a>
                    </li>
                    <li class="flex-1 sm:flex-initial">
                        <a class="[--color:var(--color-foreground)] cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 text-(--color) hover:bg-(--color)/5 bg-background h-10 px-4 py-2 box rounded-xl border-inherit">
                            2
                        </a>
                    </li>
                    <li class="flex-1 sm:flex-initial">
                        <a class="[--color:var(--color-foreground)] cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 text-(--color) hover:bg-(--color)/5 h-10 px-4 py-2 border-transparent bg-transparent">
                            3
                        </a>
                    </li>
                    <li class="flex-1 sm:flex-initial">
                        <a class="[--color:var(--color-foreground)] cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 text-(--color) hover:bg-(--color)/5 h-10 px-4 py-2 border-transparent bg-transparent">
                            ...
                        </a>
                    </li>
                    <li class="flex-1 sm:flex-initial">
                        <a class="[--color:var(--color-foreground)] cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 text-(--color) hover:bg-(--color)/5 h-10 px-4 py-2 border-transparent bg-transparent">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="chevron-right" class="lucide lucide-chevron-right size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25"><path d="m9 18 6-6-6-6"></path></svg>
                        </a>
                    </li>
                    <li class="flex-1 sm:flex-initial">
                        <a class="[--color:var(--color-foreground)] cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 text-(--color) hover:bg-(--color)/5 h-10 px-4 py-2 border-transparent bg-transparent">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="chevrons-right" class="lucide lucide-chevrons-right size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25"><path d="m6 17 5-5-5-5"></path><path d="m13 17 5-5-5-5"></path></svg>
                        </a>
                    </li>
                </ul>
            </nav>
            <select class="bg-(image:--background-image-chevron) bg-[position:calc(100%-theme(spacing.3))_center] bg-[size:theme(spacing.5)] bg-no-repeat relative appearance-none flex h-10 rounded-md border bg-background px-3 py-2 ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 box mt-3 w-20 sm:mt-0">
                <option>10</option>
                <option>25</option>
                <option>35</option>
                <option>50</option>
            </select>
    </div>
    <!-- END: Pagination -->
    @endif
    </div>

    <!-- Warning Modal -->
    @if($showWarningModal)
        <div class="modal group bg-black/60 transition-[visibility,opacity] w-screen h-screen fixed left-0 top-0 z-[9998] show duration-[0s,0.4s] visible opacity-100">
            <div class="box relative before:absolute before:inset-0 before:mx-3 before:-mb-3 before:border before:border-foreground/10 before:z-[-1] after:absolute after:inset-0 after:border after:border-foreground/10 after:bg-background after:shadow-[0px_3px_5px_#0000000b] after:z-[-1] after:backdrop-blur-md before:bg-background/60 dark:before:shadow-background before:shadow-foreground/60 z-[9999] mx-auto mt-16 p-6 transition-[margin-top,transform] duration-[0.4s,0.3s] before:rounded-3xl before:shadow-2xl after:rounded-3xl sm:max-w-md">
                
                <div class="p-5 text-center border-b border-foreground/10">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-red-100 mb-4">
                        <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                        </svg>
                    </div>
                    <h2 class="text-lg font-medium text-red-800">Voting Limit Exceeded</h2>
                    <div class="mt-2 text-sm text-red-600">{{ $warningMessage }}</div>
                </div>

                <div class="px-5 pb-8 text-center border-t border-foreground/10 pt-4">
                    <button 
                        wire:click="closeWarningModal" 
                        type="button" 
                        class="cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 bg-red-600 border-red-600 text-white hover:bg-red-700 h-10 px-4 py-2 w-24"
                    >
                        OK
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Vote Confirmation Modal -->
    @if($showVoteConfirmationModal)
        <x-menu.modal 
            :showButton="false" 
            modalId="vote-confirmation-modal" 
            title="Confirm Your Vote" 
            description="Please review your selections before submitting your vote."
            size="lg"
            :isOpen="$showVoteConfirmationModal">
            
            <div class="space-y-4">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h4 class="font-medium text-blue-800 mb-2">Your Selected Candidates:</h4>
                    <div class="space-y-2">
                        @foreach($activeVotingExclusives as $exclusive)
                            @foreach($exclusive['candidates_by_position'] as $position => $positionData)
                                @php
                                    $selectedInPosition = [];
                                    foreach($positionData['candidates'] as $candidate) {
                                        $candidateId = isset($candidate->id) ? $candidate->id : $candidate->students_id;
                                        if(in_array($candidateId, $selectedCandidates)) {
                                            $student = isset($candidate->student) ? $candidate->student : $candidate->students;
                                            $selectedInPosition[] = $student;
                                        }
                                    }
                                @endphp
                                @if(count($selectedInPosition) > 0)
                                    <div class="border-l-4 border-blue-400 pl-3">
                                        <h5 class="font-medium text-blue-700">{{ $position }}</h5>
                                        <ul class="text-sm text-blue-600 mt-1">
                                            @foreach($selectedInPosition as $student)
                                                <li>• {{ $student->first_name }} {{ $student->last_name }} ({{ $student->student_id }})</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            @endforeach
                        @endforeach
    </div>
</div>

                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-yellow-600 mt-0.5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        <div>
                            <h4 class="font-medium text-yellow-800">Important Notice</h4>
                            <p class="text-sm text-yellow-700 mt-1">Once you submit your vote, you cannot change your selections. Please make sure you have selected the correct candidates.</p>
                        </div>
                    </div>
                </div>
            </div>

            <x-slot:footer>
                <button 
                    data-tw-dismiss="modal" 
                    type="button" 
                    wire:click="cancelVote" 
                    class="cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 text-foreground hover:bg-foreground/5 bg-background border-foreground/20 h-10 px-4 py-2 w-24 mx-auto"
                >
                    Exit
                </button>
                <button 
                    type="button" 
                    wire:click="confirmVote" 
                    class="ml-3 cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 bg-(--color)/20 border-(--color)/60 text-(--color) hover:bg-(--color)/5 [--color:var(--color-primary)] h-10 px-4 py-2 w-24 mx-auto"
                >
                    Confirm
                </button>
            </x-slot:footer>
        </x-menu.modal>
    @endif

    <!-- Candidate Image Modal -->
    @if($showImageModal)
        <x-menu.modal :showButton="false" modalId="candidate-image-modal" title="Candidate Photo" size="lg" :isOpen="$showImageModal">
            <div class="text-center">
                @if($imageModalSrc)
                    <img src="{{ $imageModalSrc }}" alt="Candidate Photo" class="mx-auto max-h-[70vh] w-auto rounded-lg object-contain">
                @else
                    <div class="text-sm text-gray-500">Image not available.</div>
                @endif
            </div>
            <x-slot:footer>
                <button type="button" wire:click="closeImageModal" class="cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 text-foreground hover:bg-foreground/5 bg-background border-foreground/20 h-10 px-4 py-2 w-24 mx-auto">
                    Exit
                </button>
            </x-slot:footer>
        </x-menu.modal>
    @endif
</div>