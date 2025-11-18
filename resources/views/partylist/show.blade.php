@extends('layouts.master')

@section('title', $partylist->partylist_name ?? 'Partylist')

@section('content')
    <div class="mt-8">
        <div class="flex items-center gap-4">
            <a href="{{ url('/dashboard') }}" class="text-sm text-gray-600">&larr; Back to Dashboard</a>
            <h1 class="text-2xl font-semibold">{{ $partylist->partylist_name }}</h1>
        </div>

        @php
            $partyImgSrc = asset('images/placeholders/placeholder.jpg');
            $val = $partylist->partylist_image ?? null;
            if ($val && \Illuminate\Support\Facades\Storage::disk('public')->exists($val)) {
                $partyImgSrc = route('public.file', ['path' => $val]);
            }
        @endphp
        <div class="mt-4">
            <img src="{{ $partyImgSrc }}" alt="{{ $partylist->partylist_name }}" class="w-28 h-28 rounded-full object-cover">
        </div>
        

        @if($partylist->description)
            <div class="mt-3 text-sm text-gray-600">{{ $partylist->description }}</div>
        @endif

        <div class="mt-6 bg-white shadow rounded-lg p-4">
            <h2 class="text-lg font-medium">Candidates ({{ $candidacies->count() }})</h2>
            <div class="mt-4 space-y-3">
                @forelse($candidacies as $c)
                    <div class="flex items-center justify-between border rounded p-3">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-full overflow-hidden bg-gray-100 flex items-center justify-center text-gray-600">
                                @php
                                    $avatarSrc = null;
                                    $studentImg = $c->students->profile_image ?? null;
                                    if ($studentImg && \Illuminate\Support\Facades\Storage::disk('public')->exists($studentImg)) {
                                        $avatarSrc = route('public.file', ['path' => $studentImg]);
                                    }
                                @endphp
                                @if($avatarSrc)
                                    <img src="{{ $avatarSrc }}" alt="{{ $c->students->first_name }}" class="w-12 h-12 object-cover">
                                @else
                                    {{ strtoupper(substr($c->students->first_name ?? '', 0, 1) . substr($c->students->last_name ?? '', 0, 1)) }}
                                @endif
                            </div>
                            <div>
                                <div class="font-medium">{{ $c->students ? ($c->students->first_name . ' ' . $c->students->last_name) : 'Unknown' }}</div>
                                <div class="text-xs text-gray-500">{{ $c->position->position_name ?? 'N/A' }}</div>
                                <div class="text-xs text-gray-500">{{ $c->students->department->department_name ?? 'N/A' }}</div>
                                <div class="text-xs text-gray-500">{{ $c->students->course->course_name ?? 'N/A' }}</div>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm font-medium">Status: {{ ucfirst($c->status ?? 'N/A') }}</div>
                        </div>
                    </div>
                @empty
                    <div class="text-gray-500">No candidates found for this partylist.</div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
