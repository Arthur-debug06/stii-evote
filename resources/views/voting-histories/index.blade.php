@extends('layouts.master')

@section('content')
<div class="intro-y flex items-center mt-8">
    <h2 class="text-lg font-medium">Voting Histories</h2>
</div>

<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="col-span-12">
        <div class="box p-4">
            <div class="flex items-center justify-between mb-4">
                <div class="font-medium">All recorded election histories</div>
                <div>
                    <a href="{{ url('/') }}" class="btn btn-outline-secondary btn-sm">Back to Dashboard</a>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table-auto w-full border-collapse">
                    <thead>
                        <tr class="bg-slate-100">
                            <th class="text-left px-4 py-2 text-xs text-slate-600">#</th>
                            <th class="text-left px-4 py-2 text-xs text-slate-600">Title</th>
                            <th class="text-left px-4 py-2 text-xs text-slate-600">School Year</th>
                            <th class="text-center px-4 py-2 text-xs text-slate-600">Total Voters</th>
                            <th class="text-center px-4 py-2 text-xs text-slate-600">Total Votes</th>
                            <th class="text-left px-4 py-2 text-xs text-slate-600">Recorded</th>
                            <th class="text-center px-4 py-2 text-xs text-slate-600">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($histories as $h)
                        <tr class="intro-x border-b hover:bg-slate-50 transition">
                            <td class="px-4 py-3">{{ $h->id }}</td>
                            <td class="px-4 py-3 max-w-xl truncate">{{ $h->title }}</td>
                            <td class="px-4 py-3">
                                @php
                                    $sy = optional($h->votingExclusive->schoolYear);
                                    if (!$sy) $sy = optional($h->schoolYear);
                                @endphp
                                @if($sy && ($sy->school_year || $sy->semester))
                                    <span class="text-xs px-2 py-1 rounded-full bg-primary/10 text-primary">{{ $sy->school_year }} {{ $sy->semester ? '('.$sy->semester.')' : '' }}</span>
                                @else
                                    <span class="text-xs px-2 py-1 rounded bg-gray-100 text-gray-600">-</span>
                                @endif
                            </td>
                            <td class="text-center px-4 py-3"><span class="font-medium">{{ $h->total_voters }}</span></td>
                            <td class="text-center px-4 py-3"><span class="font-medium">{{ $h->total_votes }}</span></td>
                            <td class="px-4 py-3">{{ $h->created_at->format('M d, Y h:i A') }}</td>
                            <td class="text-center px-4 py-3">
                                <a href="{{ route('voting-histories.show', $h->id) }}" class="inline-flex items-center gap-2 px-3 py-1 rounded bg-primary text-white text-sm">
                                    <i data-lucide="eye" class="size-4"></i>
                                    View
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-6">No histories found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $histories->links() }}
            </div>
        </div>
    </div>
</div>

@endsection
