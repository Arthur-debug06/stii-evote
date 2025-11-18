<div class="top-bar group -mt-2 [&.scrolled]:sticky [&.scrolled]:inset-x-0 [&.scrolled]:top-0 [&.scrolled]:z-[999] [&.scrolled]:mt-0">
                        <div class="flex h-16 items-center gap-5 border-b transition-all group-[.scrolled]:px-5 group-[.scrolled]:rounded-2xl group-[.scrolled]:bg-background group-[.scrolled]:border group-[.scrolled]:shadow-lg group-[.scrolled]:shadow-foreground/5">
                            <div class="open-mobile-menu bg-background mr-auto flex size-9 cursor-pointer items-center justify-center rounded-xl border xl:hidden">
                                <i data-lucide="chart-no-axes-column" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 rotate-90"></i>
                            </div>
                            <ul class="truncate gap-x-6 [--color-link:var(--color-primary)] [--color-base:var(--color-foreground)] mr-auto hidden xl:flex">
                                <li class="[&:not(:last-child)&gt;a]:text-(--color-link) text-(--color-base) before:bg-(image:--background-image-chevron) relative before:absolute before:inset-y-0 before:my-auto before:-ml-4 before:size-2 before:-rotate-90 before:bg-center before:bg-no-repeat before:opacity-70 first:before:hidden">
                                    <a href="">Apps</a>
                                </li>
                                <li class="[&:not(:last-child)&gt;a]:text-(--color-link) text-(--color-base) before:bg-(image:--background-image-chevron) relative before:absolute before:inset-y-0 before:my-auto before:-ml-4 before:size-2 before:-rotate-90 before:bg-center before:bg-no-repeat before:opacity-70 first:before:hidden">
                                    <a href="">Dashboards</a>
                                </li>
                                <li class="[&:not(:last-child)&gt;a]:text-(--color-link) text-(--color-base) before:bg-(image:--background-image-chevron) relative before:absolute before:inset-y-0 before:my-auto before:-ml-4 before:size-2 before:-rotate-90 before:bg-center before:bg-no-repeat before:opacity-70 first:before:hidden">
                                    <a href="">Overview</a>
                                </li>
                            </ul>
                            <!-- <div class="quick-search-toggle bg-background hover:ring-foreground/5 flex h-9 cursor-pointer items-center rounded-full border px-4 ring-1 ring-transparent ring-offset-2 ring-offset-transparent">
                                <div class="flex items-center gap-3 opacity-70">
                                    <i data-lucide="search" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25"></i>
                                    ⌘K
                                </div>
                            </div> -->
                            <div class="group/notifications relative flex h-9 items-center">
                                <i data-lucide="bell" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 cursor-pointer" onclick="showNotificationToast()"></i>
                                
                                <!-- Notification scripts removed to prevent duplication -->
                                
                                @php
                                    // Get current user ID from either guard
                                    $currentUserId = null;
                                    $currentUserType = null;
                                    
                                    if (auth()->check()) {
                                        $currentUserId = auth()->id();
                                        $currentUserType = 'App\\Models\\User';
                                    } elseif (auth()->guard('students')->check()) {
                                        $currentUserId = auth()->guard('students')->id();
                                        $currentUserType = 'App\\Models\\students';
                                    }
                                    
                                    // Get notifications for current user where they are the notifiable_id (the one who was notified)
                                    $notifications = $currentUserId ? 
                                        \App\Models\Notification::where('user_id', $currentUserId)
                                            ->where('notifiable_id', (string) $currentUserId)
                                            ->where('notifiable_type', $currentUserType)
                                            ->orderBy('created_at', 'desc')
                                            ->limit(5)
                                            ->get() : 
                                        collect();
                                    
                                    // Get unread notifications count for the bell badge
                                    $unreadCount = $currentUserId ? 
                                        \App\Models\Notification::where('user_id', $currentUserId)
                                            ->where('notifiable_id', (string) $currentUserId)
                                            ->where('notifiable_type', $currentUserType)
                                            ->whereNull('read_at')
                                            ->count() : 
                                        0;
                                @endphp
                                
                                @if($unreadCount > 0)
                                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                        {{ $unreadCount }}
                                    </span>
                                @endif
                                
                                <div class="hidden" id="notification-dropdown">
                                    <div class="box p-5 before:absolute before:inset-0 before:mx-3 before:-mb-3 before:border before:border-foreground/10 before:bg-background/30 before:z-[-1] after:absolute after:inset-0 after:border after:border-foreground/10 after:bg-background after:shadow-[0px_3px_5px_#0000000b] after:z-[-1] after:backdrop-blur-md before:shadow-foreground/5 absolute right-0 top-0 z-50 -mr-0.5 -mt-0.5 flex w-96 flex-col gap-2.5 px-6 py-5 before:rounded-2xl before:shadow-xl before:backdrop-blur after:rounded-2xl">
                                        <div class="flex place-content-between items-center">
                                            <div class="font-medium">Notifications</div>
                                            <div class="flex items-center gap-2">
                                                <a class="text-primary text-xs" href="">View More</a>
                                                <button onclick="closeNotificationDropdown()" class="text-gray-500 hover:text-gray-700 transition-colors">
                                                    <i data-lucide="x" class="size-4 stroke-[1.5]"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="mt-1 flex flex-col gap-2.5">
                                            @forelse($notifications as $notification)
                                                <a class="hover:border-foreground/10 hover:bg-foreground/5 -mx-2 flex items-center gap-3.5 rounded-2xl border border-transparent p-2 {{ $notification->isUnread() ? 'bg-blue-50 border-blue-200' : '' }}" 
                                                   href="{{ $notification->url ?? '#' }}"
                                                   onclick="markAsRead({{ $notification->id }})">
                                                    <span class="tooltip border-(--color)/5 block relative flex-none overflow-hidden rounded-full border-3 ring-1 ring-(--color)/25 [--color:var(--color-primary)] size-11">
                                                        <div class="absolute top-0 size-full flex items-center justify-center bg-{{ $notification->icon_color ?? 'primary' }}-100">
                                                            <i data-lucide="{{ $notification->icon ?? 'bell' }}" class="size-5 text-{{ $notification->icon_color ?? 'primary' }}-600"></i>
                                                        </div>
                                                    </span>
                                                    <div class="flex flex-col gap-1">
                                                        <div class="flex place-content-between items-center">
                                                            <div class="font-medium">{{ $notification->title }}</div>
                                                            <div class="text-xs opacity-70">{{ $notification->created_at->format('h:i A') }}</div>
                                                        </div>
                                                        <div class="line-clamp-2 text-xs opacity-70">
                                                            {{ $notification->message }}
                                                        </div>
                                                        @if($notification->status)
                                                            <div class="text-xs">
                                                                <span class="px-2 py-1 rounded-full text-xs bg-{{ $notification->status === 'approved' ? 'green' : ($notification->status === 'rejected' ? 'red' : 'yellow') }}-100 text-{{ $notification->status === 'approved' ? 'green' : ($notification->status === 'rejected' ? 'red' : 'yellow') }}-800">
                                                                    {{ ucfirst($notification->status) }}
                                                                </span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </a>
                                            @empty
                                                <div class="text-center py-4 text-sm opacity-70">
                                                    No notifications yet
                                                </div>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="group/profile relative size-9 flex-none">
                                <span data-content="" class="tooltip border-(--color)/5 block relative flex-none overflow-hidden rounded-full border-3 ring-1 ring-(--color)/25 [--color:var(--color-primary)] size-full">
                                    <img class="absolute top-0 size-full object-cover" src="dist/images/fakers/profile-2.jpg" alt="Midone - Tailwind Admin Dashboard Template">
                                </span>
                                <div class="hidden group-hover/profile:block">
                                    <div class="box p-5 before:absolute before:inset-0 before:mx-3 before:-mb-3 before:border before:border-foreground/10 before:bg-background/30 before:z-[-1] after:absolute after:inset-0 after:border after:border-foreground/10 after:bg-background after:shadow-[0px_3px_5px_#0000000b] after:z-[-1] after:backdrop-blur-md before:shadow-foreground/5 absolute right-0 top-0 z-50 -mr-0.5 -mt-0.5 flex w-64 flex-col gap-2.5 px-6 py-5 before:rounded-2xl before:shadow-xl before:backdrop-blur after:rounded-2xl">
                                        <div class="flex flex-col gap-0.5">
                                            <div class="font-medium">Arnold Schwarzenegger</div>
                                            <div class="mt-0.5 text-xs opacity-70">Backend Engineer</div>
                                        </div>
                                        <div class="bg-foreground/5 h-px"></div>
                                        <div class="flex flex-col gap-0.5">
                                            <a class="hover:bg-foreground/5 -mx-3 flex gap-2.5 rounded-lg px-4 py-1.5" href="">
                                                <i data-lucide="users" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25"></i>
                                                Profile
                                            </a>
                                            <a class="hover:bg-foreground/5 -mx-3 flex gap-2.5 rounded-lg px-4 py-1.5" href="">
                                                <i data-lucide="shield-alert" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25"></i>
                                                Add Account
                                            </a>
                                            <a class="hover:bg-foreground/5 -mx-3 flex gap-2.5 rounded-lg px-4 py-1.5" href="">
                                                <i data-lucide="file-lock" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25"></i>
                                                Reset Password
                                            </a>
                                            <a class="hover:bg-foreground/5 -mx-3 flex gap-2.5 rounded-lg px-4 py-1.5" href="">
                                                <i data-lucide="file-question" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25"></i>
                                                Help
                                            </a>
                                        </div>
                                        <div class="bg-foreground/5 h-px"></div>
                                        <div class="flex flex-col gap-0.5">
                                            <a class="hover:bg-foreground/5 -mx-3 flex gap-2.5 rounded-lg px-4 py-1.5" href="{{route('logout')}}">
                                                <i data-lucide="power" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25"></i>
                                                Logout
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div> -->
                        </div>
                        <!-- BEGIN: Quick Search Modal -->
                        <div data-tw-backdrop="" class="modal group bg-black/60 transition-[visibility,opacity] w-screen h-screen fixed left-0 top-0 [&:not(.show)]:duration-[0s,0.2s] [&:not(.show)]:delay-[0.2s,0s] [&:not(.show)]:invisible [&:not(.show)]:opacity-0 [&.show]:visible [&.show]:opacity-100 [&.show]:duration-[0s,0.4s]" id="quick-search-modal">
                            <div class="box relative before:absolute before:inset-0 before:mx-3 before:-mb-3 before:border before:border-foreground/10 before:z-[-1] after:absolute after:inset-0 after:border after:border-foreground/10 after:bg-background after:shadow-[0px_3px_5px_#0000000b] after:z-[-1] after:backdrop-blur-md before:bg-background/60 dark:before:shadow-background before:shadow-foreground/60 z-50 mx-auto -mt-16 p-6 transition-[margin-top,transform] duration-[0.4s,0.3s] before:rounded-3xl before:shadow-2xl after:rounded-3xl group-[.show]:mt-16 group-[.modal-static]:scale-[1.05] sm:max-w-2xl">
                                <div class="-mx-5 -mt-5">
                                    <div class="relative border-b border-dashed">
                                        <i data-lucide="search" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 absolute inset-y-0 my-auto ml-4"></i>
                                        <input class="rounded-md border px-3 py-2 ring-offset-background file:border-0 file:bg-transparent file:font-medium file:text-foreground placeholder:text-foreground/70 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 w-full pl-12 bg-transparent border-none pr-19 h-14 focus:ring-offset-transparent focus-visible:ring-transparent" type="text" placeholder="Quick search...">
                                        <div class="absolute inset-y-0 right-0 flex items-center h-6 px-2 my-auto mr-4 border rounded-lg">
                                            esc</div>
                                    </div>
                                    <div class="flex flex-wrap gap-2 px-5 mt-4">
                                        <a class="hover:bg-foreground/5 flex items-center gap-x-1.5 rounded-full border px-3 py-0.5" href="">
                                            <i data-lucide="users2" class="stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 size-4"></i>
                                            Users
                                        </a>
                                        <a class="hover:bg-foreground/5 flex items-center gap-x-1.5 rounded-full border px-3 py-0.5" href="">
                                            <i data-lucide="building2" class="stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 size-4"></i>
                                            Departments
                                        </a>
                                        <a class="hover:bg-foreground/5 flex items-center gap-x-1.5 rounded-full border px-3 py-0.5" href="">
                                            <i data-lucide="kanban-square" class="stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 size-4"></i>
                                            Products
                                        </a>
                                        <a class="hover:bg-foreground/5 flex items-center gap-x-1.5 rounded-full border px-3 py-0.5" href="">
                                            <i data-lucide="mail-check" class="stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 size-4"></i>
                                            Mails
                                        </a>
                                    </div>
                                    <div class="px-5 py-4">
                                        <div class="flex items-center">
                                            <div class="text-xs uppercase opacity-70">
                                                Users
                                            </div>
                                            <a class="ml-auto text-xs opacity-70" href="">
                                                See All
                                            </a>
                                        </div>
                                        <div class="mt-3.5 flex flex-col gap-1">
                                            <a class="hover:border-foreground/10 hover:bg-foreground/5 -mx-1 flex items-center gap-2.5 rounded-xl border border-transparent p-1" href="">
                                                <span data-content="" class="tooltip border-(--color)/5 block relative flex-none overflow-hidden rounded-full border-3 ring-1 ring-(--color)/25 [--color:var(--color-primary)] size-8">
                                                    <img class="absolute top-0 size-full object-cover" src="dist/images/fakers/profile-4.jpg" alt="Midone - Tailwind Admin Dashboard Template">
                                                </span>
                                                <div class="font-medium truncate">
                                                    Tom Cruise
                                                </div>
                                                <div class="hidden opacity-70 sm:block">
                                                    Frontend Engineer
                                                </div>
                                            </a>
                                            <a class="hover:border-foreground/10 hover:bg-foreground/5 -mx-1 flex items-center gap-2.5 rounded-xl border border-transparent p-1" href="">
                                                <span data-content="" class="tooltip border-(--color)/5 block relative flex-none overflow-hidden rounded-full border-3 ring-1 ring-(--color)/25 [--color:var(--color-primary)] size-8">
                                                    <img class="absolute top-0 size-full object-cover" src="dist/images/fakers/profile-13.jpg" alt="Midone - Tailwind Admin Dashboard Template">
                                                </span>
                                                <div class="font-medium truncate">
                                                    Will Smith
                                                </div>
                                                <div class="hidden opacity-70 sm:block">
                                                    DevOps Engineer
                                                </div>
                                            </a>
                                            <a class="hover:border-foreground/10 hover:bg-foreground/5 -mx-1 flex items-center gap-2.5 rounded-xl border border-transparent p-1" href="">
                                                <span data-content="" class="tooltip border-(--color)/5 block relative flex-none overflow-hidden rounded-full border-3 ring-1 ring-(--color)/25 [--color:var(--color-primary)] size-8">
                                                    <img class="absolute top-0 size-full object-cover" src="dist/images/fakers/profile-5.jpg" alt="Midone - Tailwind Admin Dashboard Template">
                                                </span>
                                                <div class="font-medium truncate">
                                                    Arnold Schwarzenegger
                                                </div>
                                                <div class="hidden opacity-70 sm:block">
                                                    Backend Engineer
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="px-5 py-4 border-t border-dashed">
                                        <div class="flex items-center">
                                            <div class="text-xs uppercase opacity-70">
                                                Departments
                                            </div>
                                            <a class="ml-auto text-xs opacity-70" href="">
                                                See All
                                            </a>
                                        </div>
                                        <div class="mt-3.5 flex flex-col gap-1">
                                            <a class="hover:border-foreground/10 hover:bg-foreground/5 -mx-1 flex items-center gap-2.5 rounded-xl border border-transparent p-1" href="">
                                                <div class="flex items-center justify-center overflow-hidden border rounded-lg border-primary/10 bg-primary/10 text-primary size-8">
                                                    <i data-lucide="hotel" class="size-4 [--color:currentColor] stroke-(--color) fill-(--color)/25 stroke-1"></i>
                                                </div>
                                                <div class="font-medium truncate">
                                                    Samsung Galaxy S20 Ultra
                                                </div>
                                                <div class="hidden opacity-70 sm:block">
                                                    Smartphone & Tablet
                                                </div>
                                            </a>
                                            <a class="hover:border-foreground/10 hover:bg-foreground/5 -mx-1 flex items-center gap-2.5 rounded-xl border border-transparent p-1" href="">
                                                <div class="flex items-center justify-center overflow-hidden border rounded-lg border-primary/10 bg-primary/10 text-primary size-8">
                                                    <i data-lucide="store" class="size-4 [--color:currentColor] stroke-(--color) fill-(--color)/25 stroke-1"></i>
                                                </div>
                                                <div class="font-medium truncate">
                                                    Samsung Galaxy S20 Ultra
                                                </div>
                                                <div class="hidden opacity-70 sm:block">
                                                    Smartphone & Tablet
                                                </div>
                                            </a>
                                            <a class="hover:border-foreground/10 hover:bg-foreground/5 -mx-1 flex items-center gap-2.5 rounded-xl border border-transparent p-1" href="">
                                                <div class="flex items-center justify-center overflow-hidden border rounded-lg border-primary/10 bg-primary/10 text-primary size-8">
                                                    <i data-lucide="hotel" class="size-4 [--color:currentColor] stroke-(--color) fill-(--color)/25 stroke-1"></i>
                                                </div>
                                                <div class="font-medium truncate">
                                                    Apple MacBook Pro 13
                                                </div>
                                                <div class="hidden opacity-70 sm:block">
                                                    PC & Laptop
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="px-5 py-4 border-t border-dashed">
                                        <div class="flex items-center">
                                            <div class="text-xs uppercase opacity-70">
                                                Products
                                            </div>
                                            <a class="ml-auto text-xs opacity-70" href="">
                                                See All
                                            </a>
                                        </div>
                                        <div class="mt-3.5 flex flex-col gap-1">
                                            <a class="hover:border-foreground/10 hover:bg-foreground/5 -mx-1 flex items-center gap-2.5 rounded-xl border border-transparent p-1" href="">
                                                <span data-content="" class="tooltip border-(--color)/5 block relative flex-none overflow-hidden rounded-full border-3 ring-1 ring-(--color)/25 [--color:var(--color-primary)] size-8">
                                                    <img class="absolute top-0 size-full object-cover" src="dist/images/fakers/preview-11.jpg" alt="Midone - Tailwind Admin Dashboard Template">
                                                </span>
                                                <div class="font-medium truncate">
                                                    Fried/Grilled Banana
                                                </div>
                                                <div class="hidden opacity-70 sm:block">
                                                    Photography
                                                </div>
                                            </a>
                                            <a class="hover:border-foreground/10 hover:bg-foreground/5 -mx-1 flex items-center gap-2.5 rounded-xl border border-transparent p-1" href="">
                                                <span data-content="" class="tooltip border-(--color)/5 block relative flex-none overflow-hidden rounded-full border-3 ring-1 ring-(--color)/25 [--color:var(--color-primary)] size-8">
                                                    <img class="absolute top-0 size-full object-cover" src="dist/images/fakers/preview-9.jpg" alt="Midone - Tailwind Admin Dashboard Template">
                                                </span>
                                                <div class="font-medium truncate">
                                                    Fried/Grilled Banana
                                                </div>
                                                <div class="hidden opacity-70 sm:block">
                                                    PC & Laptop
                                                </div>
                                            </a>
                                            <a class="hover:border-foreground/10 hover:bg-foreground/5 -mx-1 flex items-center gap-2.5 rounded-xl border border-transparent p-1" href="">
                                                <span data-content="" class="tooltip border-(--color)/5 block relative flex-none overflow-hidden rounded-full border-3 ring-1 ring-(--color)/25 [--color:var(--color-primary)] size-8">
                                                    <img class="absolute top-0 size-full object-cover" src="dist/images/fakers/preview-7.jpg" alt="Midone - Tailwind Admin Dashboard Template">
                                                </span>
                                                <div class="font-medium truncate">
                                                    Snack Platter
                                                </div>
                                                <div class="hidden opacity-70 sm:block">
                                                    Electronic
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- END: Quick Search Modal -->
                    </div>

                    <!-- Notification JavaScript -->
                    <script>
                        function markAsRead(notificationId) {
                            fetch(`/notifications/${notificationId}/mark-read`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    // Remove the unread styling
                                    const notificationElement = document.querySelector(`[onclick="markAsRead(${notificationId})"]`);
                                    if (notificationElement) {
                                        notificationElement.classList.remove('bg-blue-50', 'border-blue-200');
                                    }
                                }
                            })
                            .catch(error => {
                                console.error('Error marking notification as read:', error);
                            });
                        }

                        function testToast() {
                            if (window.showMenuToast) {
                                window.showMenuToast({
                                    type: 'success',
                                    title: 'Test Toast for User Account',
                                    message: 'This toast notification is working for User accounts!'
                                });
                            } else {
                                console.log('showMenuToast function not available');
                                // Fallback: dispatch custom event
                                window.dispatchEvent(new CustomEvent('show-toast', {
                                    detail: {
                                        type: 'success',
                                        title: 'Test Toast for User Account',
                                        message: 'This toast notification is working for User accounts!'
                                    }
                                }));
                            }
                        }

                        function triggerLoginToast() {
                            if (window.showMenuToast) {
                                // Simulate login notification
                                window.showMenuToast({
                                    type: 'success',
                                    title: 'Welcome Back!',
                                    message: 'You have successfully logged in to the system.',
                                    autoHideMs: 10000
                                });
                                
                                // Show additional notifications after a delay
                                setTimeout(() => {
                                    window.showMenuToast({
                                        type: 'info',
                                        title: 'System Update',
                                        message: 'New features are now available in your dashboard.',
                                        autoHideMs: 10000
                                    });
                                }, 2000);
                                
                                setTimeout(() => {
                                    window.showMenuToast({
                                        type: 'warning',
                                        title: 'Reminder',
                                        message: 'Please update your profile information if needed.',
                                        autoHideMs: 10000
                                    });
                                }, 4000);
                            } else {
                                console.log('showMenuToast function not available');
                            }
                        }

                        function showNotificationToast() {
                            // Toggle notification dropdown
                            const dropdown = document.getElementById('notification-dropdown');
                            if (dropdown) {
                                dropdown.classList.toggle('hidden');
                            }
                            
                            // No toast message - just toggle the dropdown
                        }

                        function closeNotificationDropdown() {
                            const dropdown = document.getElementById('notification-dropdown');
                            if (dropdown) {
                                dropdown.classList.add('hidden');
                            }
                        }
                    </script>