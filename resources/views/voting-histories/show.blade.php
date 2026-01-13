@extends('layouts.master')

@section('content')
<div class="intro-y flex items-center mt-8">
    <h2 class="text-lg font-medium">Voting History</h2>
    <div class="ml-auto">
        <a href="{{ route('voting-histories.index') }}" class="btn btn-outline-secondary btn-sm">Back</a>
    </div>
</div>

<div class="grid grid-cols-12 gap-6 mt-5">
    <!-- Main content -->
    <div class="col-span-12 lg:col-span-8">
        <div class="box p-4">

            <!-- Title and School Year -->
            <div class="mb-4">
                <h3 class="font-medium">{{ $history->title }}</h3>
                @php
                    $sy = optional($history->votingExclusive->schoolYear) ?: optional($history->schoolYear);
                @endphp
                <div class="text-sm text-muted">
                    {{ $sy ? ($sy->school_year . ' ' . ($sy->semester ? '(' . $sy->semester . ')' : '')) : '' }}
                </div>
            </div>

            <!-- Totals -->
            <div class="grid grid-cols-3 gap-3 mb-4">
                <div class="p-3 bg-gray-50 rounded">
                    <div class="text-xs text-muted">Total Voters</div>
                    <div class="text-lg font-semibold">{{ $history->total_voters }}</div>
                </div>
                <div class="p-3 bg-gray-50 rounded">
                    <div class="text-xs text-muted">Total Votes</div>
                    <div class="text-lg font-semibold">{{ $history->total_votes }}</div>
                </div>
                <div class="p-3 bg-gray-50 rounded">
                    <div class="text-xs text-muted">Recorded</div>
                    <div class="text-lg font-semibold">{{ $history->created_at->format('M d, Y h:i A') }}</div>
                </div>
            </div>
<!-- Winner Summary -->
<div class="mt-4 p-3 bg-gray-50 rounded">
    <h4 class="font-medium mb-2">Election Summary</h4>

    <div class="text-sm text-gray-700 space-y-2">
        @foreach(explode(', ', $history->winner_summary) as $winner)
            <p class="text-base"><span class="font-bold text-lg">{{ $winner }}</span></p>
        @endforeach
    </div>
</div>



        </div>
    </div>

    <!-- Voting Details Sidebar -->
    <div class="col-span-12 lg:col-span-4">
        <div class="box p-4">
            <h4 class="font-medium">Details</h4>
            <div class="mt-3 text-sm">
                <p><strong>Start:</strong> {{ optional($history->start_datetime)->format('M d, Y h:i A') ?? '-' }}</p>
                <p><strong>End:</strong> {{ optional($history->end_datetime)->format('M d, Y h:i A') ?? '-' }}</p>
                <p><strong>School Year:</strong> {{ $sy ? ($sy->school_year . ' ' . ($sy->semester ? '(' . $sy->semester . ')' : '')) : '' }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
