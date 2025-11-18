@section('title', 'Notifications')

<div>
    <!-- Toast Notification Template -->
    <x-menu.notification-toast seconds="10" layout="compact" animated="true" />
    
    <h2 class="mt-10 text-lg font-medium">Action Center</h2>
    <div class="mt-5 grid grid-cols-12 gap-6">
        <!-- Left Sidebar - Notifications Menu -->
        <div class="col-span-12 lg:col-span-3">
            <!-- Notifications Menu -->
            <div class="box relative before:absolute before:inset-0 before:mx-3 before:-mb-3 before:border before:border-foreground/10 before:bg-background/30 before:shadow-[0px_3px_5px_#0000000b] before:z-[-1] before:rounded-xl after:absolute after:inset-0 after:border after:border-foreground/10 after:bg-background after:shadow-[0px_3px_5px_#0000000b] after:rounded-xl after:z-[-1] after:backdrop-blur-md p-5">
                <h3 class="text-lg font-medium mb-4">Notifications</h3>
                <button wire:click="markAllAsRead" class="cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 bg-(--color)/20 border-(--color)/60 text-(--color) hover:bg-(--color)/5 [--color:var(--color-primary)] h-10 px-4 py-2 w-full mb-4" type="button">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="check" class="lucide lucide-check stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 size-4"><path d="M20 6 9 17l-5-5"></path></svg>
                    Mark All as Read
                </button>
                
                <div class="space-y-2">
                    <button wire:click="$set('filterStatus', '')" class="{{ $filterStatus === '' ? 'bg-foreground/5 border-foreground/10' : '' }} flex items-center rounded-md border border-transparent px-3 py-2 hover:bg-foreground/5 transition-colors w-full">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="bell" class="lucide lucide-bell stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 mr-2 size-4"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
                        All Notifications
                        <div class="bg-(--color)/20 border-(--color)/60 text-(--color) flex cursor-pointer items-center rounded-full border px-2 py-px text-xs ms-auto [--color:var(--color-primary)]">
                            {{ $totalCount }}
                        </div>
            </button>
                    <button wire:click="$set('filterStatus', 'unread')" class="{{ $filterStatus === 'unread' ? 'bg-foreground/5 border-foreground/10' : '' }} flex items-center rounded-md border border-transparent px-3 py-2 hover:bg-foreground/5 transition-colors w-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="mail" class="lucide lucide-mail stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 mr-2 size-4"><rect width="20" height="16" x="2" y="4" rx="2"></rect><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path></svg>
                        Unread
                    <div class="bg-(--color)/20 border-(--color)/60 text-(--color) flex cursor-pointer items-center rounded-full border px-2 py-px text-xs ms-auto [--color:var(--color-warning)]">
                            {{ $unreadCount }}
                    </div>
                    </button>
                    <button wire:click="$set('filterStatus', 'read')" class="{{ $filterStatus === 'read' ? 'bg-foreground/5 border-foreground/10' : '' }} flex items-center rounded-md border border-transparent px-3 py-2 hover:bg-foreground/5 transition-colors w-full">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="check-circle" class="lucide lucide-check-circle stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 mr-2 size-4"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><path d="m9 11 3 3L22 4"></path></svg>
                        Read
                    </button>
                    <button wire:click="$set('filterStatus', 'pending')" class="{{ $filterStatus === 'pending' ? 'bg-foreground/5 border-foreground/10' : '' }} flex items-center rounded-md border border-transparent px-3 py-2 hover:bg-foreground/5 transition-colors w-full">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="clock" class="lucide lucide-clock stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 mr-2 size-4"><circle cx="12" cy="12" r="10"></circle><polyline points="12,6 12,12 16,14"></polyline></svg>
                        Pending
                    </button>
                    <button wire:click="$set('filterStatus', 'approved')" class="{{ $filterStatus === 'approved' ? 'bg-foreground/5 border-foreground/10' : '' }} flex items-center rounded-md border border-transparent px-3 py-2 hover:bg-foreground/5 transition-colors w-full">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="thumbs-up" class="lucide lucide-thumbs-up stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 mr-2 size-4"><path d="M7 10v12"></path><path d="M15 5.88 14 10h5.83a2 2 0 0 1 1.92 2.56l-2.33 8A2 2 0 0 1 17.5 22H4a2 2 0 0 1-2-2v-8a2 2 0 0 1 2-2h2.76a2 2 0 0 0 1.79-1.11L12 2h0a3.13 3.13 0 0 1 3 3.88Z"></path></svg>
                        Approved
                    </button>
                    <button wire:click="$set('filterStatus', 'rejected')" class="{{ $filterStatus === 'rejected' ? 'bg-foreground/5 border-foreground/10' : '' }} flex items-center rounded-md border border-transparent px-3 py-2 hover:bg-foreground/5 transition-colors w-full">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="thumbs-down" class="lucide lucide-thumbs-down stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 mr-2 size-4"><path d="M17 14V2"></path><path d="M9 18.12 10 14H4.17a2 2 0 0 1-1.92-2.56l2.33-8A2 2 0 0 1 6.5 2H20a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2h-2.76a2 2 0 0 0-1.79 1.11L12 22h0a3.13 3.13 0 0 1-3-3.88Z"></path></svg>
                        Rejected
                    </button>
                    </div>
                
            <div class="mt-4 border-t border-dashed pt-4">
                    <button wire:click="clearFilters" class="flex items-center truncate px-3 py-2 hover:bg-foreground/5 transition-colors rounded-md w-full">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="x" class="lucide lucide-x stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 mr-2 size-4"><path d="M18 6 6 18"></path><path d="M6 6l12 12"></path></svg>
                        Clear Filters
                    </button>
                    </div>
            </div>
        </div>
        
        <!-- Main Content - Notifications List -->
        <div class="col-span-12 lg:col-span-9">
            <!-- Notifications List -->
            <div class="col-span-12">
                <div class="box relative before:absolute before:inset-0 before:mx-3 before:-mb-3 before:border before:border-foreground/10 before:bg-background/30 before:shadow-[0px_3px_5px_#0000000b] before:z-[-1] before:rounded-xl after:absolute after:inset-0 after:border after:border-foreground/10 after:bg-background after:shadow-[0px_3px_5px_#0000000b] after:rounded-xl after:z-[-1] after:backdrop-blur-md p-0 h-96">
                    <div class="overflow-y-auto h-full">
                        @forelse($notifications as $notification)
                        <div class="transition duration-200 ease-in-out transform border-b border-foreground/10 hover:bg-background/50 {{ $notification->read_at ? 'opacity-70' : 'bg-background/30' }}">
                            @if($notification->url)
                                <a href="{{ $notification->url }}" 
                                   onclick="Livewire.emit('markAsRead', {{ $notification->id }})"
                                   class="block flex px-5 py-4 hover:bg-background/70 transition-colors cursor-pointer">
                            @else
                                <div class="flex px-5 py-4">
                            @endif
                                <div class="mr-4 flex w-8 flex-none items-center justify-center">
                                    @if($notification->read_at)
                                        <div class="size-2 rounded-full bg-foreground/30"></div>
                                    @else
                                        <div class="size-2 rounded-full bg-primary"></div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-sm font-medium text-foreground truncate">
                                                {{ $notification->title }}
                                            </h3>
                                            <p class="mt-1 text-sm text-foreground/70 line-clamp-2">
                                                {{ $notification->message }}
                                            </p>
                                            <div class="mt-2 flex items-center gap-4 text-xs text-foreground/50">
                                                <span>{{ $notification->created_at->format('M d, Y h:i A') }}</span>
                                                @if($notification->status)
                                                <span class="px-2 py-1 rounded-full text-xs font-medium
                                                    {{ $notification->status === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300' : '' }}
                                                    {{ $notification->status === 'approved' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' : '' }}
                                                    {{ $notification->status === 'rejected' ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300' : '' }}
                                                    {{ !in_array($notification->status, ['pending', 'approved', 'rejected']) ? 'bg-slate-100 text-slate-800 dark:bg-slate-900/30 dark:text-slate-300' : '' }}">
                                                    {{ ucfirst($notification->status) }}
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="ml-4 flex items-center gap-2">
                                            @if($notification->status === 'pending' && $notification->documentable_type === 'App\\Models\\applied_candidacy')
                                               
                                            @else
                                                @if(!$notification->read_at && !$notification->url)
                                                    <button type="button" 
                                                            onclick="(function(){
                                                                try {
                                                                    var payload = { type: 'success', title: 'Notification', message: 'Marked as read' };
                                                                    if (window.showMenuToastThen) {
                                                                        window.showMenuToastThen(payload, function(){
                                                                            try { Livewire.emit('markAsRead', {{ $notification->id }}); } catch(_) {}
                                                                        });
                                                                    } else {
                                                                        try { Livewire.emit('markAsRead', {{ $notification->id }}); } catch(_) {}
                                                                    }
                                                                } catch(_) { try { Livewire.emit('markAsRead', {{ $notification->id }}); } catch(_) {} }
                                                            })()"
                                                            class="p-1 text-foreground/50 hover:text-foreground/70 transition-colors"
                                                            title="Mark as read">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="check" class="lucide lucide-check">
                                                            <path d="M20 6 9 17l-5-5"></path>
                                                        </svg>
                                                    </button>
                                                @elseif($notification->url)
                                                    <div class="p-1 text-foreground/50" title="Click to view">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="external-link" class="lucide lucide-external-link">
                                                            <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                                            <polyline points="15,3 21,3 21,9"></polyline>
                                                            <line x1="10" y1="14" x2="21" y2="3"></line>
                                                        </svg>
                                                    </div>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @if($notification->url)
                                </a>
                            @else
                                </div>
                            @endif
                        </div>
                        @empty
                        <div class="px-5 py-12 text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="bell-off" class="lucide lucide-bell-off mx-auto mb-4 text-foreground/30">
                                <path d="M8.5 8.5c.5-2.5 2.79-4 5.5-4 2.71 0 5 1.5 5.5 4"></path>
                                <path d="M14 14H9s-3 0-3-3 3-3 3-3"></path>
                                <path d="M22 8.5c0 2.79-2.21 5-5 5s-5-2.21-5-5 2.21-5 5-5 5 2.21 5 5Z"></path>
                                <path d="M2 2l20 20"></path>
                            </svg>
                            <h3 class="text-lg font-medium text-foreground mb-2">No notifications found</h3>
                            <p class="text-foreground/70">
                                @if($search || $filterStatus || $filterType)
                                    No notifications match your current filters.
                                @else
                                    You don't have any notifications yet.
                                @endif
                            </p>
                        </div>
                        @endforelse
                        </div>
                    
                    <!-- Pagination -->
                    @if($notifications->hasPages())
                    <div class="border-t border-foreground/10 px-5 py-4">
                        <x-menu.pagination :paginator="$notifications" />
                    </div>
                    @endif
                    
                </div>
            </div>
        </div>
    </div>

    <!-- Decline Reason Modal -->
    <x-menu.modal 
        :showButton="false" 
        modalId="decline-reason-modal" 
        title="Decline Candidacy Application" 
        description="Please provide a reason for declining this candidacy application"
        size="md"
        :isOpen="$showDeclineModal">
        
        <form wire:submit.prevent="processDecline" class="space-y-4">
            <div class="grid gap-4 gap-y-3">
                <div class="grid grid-cols-4 items-start gap-4">
                    <label class="text-right text-sm font-medium pt-2" for="decline-reason">
                        Reason
                    </label>
                    <textarea 
                        wire:model="declineReason"
                        id="decline-reason"
                        class="col-span-3 w-full rounded-md border bg-background px-3 py-2 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5" 
                        rows="4" 
                        placeholder="Enter reason for declining..."
                        required
                    ></textarea>
                </div>
            </div>
        </form>

        <x-slot:footer>
            <button data-tw-dismiss="modal" type="button" wire:click="$set('showDeclineModal', false)" class="cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 text-foreground hover:bg-foreground/5 bg-background border-foreground/20 h-10 px-4 py-2 mr-1 w-24">
                Cancel
            </button>
            <button type="button" wire:click="processDecline" class="cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 bg-(--color)/20 border-(--color)/60 text-(--color) hover:bg-(--color)/5 [--color:var(--color-danger)] h-10 px-4 py-2 w-32">
                Decline
            </button>
        </x-slot:footer>
    </x-menu.modal>

    <!-- Approve Confirmation Modal -->
    <x-menu.modal 
        :showButton="false" 
        modalId="approve-confirmation-modal" 
        title="Approve Candidacy Application" 
        description="Are you sure you want to approve this candidacy application?"
        size="md"
        :isOpen="$showApproveModal">
        
        <div class="text-center py-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-10 h-10 text-green-500 mx-auto mb-3">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <path d="m9 11 3 3L22 4"></path>
            </svg>
            <div class="mt-2 text-sm">This action will approve the candidacy application and notify the student.</div>
        </div>

        <x-slot:footer>
            <button data-tw-dismiss="modal" type="button" wire:click="$set('showApproveModal', false)" class="cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 text-foreground hover:bg-foreground/5 bg-background border-foreground/20 h-10 px-4 py-2 mr-1 w-24">
                Cancel
            </button>
            <button type="button" wire:click="approveCandidacy({{ $approveNotificationId ?? 0 }})" class="cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 bg-(--color)/20 border-(--color)/60 text-(--color) hover:bg-(--color)/5 [--color:var(--color-success)] h-10 px-4 py-2 w-24">
                Approve
            </button>
        </x-slot:footer>
    </x-menu.modal>
</div>


