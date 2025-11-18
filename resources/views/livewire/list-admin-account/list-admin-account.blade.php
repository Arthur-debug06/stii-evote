<div>
    <!-- Toast Notification Template -->
    <x-menu.notification-toast seconds="10" layout="compact" animated="true" />
    
    <!-- JavaScript Alert Listener -->
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('show-alert', (event) => {
                alert(event.title + '\n\n' + event.message);
            });
        });

        // Password toggle functionality
        function togglePassword(button) {
            const passwordSpan = button.previousElementSibling;
            const currentText = passwordSpan.textContent;
            const actualPassword = passwordSpan.getAttribute('data-password');
            
            if (currentText === '••••••••') {
                passwordSpan.textContent = actualPassword;
                button.textContent = 'Hide';
                button.classList.remove('text-blue-600', 'hover:text-blue-800');
                button.classList.add('text-red-600', 'hover:text-red-800');
            } else {
                passwordSpan.textContent = '••••••••';
                button.textContent = 'Show';
                button.classList.remove('text-red-600', 'hover:text-red-800');
                button.classList.add('text-blue-600', 'hover:text-blue-800');
            }
        }
    </script>
    
    <h2 class="mt-10 text-lg font-medium">Admin Account</h2>
    <div class="mt-5 grid grid-cols-12 gap-6">
        <div class="col-span-12 mt-2 flex flex-wrap items-center sm:flex-nowrap">
            <a href="{{ route('pdf.admin-account') }}" target="_blank" class="cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 bg-(--color)/20 border-(--color)/60 text-(--color) hover:bg-(--color)/5 [--color:var(--color-primary)] h-10 px-4 py-2 box mr-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="printer" class="lucide lucide-printer size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 h-4 w-4"><path d="M6 9V2h12v7"></path><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><path d="M6 14h12v8H6z"></path></svg>
                Print Admin List
            </a>
            <div class="mx-auto hidden opacity-70 md:block">
                @if(count($admins) > 0)
                    Showing {{ count($admins) }} admin(s)
                    @if(!empty($search))
                        (filtered from {{ \App\Models\User::count() }} total entries)
                    @endif
                @else
                    No admins found
                    @if(!empty($search))
                        for "{{ $search }}"
                    @endif
                @endif
            </div>
            <div class="mt-3 w-full sm:ml-auto sm:mt-0 sm:w-auto md:ml-0">
                <div class="relative w-56">
                    <input 
                        wire:model.live.debounce.300ms="search" 
                        class="h-10 rounded-md border bg-background px-3 py-2 ring-offset-background file:border-0 file:bg-transparent file:font-medium file:text-foreground placeholder:text-foreground/70 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 box w-56 pr-10" 
                        type="text" 
                        placeholder="Search admin name, email, role...">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="search" class="lucide lucide-search size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 absolute inset-y-0 right-0 my-auto mr-3 h-4 w-4"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.3-4.3"></path></svg>
                </div>
            </div>
        </div>
        
        <!-- BEGIN: Admins by Role -->
        @if(count($admins) > 0)
            @php
                // Group admins by role
                $adminsByRole = [];
                foreach($admins as $admin) {
                    $roleName = $admin->role ? ucfirst($admin->role) : 'No Role';
                    if (!isset($adminsByRole[$roleName])) {
                        $adminsByRole[$roleName] = collect();
                    }
                    $adminsByRole[$roleName]->push($admin);
                }
            @endphp
            
            @foreach($adminsByRole as $roleName => $roleAdmins)
                <!-- Role Header -->
                <div class="col-span-12 mb-4">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-blue-800">{{ $roleName }}</h3>
                        <p class="text-sm text-blue-600">
                            {{ count($roleAdmins) }} admin(s) with this role
                        </p>
                    </div>
                </div>

                <!-- Admins Grid -->
                @foreach($roleAdmins as $admin)
                    <div class="col-span-12 md:col-span-6 lg:col-span-4 xl:col-span-3">
                        <div class="box relative before:absolute before:inset-0 before:mx-3 before:-mb-3 before:border before:border-foreground/10 before:bg-background/30 before:shadow-[0px_3px_5px_#0000000b] before:z-[-1] before:rounded-xl after:absolute after:inset-0 after:border after:border-foreground/10 after:bg-background after:shadow-[0px_3px_5px_#0000000b] after:rounded-xl after:z-[-1] after:backdrop-blur-md p-0">
                            <div class="p-5">
                                <div class="image-fit h-40 overflow-hidden rounded-lg before:absolute before:left-0 before:top-0 before:z-10 before:block before:h-full before:w-full before:bg-gradient-to-t before:from-black before:to-black/10 2xl:h-56">
                                    @php
                                        $imageSrc = asset('images/placeholders/placeholder.jpg'); // Default placeholder
                                        
                                        // Check profile_image field first (profile_pictures folder)
                                        if ($admin->profile_image) {
                                            if (file_exists(public_path('storage/' . $admin->profile_image))) {
                                                $imageSrc = asset('storage/' . $admin->profile_image);
                                            }
                                        }
                                        
                                        // If no profile_image or file doesn't exist, check admin_images directory
                                        if ($imageSrc === asset('images/placeholders/placeholder.jpg')) {
                                            $adminImagesPath = storage_path('app/public/admin_images/');
                                            
                                            // Look for profile images with admin ID
                                            $profilePattern = $adminImagesPath . '*_profile_*' . $admin->id . '*';
                                            $profileFiles = glob($profilePattern);
                                            
                                            if (!empty($profileFiles)) {
                                                $profileFile = basename($profileFiles[0]);
                                                $imageSrc = asset('storage/admin_images/' . $profileFile);
                                            } else {
                                                // Look for ID images as fallback
                                                $idPattern = $adminImagesPath . '*_id_*' . $admin->id . '*';
                                                $idFiles = glob($idPattern);
                                                
                                                if (!empty($idFiles)) {
                                                    $idFile = basename($idFiles[0]);
                                                    $imageSrc = asset('storage/admin_images/' . $idFile);
                                                }
                                            }
                                        }
                                    @endphp
                                    <img class="rounded-lg" src="{{ $imageSrc }}" alt="Admin Photo">
                                    <div class="flex cursor-pointer items-center rounded-full border px-2 py-px text-xs border-(--color-pending) bg-(--color-pending)/70 absolute top-0 z-10 m-5 text-white [--color:var(--color-pending)]">
                                        {{ $roleName }}
                                    </div>
                                    <div class="absolute bottom-0 z-10 px-5 pb-6 text-white">
                                        <a class="block text-base font-medium" href="">
                                            {{ $admin->name }}
                                        </a>
                                        <span class="mt-3 text-xs opacity-70">
                                            {{ ucfirst($admin->role ?? 'Admin') }}
                                        </span>
                                    </div>
                                </div>
                                <div class="mt-5 opacity-70">
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="user" class="lucide lucide-user size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 mr-2 h-4 w-4"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                        Admin ID: {{ $admin->id }}
                                    </div>
                                    <div class="mt-2 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="mail" class="lucide lucide-mail size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 mr-2 h-4 w-4"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><path d="M22 6l-10 7L2 6"></path></svg>
                                        {{ $admin->email }}
                                    </div>
                                    <div class="mt-2 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="shield" class="lucide lucide-shield size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 mr-2 h-4 w-4"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                                        <span class="text-sm">Role: </span>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800 ml-1">
                                            {{ ucfirst($admin->role ?? 'Admin') }}
                                        </span>
                                    </div>
                                    <div class="mt-2 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="wifi" class="lucide lucide-wifi size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 mr-2 h-4 w-4"><path d="M5 12.55a11 11 0 0 1 14.08 0"></path><path d="M1.42 9a16 16 0 0 1 21.16 0"></path><path d="M8.53 16.11a6 6 0 0 1 6.95 0"></path><line x1="12" y1="20" x2="12.01" y2="20"></line></svg>
                                        <span class="text-sm">Online: </span>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $admin->is_online ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }} ml-1">
                                            {{ $admin->is_online ? 'Yes' : 'No' }}
                                        </span>
                                    </div>
                                    <div class="mt-2 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="check-square" class="lucide lucide-check-square size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 mr-2 h-4 w-4"><path d="M21 10.5V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h12.5"></path><path d="m9 11 3 3L22 4"></path></svg>
                                        <span class="text-sm">Status: </span>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $admin->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} ml-1">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                            {{ ucfirst($admin->status ?? 'Unknown') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endforeach
        @else
            <div class="col-span-12 text-center py-12">
                <div class="mx-auto h-12 w-12 text-gray-400">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                    </svg>
                </div>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No Admins Found</h3>
                <p class="mt-1 text-sm text-gray-500">There are currently no admins in the system.</p>
            </div>
        @endif
        <!-- END: Admins by Role -->
    </div>
 
</div>