@props([
    'paginator' => null,
    'currentPage' => null,
    'totalPages' => null,
    'perPage' => null,
    'perPageOptions' => [10, 25, 35, 50],
    'showPerPageSelect' => true,
    'onPageChange' => null,
    'onPerPageChange' => null,
])

@php
    // If paginator is provided, use its values
    if ($paginator) {
        $currentPage = $paginator->currentPage();
        $totalPages = $paginator->lastPage();
        $perPage = $paginator->perPage();
    } else {
        // Use provided values or defaults
        $currentPage = $currentPage ?? 2;
        $totalPages = $totalPages ?? 10;
        $perPage = $perPage ?? 10;
    }
@endphp

<div class="col-span-12 flex flex-wrap items-center sm:flex-row sm:flex-nowrap">
                                <nav class="w-full sm:mr-auto sm:w-auto">
                                    <ul class="mr-0 flex w-full gap-1 sm:mr-auto sm:w-auto">
                                        <li class="flex-1 sm:flex-initial">
                                            @if($paginator && $currentPage > 1)
                                                <a wire:click="gotoPage(1)" class="[--color:var(--color-foreground)] cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 text-(--color) hover:bg-(--color)/5 h-10 px-4 py-2 border-transparent bg-transparent">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="chevrons-left" class="lucide lucide-chevrons-left size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25"><path d="m11 17-5-5 5-5"></path><path d="m18 17-5-5 5-5"></path></svg>
                                                </a>
                                            @elseif($onPageChange && $currentPage > 1)
                                                <a wire:click="{{ $onPageChange }}(1)" class="[--color:var(--color-foreground)] cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 text-(--color) hover:bg-(--color)/5 h-10 px-4 py-2 border-transparent bg-transparent">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="chevrons-left" class="lucide lucide-chevrons-left size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25"><path d="m11 17-5-5 5-5"></path><path d="m18 17-5-5 5-5"></path></svg>
                                                </a>
                                            @else
                                                <a class="[--color:var(--color-foreground)] cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 text-(--color) hover:bg-(--color)/5 h-10 px-4 py-2 border-transparent bg-transparent opacity-50 pointer-events-none">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="chevrons-left" class="lucide lucide-chevrons-left size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25"><path d="m11 17-5-5 5-5"></path><path d="m18 17-5-5 5-5"></path></svg>
                                                </a>
                                            @endif
                                        </li>
                                        <li class="flex-1 sm:flex-initial">
                                            @if($paginator && $currentPage > 1)
                                                <a wire:click="gotoPage({{ $currentPage - 1 }})" class="[--color:var(--color-foreground)] cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 text-(--color) hover:bg-(--color)/5 h-10 px-4 py-2 border-transparent bg-transparent">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="chevron-left" class="lucide lucide-chevron-left size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25"><path d="m15 18-6-6 6-6"></path></svg>
                                                </a>
                                            @elseif($onPageChange && $currentPage > 1)
                                                <a wire:click="{{ $onPageChange }}({{ $currentPage - 1 }})" class="[--color:var(--color-foreground)] cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 text-(--color) hover:bg-(--color)/5 h-10 px-4 py-2 border-transparent bg-transparent">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="chevron-left" class="lucide lucide-chevron-left size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25"><path d="m15 18-6-6 6-6"></path></svg>
                                                </a>
                                            @else
                                                <a class="[--color:var(--color-foreground)] cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 text-(--color) hover:bg-(--color)/5 h-10 px-4 py-2 border-transparent bg-transparent opacity-50 pointer-events-none">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="chevron-left" class="lucide lucide-chevron-left size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25"><path d="m15 18-6-6 6-6"></path></svg>
                                                </a>
                                            @endif
                                        </li>
                                        <li class="flex-1 sm:flex-initial">
                                            <a class="[--color:var(--color-foreground)] cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 text-(--color) hover:bg-(--color)/5 h-10 px-4 py-2 border-transparent bg-transparent">
                                                ...
                                            </a>
                                        </li>
                                        <li class="flex-1 sm:flex-initial">
                                            @if($paginator && $currentPage != 1)
                                                <a wire:click="gotoPage(1)" class="[--color:var(--color-foreground)] cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 text-(--color) hover:bg-(--color)/5 h-10 px-4 py-2 border-transparent bg-transparent {{ $currentPage == 1 ? 'bg-background box rounded-xl border-inherit' : '' }}">
                                                    1
                                                </a>
                                            @elseif($onPageChange && $currentPage != 1)
                                                <a wire:click="{{ $onPageChange }}(1)" class="[--color:var(--color-foreground)] cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 text-(--color) hover:bg-(--color)/5 h-10 px-4 py-2 border-transparent bg-transparent {{ $currentPage == 1 ? 'bg-background box rounded-xl border-inherit' : '' }}">
                                                    1
                                                </a>
                                            @else
                                                <a class="[--color:var(--color-foreground)] cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 text-(--color) hover:bg-(--color)/5 h-10 px-4 py-2 border-transparent bg-transparent {{ $currentPage == 1 ? 'bg-background box rounded-xl border-inherit' : '' }}">
                                                    1
                                                </a>
                                            @endif
                                        </li>
                                        <li class="flex-1 sm:flex-initial">
                                            @if($paginator && $currentPage != 2)
                                                <a wire:click="gotoPage(2)" class="[--color:var(--color-foreground)] cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 text-(--color) hover:bg-(--color)/5 h-10 px-4 py-2 {{ $currentPage == 2 ? 'bg-background box rounded-xl border-inherit' : 'border-transparent bg-transparent' }}">
                                                    2
                                                </a>
                                            @elseif($onPageChange && $currentPage != 2)
                                                <a wire:click="{{ $onPageChange }}(2)" class="[--color:var(--color-foreground)] cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 text-(--color) hover:bg-(--color)/5 h-10 px-4 py-2 {{ $currentPage == 2 ? 'bg-background box rounded-xl border-inherit' : 'border-transparent bg-transparent' }}">
                                                    2
                                                </a>
                                            @else
                                                <a class="[--color:var(--color-foreground)] cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 text-(--color) hover:bg-(--color)/5 h-10 px-4 py-2 {{ $currentPage == 2 ? 'bg-background box rounded-xl border-inherit' : 'border-transparent bg-transparent' }}">
                                                    2
                                                </a>
                                            @endif
                                        </li>
                                        <li class="flex-1 sm:flex-initial">
                                            @if($paginator && $currentPage != 3)
                                                <a wire:click="gotoPage(3)" class="[--color:var(--color-foreground)] cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 text-(--color) hover:bg-(--color)/5 h-10 px-4 py-2 {{ $currentPage == 3 ? 'bg-background box rounded-xl border-inherit' : 'border-transparent bg-transparent' }}">
                                                    3
                                                </a>
                                            @elseif($onPageChange && $currentPage != 3)
                                                <a wire:click="{{ $onPageChange }}(3)" class="[--color:var(--color-foreground)] cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 text-(--color) hover:bg-(--color)/5 h-10 px-4 py-2 {{ $currentPage == 3 ? 'bg-background box rounded-xl border-inherit' : 'border-transparent bg-transparent' }}">
                                                    3
                                                </a>
                                            @else
                                                <a class="[--color:var(--color-foreground)] cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 text-(--color) hover:bg-(--color)/5 h-10 px-4 py-2 {{ $currentPage == 3 ? 'bg-background box rounded-xl border-inherit' : 'border-transparent bg-transparent' }}">
                                                    3
                                                </a>
                                            @endif
                                        </li>
                                        <li class="flex-1 sm:flex-initial">
                                            <a class="[--color:var(--color-foreground)] cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 text-(--color) hover:bg-(--color)/5 h-10 px-4 py-2 border-transparent bg-transparent">
                                                ...
                                            </a>
                                        </li>
                                        <li class="flex-1 sm:flex-initial">
                                            @if($paginator && $currentPage < $totalPages)
                                                <a wire:click="gotoPage({{ $currentPage + 1 }})" class="[--color:var(--color-foreground)] cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 text-(--color) hover:bg-(--color)/5 h-10 px-4 py-2 border-transparent bg-transparent">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="chevron-right" class="lucide lucide-chevron-right size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25"><path d="m9 18 6-6-6-6"></path></svg>
                                                </a>
                                            @elseif($onPageChange && $currentPage < $totalPages)
                                                <a wire:click="{{ $onPageChange }}({{ $currentPage + 1 }})" class="[--color:var(--color-foreground)] cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 text-(--color) hover:bg-(--color)/5 h-10 px-4 py-2 border-transparent bg-transparent">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="chevron-right" class="lucide lucide-chevron-right size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25"><path d="m9 18 6-6-6-6"></path></svg>
                                                </a>
                                            @else
                                                <a class="[--color:var(--color-foreground)] cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 text-(--color) hover:bg-(--color)/5 h-10 px-4 py-2 border-transparent bg-transparent opacity-50 pointer-events-none">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="chevron-right" class="lucide lucide-chevron-right size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25"><path d="m9 18 6-6-6-6"></path></svg>
                                                </a>
                                            @endif
                                        </li>
                                        <li class="flex-1 sm:flex-initial">
                                            @if($paginator && $currentPage < $totalPages)
                                                <a wire:click="gotoPage({{ $totalPages }})" class="[--color:var(--color-foreground)] cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 text-(--color) hover:bg-(--color)/5 h-10 px-4 py-2 border-transparent bg-transparent">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="chevrons-right" class="lucide lucide-chevrons-right size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25"><path d="m6 17 5-5-5-5"></path><path d="m13 17 5-5-5-5"></path></svg>
                                                </a>
                                            @elseif($onPageChange && $currentPage < $totalPages)
                                                <a wire:click="{{ $onPageChange }}({{ $totalPages }})" class="[--color:var(--color-foreground)] cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 text-(--color) hover:bg-(--color)/5 h-10 px-4 py-2 border-transparent bg-transparent">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="chevrons-right" class="lucide lucide-chevrons-right size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25"><path d="m6 17 5-5-5-5"></path><path d="m13 17 5-5-5-5"></path></svg>
                                                </a>
                                            @else
                                                <a class="[--color:var(--color-foreground)] cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 text-(--color) hover:bg-(--color)/5 h-10 px-4 py-2 border-transparent bg-transparent opacity-50 pointer-events-none">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="chevrons-right" class="lucide lucide-chevrons-right size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25"><path d="m6 17 5-5-5-5"></path><path d="m13 17 5-5-5-5"></path></svg>
                                                </a>
                                            @endif
                                        </li>
                                    </ul>
                                </nav>
                                @if($showPerPageSelect)
                                <select 
                                    class="bg-(image:--background-image-chevron) bg-[position:calc(100%-theme(spacing.3))_center] bg-[size:theme(spacing.5)] bg-no-repeat relative appearance-none flex h-10 rounded-md border bg-background px-3 py-2 ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 box mt-3 w-20 sm:mt-0"
                                    @if($onPerPageChange) wire:change="{{ $onPerPageChange }}" @endif
                                >
                                    @foreach($perPageOptions as $option)
                                        <option value="{{ $option }}" {{ $option == $perPage ? 'selected' : '' }}>
                                            {{ $option }}
                                        </option>
                                    @endforeach
                                </select>
                                @endif
                            </div>