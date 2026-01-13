<div>
    <div class="flex items-center gap-2 mb-4">
        <select wire:model="selectedVotingId" class="input">
            <option value="">-- Select Voting Exclusive --</option>
            @foreach($votings as $v)
                <option value="{{ $v->id }}">{{ $v->title ?? 'Untitled' }} ({{ optional($v->start_datetime)->format('Y-m-d') }})</option>
            @endforeach
        </select>

        <div class="relative flex-1">
            <input wire:model.debounce.350ms="search" id="search-input" class="input w-full" placeholder="Search students...">
            <div class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z" />
                </svg>
            </div>
        </div>

        @if($selectedVoting)
            <a target="_blank" href="{{ url('/pdf/student-voters/' . $selectedVoting->id) }}" class="btn btn-primary">Print Report</a>
        @endif
    </div>

    @if(!$selectedVoting)
        <div class="p-4 bg-yellow-50 border border-yellow-200">Please select a voting exclusive to show voters.</div>
    @else
        @if($voters && $voters->count())
            <div class="overflow-x-auto">
                <table class="table-auto w-full">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Student Name</th>
                            <th>Student ID</th>
                            <th>Course</th>
                            <th>Department</th>
                            <th>Voted At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($voters as $idx => $vote)
                            <tr>
                                <td>{{ $voters->firstItem() + $idx }}</td>
                                <td>{{ optional($vote->student)->full_name ?? 'N/A' }}</td>
                                <td>{{ optional($vote->student)->student_id ?? 'N/A' }}</td>
                                <td>{{ optional($vote->student->course)->name ?? '' }}</td>
                                <td>{{ optional($vote->student->department)->name ?? '' }}</td>
                                <td>{{ optional($vote->created_at) ? $vote->created_at->format('Y-m-d H:i') : '' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $voters->links() }}
            </div>
        @else
            <div class="p-4 bg-gray-50 border border-gray-200">No voters found for this voting.</div>
        @endif
    @endif
</div>
