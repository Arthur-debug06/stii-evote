@props([
    'size' => 'md', // sm|md|lg|xl|2xl|3xl
    'triggerId' => 'open-modal',
    'modalId' => 'dialog-modal',
    'title' => 'Modal Dialog',
    'description' => '',
    'showButton' => true,
    'buttonText' => 'Show Dialog',
    'buttonVariant' => 'primary',
    'buttonIcon' => 'plus',
    'backdrop' => 'default', // default | static
    'isOpen' => false,
])

@php
    $sizeMap = [
        'sm' => 'sm:max-w-sm',
        'md' => 'sm:max-w-md', 
        'lg' => 'sm:max-w-lg',
        'xl' => 'sm:max-w-xl',
        '2xl' => 'sm:max-w-2xl',
        '3xl' => 'sm:max-w-3xl',
    ];
    $dialogSize = $sizeMap[$size] ?? $sizeMap['md'];
@endphp

<div class="flex justify-center">
    @if ($showButton)
    <button 
        data-tw-toggle="modal" 
        data-tw-target="#{{ $modalId }}" 
        id="{{ $triggerId }}"
        class="inline-flex items-center gap-2 rounded-md px-3 py-2 text-sm border shadow-sm bg-primary/10 border-primary/20 text-primary hover:bg-primary/15"
    >
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
            <path d="M5 12h14"></path>
            <path d="M12 5v14"></path>
        </svg>
        {{ $buttonText }}
    </button>
    @endif

    <!-- Modal -->
    <div id="{{ $modalId }}" class="modal group bg-black/60 transition-[visibility,opacity] w-screen h-screen fixed left-0 top-0 z-[9998] [&:not(.show)]:duration-[0s,0.2s] [&:not(.show)]:delay-[0.2s,0s] [&:not(.show)]:invisible [&:not(.show)]:opacity-0 [&.show]:visible [&.show]:opacity-100 [&.show]:duration-[0s,0.4s] {{ $isOpen ? 'show' : '' }} overflow-y-auto overflow-x-hidden">
        <div class="box relative before:absolute before:inset-0 before:mx-3 before:-mb-3 before:border before:border-foreground/10 before:z-[-1] after:absolute after:inset-0 after:border after:border-foreground/10 after:bg-background after:shadow-[0px_3px_5px_#0000000b] after:z-[-1] after:backdrop-blur-md before:bg-background/60 dark:before:shadow-background before:shadow-foreground/60 z-[9999] mx-auto -mt-16 p-6 transition-[margin-top,transform] duration-[0.4s,0.3s] before:rounded-3xl before:shadow-2xl after:rounded-3xl group-[.show]:mt-16 group-[.modal-static]:scale-[1.05] {{ $dialogSize }} my-8">
            
            <div class="space-y-0">
                @if($title || $description)
                <div class="p-5 text-center border-b border-foreground/10">
                    @if($title)
                    <h2 class="text-lg font-medium">{{ $title }}</h2>
                    @endif
                    @if($description)
                    <div class="mt-2 text-sm text-foreground/70">{{ $description }}</div>
                    @endif
                </div>
                @endif

                <div class="p-5">
                    {{ $slot }}
                </div>

                @if(isset($footer))
                <div class="px-5 pb-8 text-center border-t border-foreground/10 pt-4">
                    {{ $footer }}
                </div>
                @else
                <div class="px-5 pb-8 text-center border-t border-foreground/10 pt-4">
                    <button data-tw-dismiss="modal" type="button" class="cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 text-foreground hover:bg-foreground/5 bg-background border-foreground/20 h-10 px-4 py-2 mr-1 w-24">
                        Cancel
                    </button>
                    <button data-tw-dismiss="modal" type="button" class="cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 bg-(--color)/20 border-(--color)/60 text-(--color) hover:bg-(--color)/5 [--color:var(--color-primary)] h-10 px-4 py-2 w-24">
                        Save
                    </button>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>