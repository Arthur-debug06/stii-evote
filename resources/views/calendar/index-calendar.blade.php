
@extends('layouts.master')

@push('css')
    <!-- Sweet Alert css-->
    <link href="{{ asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css">
@endpush
@section('content')
    {{-- @can('view disbursement voucher') --}}
        <!-- page title -->
        <livewire:calendar.calendar/>
    {{-- @else
        <div class="flex items-center p-4 text-red-800 border border-red-300 rounded-lg bg-red-50 dark:bg-red-800/10 dark:border-red-800 dark:text-red-500"
            role="alert">
            <i data-lucide="alert-circle" class="size-4 me-2"></i>
            <div>
                You don't have permission to view Calendar......
            </div>
        </div>
    @endcan --}}
@endsection
@push('scripts')
    <!-- list js-->
    <script src="{{ asset('build/libs/list.js/list.min.js') }}"></script>
    <script src="{{ asset('build/libs/list.pagination.js/list.pagination.min.js') }}"></script>
    <script src="{{ asset('build/libs/flatpickr/flatpickr.min.js') }}"></script>
    <!-- Sweet Alerts js -->
    <script src="{{ asset('build/libs/sweetalert2/sweetalert2.min.js') }}"></script>

    <!-- App js -->
    @vite('resources/js/app.js')
@endpush