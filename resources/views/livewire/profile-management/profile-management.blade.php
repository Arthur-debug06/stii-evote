<div class="mt-5 grid grid-cols-12 gap-6">
                        <!-- BEGIN: Profile Menu -->
                        <div class="col-span-12 flex flex-col-reverse lg:col-span-4 lg:block 2xl:col-span-3">
                            <div class="box relative before:absolute before:inset-0 before:mx-3 before:-mb-3 before:border before:border-foreground/10 before:bg-background/30 before:shadow-[0px_3px_5px_#0000000b] before:z-[-1] before:rounded-xl after:absolute after:inset-0 after:border after:border-foreground/10 after:bg-background after:shadow-[0px_3px_5px_#0000000b] after:rounded-xl after:z-[-1] after:backdrop-blur-md mt-5 p-0 lg:mt-0">
                                <div class="relative flex items-center p-5">
                                    <span data-content="" class="tooltip border-(--color)/5 block relative flex-none overflow-hidden rounded-full border-3 ring-1 ring-(--color)/25 [--color:var(--color-primary)] size-12">
                                        @php
                                            $profileImage = null;
                                            
                                            // Prefer explicit DB paths using route-based serving
                                            if ($user && $user->profile_image && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->profile_image)) {
                                                $profileImage = route('public.file', ['path' => $user->profile_image]);
                                            } elseif ($user && $user->student_id_image && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->student_id_image)) {
                                                $profileImage = route('public.file', ['path' => $user->student_id_image]);
                                            } elseif ($userType === 'student' && $user) {
                                                // Fallback: Check student_images directory for profile images
                                                $studentImagesPath = storage_path('app/public/student_images');
                                                if (is_dir($studentImagesPath)) {
                                                    $files = glob($studentImagesPath . '/*_profile_*' . $user->id . '*');
                                                    if (empty($files)) {
                                                        $files = glob($studentImagesPath . '/*_id_*' . $user->id . '*');
                                                    }
                                                    if (!empty($files) && file_exists($files[0])) {
                                                        $profileImage = route('public.file', ['path' => 'student_images/' . basename($files[0])]);
                                                    }
                                                }
                                            }
                                        @endphp
                                        
                                        @if($profileImage)
                                            <img class="absolute top-0 size-full object-cover" src="{{ $profileImage }}" alt="{{ $user->first_name ?? 'User' }} {{ $user->last_name ?? '' }}">
                                            @else
                                            <div class="absolute top-0 size-full bg-primary/20 flex items-center justify-center text-primary font-semibold">
                                                    @if($userType === 'student')
                                                    {{ strtoupper(substr($user->first_name ?? 'U', 0, 1)) }}{{ strtoupper(substr($user->last_name ?? 'N', 0, 1)) }}
                                                    @else
                                                    {{ strtoupper(substr($user->name ?? 'A', 0, 1)) }}
                                                    @endif
                                                </div>
                                            @endif
                                        </span>
                                    <div class="ml-4 mr-auto">
                                        <div class="text-base font-medium">
                                            @if($userType === 'student')
                                                {{ $user->first_name ?? 'Unknown' }} {{ $user->last_name ?? 'Student' }}
                                            @else
                                                {{ $user->name ?? 'Admin User' }}
                                            @endif
                                        </div>
                                        <div class="opacity-70">
                                            @if($userType === 'student')
                                                {{ $user->course->course_name ?? 'Student' }}
                                            @else
                                                Administrator
                                            @endif
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="flex flex-col gap-5 border-t p-5">
                                    <!-- User Information Display -->
                                    <div class="space-y-4">
                                        <h3 class="text-lg font-semibold text-foreground">User Information</h3>
                                        
                                        @if($userType === 'student')
                                            <!-- Student Information -->
                                            <div class="grid grid-cols-1 gap-4">
                                                <div class="flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user mr-3 text-primary">
                                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                                        <circle cx="12" cy="7" r="4"></circle>
                                                    </svg>
                                                    <div>
                                                        <div class="text-sm font-medium text-foreground">Full Name</div>
                                                        <div class="text-sm text-muted-foreground">{{ $user->first_name ?? 'N/A' }} {{ $user->last_name ?? 'N/A' }}</div>
                                    </div>
                                        </div>
                                                
                                                <div class="flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-mail mr-3 text-primary">
                                                        <rect width="20" height="16" x="2" y="4" rx="2"></rect>
                                                        <path d="m22 7-10 5L2 7"></path>
                                                    </svg>
                                                    <div>
                                                        <div class="text-sm font-medium text-foreground">Email</div>
                                                        <div class="text-sm text-muted-foreground">{{ $user->email ?? 'N/A' }}</div>
                                        </div>
                                        </div>
                                                
                                                <div class="flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-book-open mr-3 text-primary">
                                                        <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                                                        <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                                                    </svg>
                                                    <div>
                                                        <div class="text-sm font-medium text-foreground">Course</div>
                                                        <div class="text-sm text-muted-foreground">{{ $user->course->course_name ?? 'N/A' }}</div>
                                        </div>
                                        </div>
                                                
                                                <div class="flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-building mr-3 text-primary">
                                                        <rect width="16" height="20" x="4" y="2" rx="2" ry="2"></rect>
                                                        <path d="M9 22v-4h6v4"></path>
                                                        <path d="M8 6h.01"></path>
                                                        <path d="M16 6h.01"></path>
                                                        <path d="M12 6h.01"></path>
                                                        <path d="M12 10h.01"></path>
                                                        <path d="M12 14h.01"></path>
                                                        <path d="M16 10h.01"></path>
                                                        <path d="M16 14h.01"></path>
                                                        <path d="M8 10h.01"></path>
                                                        <path d="M8 14h.01"></path>
                                                    </svg>
                                                    <div>
                                                        <div class="text-sm font-medium text-foreground">Department</div>
                                                        <div class="text-sm text-muted-foreground">{{ $user->department->department_name ?? 'N/A' }}</div>
                                        </div>
                                        </div>
                                                
                                                <div class="flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar mr-3 text-primary">
                                                        <rect width="18" height="18" x="3" y="4" rx="2" ry="2"></rect>
                                                        <line x1="16" x2="16" y1="2" y2="6"></line>
                                                        <line x1="8" x2="8" y1="2" y2="6"></line>
                                                        <line x1="3" x2="21" y1="10" y2="10"></line>
                                                    </svg>
                                                    <div>
                                                        <div class="text-sm font-medium text-foreground">School Year</div>
                                                        <div class="text-sm text-muted-foreground">{{ $user->school_year_and_semester->school_year ?? 'N/A' }} - {{ $user->school_year_and_semester->semester ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                                
                                                <div class="flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-id-card mr-3 text-primary">
                                                        <rect width="18" height="12" x="3" y="10" rx="2" ry="2"></rect>
                                                        <circle cx="12" cy="6" r="2"></circle>
                                                        <path d="M9 10h6"></path>
                                                    </svg>
                                                    <div>
                                                        <div class="text-sm font-medium text-foreground">Student ID</div>
                                                        <div class="text-sm text-muted-foreground">{{ $user->student_id ?? 'N/A' }}</div>
                                </div>
                                        </div>
                                                
                                                <div class="flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-heart mr-3 text-primary">
                                                        <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.29 1.51 4.04 3 5.5l7 7Z"></path>
                                                    </svg>
                                                    <div>
                                                        <div class="text-sm font-medium text-foreground">Marital Status</div>
                                                        <div class="text-sm text-muted-foreground">{{ ucfirst($user->marital_status ?? 'N/A') }}</div>
                                        </div>
                                        </div>
                                                
                                                <div class="flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shield mr-3 text-primary">
                                                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                                                    </svg>
                                                    <div>
                                                        <div class="text-sm font-medium text-foreground">Status</div>
                                                        <div class="text-sm text-muted-foreground">{{ ucfirst($user->status ?? 'N/A') }}</div>
                                    </div>
                                </div>
                            </div>
                                        @else
                                            <!-- Admin Information -->
                                            <div class="grid grid-cols-1 gap-4">
                                                <div class="flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user mr-3 text-primary">
                                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                                        <circle cx="12" cy="7" r="4"></circle>
                                                    </svg>
                                                    <div>
                                                        <div class="text-sm font-medium text-foreground">Full Name</div>
                                                        <div class="text-sm text-muted-foreground">{{ $user->name ?? 'N/A' }}</div>
                            </div>
                        </div>
                                                
                                                <div class="flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-mail mr-3 text-primary">
                                                        <rect width="20" height="16" x="2" y="4" rx="2"></rect>
                                                        <path d="m22 7-10 5L2 7"></path>
                                                    </svg>
                                                    <div>
                                                        <div class="text-sm font-medium text-foreground">Email</div>
                                                        <div class="text-sm text-muted-foreground">{{ $user->email ?? 'N/A' }}</div>
                                                        </div>
                                                    </div>
                                                
                                                <div class="flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shield mr-3 text-primary">
                                                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                                                    </svg>
                                                    <div>
                                                        <div class="text-sm font-medium text-foreground">Role</div>
                                                        <div class="text-sm text-muted-foreground">{{ ucfirst($user->role ?? 'N/A') }}</div>
                                                </div>
                                            </div>
                                                 
                                                <div class="flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-activity mr-3 text-primary">
                                                        <path d="M22 12h-4l-3 9L9 3l-3 9H2"></path>
                                                    </svg>
                                                    <div>
                                                        <div class="text-sm font-medium text-foreground">Status</div>
                                                        <div class="text-sm text-muted-foreground">{{ ucfirst($user->status ?? 'N/A') }}</div>
                                                        </div>
                                                    </div>
                                                
                                                <div class="flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar mr-3 text-primary">
                                                        <rect width="18" height="18" x="3" y="4" rx="2" ry="2"></rect>
                                                        <line x1="16" x2="16" y1="2" y2="6"></line>
                                                        <line x1="8" x2="8" y1="2" y2="6"></line>
                                                        <line x1="3" x2="21" y1="10" y2="10"></line>
                                                    </svg>
                                                        <div>
                                                        <div class="text-sm font-medium text-foreground">Member Since</div>
                                                        <div class="text-sm text-muted-foreground">{{ $user->created_at ? $user->created_at->format('M d, Y') : 'N/A' }}</div>
                                                                </div>
                                                                </div>
                                                            </div>
                                        @endif
                                                                </div>
                                                                </div>
                               
                                                            </div>
                            <!-- OTP Verification Section -->
                            @if($showOtpModal)
                            <div class="box relative p-5 before:absolute before:inset-0 before:mx-3 before:-mb-3 before:border before:shadow-[0px_3px_5px_#0000000b] before:z-[-1] before:rounded-xl after:absolute after:inset-0 after:border after:shadow-[0px_3px_5px_#0000000b] after:rounded-xl after:z-[-1] after:backdrop-blur-md text-(--color) before:bg-(--color)/5 before:border-(--color)/20 after:bg-(--color)/5 after:border-(--color)/20 mt-8 [--color:var(--color-primary)]">
                                <div class="flex items-center">
                                    <div class="text-lg font-medium">Security Verification</div>
                                    <div class="bg-(--color)/20 border-(--color)/60 text-(--color) flex cursor-pointer items-center rounded-full border px-2 py-px text-xs ml-auto [--color:var(--color-primary)]">
                                        Required
                                                                </div>
                                                                </div>
                                <div class="mt-4 text-center">
                                    <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                        <div class="flex items-center justify-center mb-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-600 mr-2">
                                                <path d="M9 12l2 2 4-4"></path>
                                                <path d="M21 12c-1 0-3-1-3-3s2-3 3-3 3 1 3 3-2 3-3 3"></path>
                                                <path d="M3 12c1 0 3-1 3-3s-2-3-3-3-3 1-3 3 2 3 3 3"></path>
                                            </svg>
                                            <span class="text-sm font-medium text-blue-800">OTP Sent Successfully!</span>
                                                            </div>
                                        <p class="text-xs text-blue-700">
                                            Check your email inbox and spam folder for the verification code.
                                        </p>
                                                            </div>
                                    
                                    <p class="text-sm text-gray-600 mb-4">
                                        Please enter the 6-digit OTP sent to your email address to complete the {{ $changeType === 'password' ? 'password change' : 'email change' }}.
                                    </p>
                                    <p class="text-sm font-medium text-gray-800 mb-6">
                                        Code sent to: <strong>{{ $originalEmail }}</strong>
                                    </p>
                                    
                                    <div class="mb-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                        <h4 class="text-sm font-medium text-yellow-800 mb-2">Instructions:</h4>
                                        <ul class="text-xs text-yellow-700 text-left space-y-1">
                                            <li>• Check your email inbox for the 6-digit code</li>
                                            <li>• If not in inbox, check your spam/junk folder</li>
                                            <li>• Enter the code exactly as shown in the email</li>
                                            <li>• Code expires in 10 minutes</li>
                                            <li>• Do not share this code with anyone</li>
                                                    </ul>
                                                </div>
                                    
                                    <div class="flex justify-center mb-4">
                                        <input 
                                            wire:model="otpCode" 
                                            type="text" 
                                            maxlength="6"
                                            class="w-40 text-center text-2xl font-mono tracking-widest border-2 border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary focus:border-primary @error('otpCode') border-red-500 @enderror"
                                            placeholder="000000"
                                            autocomplete="off"
                                            x-data="{ otp: @entangle('otpCode') }"
                                            x-model="otp"
                                            x-on:input="otp = otp.replace(/[^0-9]/g, '').slice(0, 6)"
                                            x-on:keydown.enter="$wire.verifyOtp()">
                                                                </div>
                                    
                                    @error('otpCode') 
                                        <div class="text-center text-red-500 text-sm mb-4">{{ $message }}</div> 
                                    @enderror
                                    
                                    <div class="text-center text-sm text-gray-500 mb-4">
                                        <div class="mb-2">
                                            <span class="text-gray-600">Code expires in: </span>
                                            <span class="font-mono font-bold text-red-600" x-data="{ timeLeft: 600 }" x-init="setInterval(() => { if (timeLeft > 0) timeLeft-- }, 1000)">
                                                <span x-text="Math.floor(timeLeft / 60) + ':' + (timeLeft % 60).toString().padStart(2, '0')"></span>
                                                                    </span>
                                                                </div>
                                        <p>Didn't receive the code? 
                                            <button type="button" wire:click="sendOtp" class="text-primary hover:underline font-medium">
                                                Resend OTP
                                                                </button>
                                        </p>
                                                            </div>
                                                        </div>
                                <div class="mt-5 flex font-medium">
                                    <button type="button" wire:click="cancelOtpVerification" class="cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 border-gray-300 text-gray-700 hover:bg-gray-50 h-10 px-4 py-2 w-28 bg-transparent" type="button">
                                        Cancel
                                                                </button>
                                    <button type="button" wire:click="verifyOtp" class="cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 border-(--color)/60 text-(--color) hover:bg-(--color)/5 [--color:var(--color-primary)] h-10 px-4 py-2 ml-auto w-28 bg-transparent" type="button">
                                        Verify
                                                                </button>
                                                            </div>
                                                        </div>
                            @else
                            <div class="box relative p-5 before:absolute before:inset-0 before:mx-3 before:-mb-3 before:border before:shadow-[0px_3px_5px_#0000000b] before:z-[-1] before:rounded-xl after:absolute after:inset-0 after:border after:shadow-[0px_3px_5px_#0000000b] after:rounded-xl after:z-[-1] after:backdrop-blur-md text-(--color) before:bg-(--color)/5 before:border-(--color)/20 after:bg-(--color)/5 after:border-(--color)/20 mt-8 [--color:var(--color-warning)]">
                                                                <div class="flex items-center">
                                    <div class="text-lg font-medium">Take Note</div>
                                   
                                                                </div>
                                <div class="mt-4">
                                    This section will change into verififcation of otp if the user request for a change password or email.
                                                                </div>
                                
                                                            </div>
                            @endif
                                                        </div>
                        <!-- END: Profile Menu -->
                        <div class="col-span-12 lg:col-span-8 2xl:col-span-9">
                            <div class="grid grid-cols-6 gap-x-6 gap-y-8">
                                <!-- BEGIN: Profile Information -->
                                <div class="box relative before:absolute before:inset-0 before:mx-3 before:-mb-3 before:border before:border-foreground/10 before:bg-background/30 before:shadow-[0px_3px_5px_#0000000b] before:z-[-1] before:rounded-xl after:absolute after:inset-0 after:border after:border-foreground/10 after:bg-background after:shadow-[0px_3px_5px_#0000000b] after:rounded-xl after:z-[-1] after:backdrop-blur-md col-span-12 p-0 2xl:col-span-6">
                                    <div class="flex items-center border-b px-5 py-5 sm:py-3">
                                        <h2 class="mr-auto text-base font-medium">Profile Information</h2>
                                        <button wire:click="updateProfile" class="[--color:var(--color-primary)] cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 text-white hover:bg-(--color)/90 bg-(--color) border-(--color) h-10 px-4 py-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="save" class="lucide lucide-save stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 mr-2 size-4">
                                                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                                                <polyline points="17,21 17,13 7,13 7,21"></polyline>
                                                <polyline points="7,3 7,8 15,8"></polyline>
                                            </svg>
                                            Save Changes
                                                                </button>
                                                            </div>
                                    <div class="p-5">
                                        @if (session()->has('success'))
                                            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                                                {{ session('success') }}
                                                        </div>
                                        @endif
                                        
                                        @if (session()->has('error'))
                                            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                                                {{ session('error') }}
                                                    </div>
                                        @endif
                                        
                                        <form wire:submit.prevent="updateProfile" class="space-y-6">
                                            @if($userType === 'admin')
                                                <!-- Admin/User Model Form -->
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                    <!-- Name -->
                                                    <div>
                                                        <label class="block text-sm font-medium text-foreground mb-2">Name</label>
                                                        <input type="text" wire:model="name" 
                                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('name') border-red-500 @enderror"
                                                               placeholder="Enter your name">
                                                        @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                                                </div>
                                                
                                                <!-- Email -->
                                                <div>
                                                    <label class="block text-sm font-medium text-foreground mb-2">Email</label>
                                                    <input type="email" wire:model="email" 
                                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('email') border-red-500 @enderror"
                                                           placeholder="Enter your email">
                                                    @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                                                    </div>
                                                
                                                
                                                <!-- Profile Image Upload -->
                                                <div>
                                                    <label class="block text-sm font-medium text-foreground mb-2">Profile Image</label>
                                                    <div class="flex items-center space-x-4">
                                                        @if($user && $user->profile_image && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->profile_image))
                                                            <img src="{{ route('public.file', ['path' => $user->profile_image]) }}" 
                                                                 alt="Profile" class="w-16 h-16 rounded-full object-cover">
                                                        @else
                                                            <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center">
                                                                <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                                                </svg>
                                                                </div>
                                                        @endif
                                                        <div>
                                                            <input type="file" accept="image/*" class="text-sm text-gray-500">
                                                            <p class="text-xs text-gray-500 mt-1">JPG, PNG or GIF (Max. 2MB)</p>
                                                            </div>
                                                                </div>
                                                                </div>
                                            @else
                                                <!-- Student Model Form -->
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                    
                                                    <!-- First Name -->
                                                    <div>
                                                        <label class="block text-sm font-medium text-foreground mb-2">First Name</label>
                                                        <input type="text" wire:model="first_name" 
                                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('first_name') border-red-500 @enderror"
                                                               placeholder="Enter first name">
                                                        @error('first_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                                            </div>
                                                    
                                                    <!-- Middle Name -->
                                                    <div>
                                                        <label class="block text-sm font-medium text-foreground mb-2">Middle Name</label>
                                                        <input type="text" wire:model="middle_name" 
                                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('middle_name') border-red-500 @enderror"
                                                               placeholder="Enter middle name">
                                                        @error('middle_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                                        </div>
                                                    
                                                    <!-- Last Name -->
                                                    <div>
                                                        <label class="block text-sm font-medium text-foreground mb-2">Last Name</label>
                                                        <input type="text" wire:model="last_name" 
                                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('last_name') border-red-500 @enderror"
                                                               placeholder="Enter last name">
                                                        @error('last_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                                                </div>
                                                    
                                                    <!-- Suffix -->
                                                    <div>
                                                        <label class="block text-sm font-medium text-foreground mb-2">Suffix</label>
                                                        <input type="text" wire:model="suffix" 
                                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('suffix') border-red-500 @enderror"
                                                               placeholder="Jr., Sr., III, etc.">
                                                        @error('suffix') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                                            </div>
                                                    
                                                    <!-- Gender -->
                                                    <div>
                                                        <label class="block text-sm font-medium text-foreground mb-2">Gender</label>
                                                        <select wire:model="gender" 
                                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('gender') border-red-500 @enderror">
                                                            <option value="">Select Gender</option>
                                                            <option value="Male">Male</option>
                                                            <option value="Female">Female</option>
                                                            <option value="Other">Other</option>
                                                        </select>
                                                        @error('gender') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                                            </div>
                                                    
                                                    <!-- Marital Status -->
                                                    <div>
                                                        <label class="block text-sm font-medium text-foreground mb-2">Marital Status</label>
                                                        <select wire:model="marital_status" 
                                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('marital_status') border-red-500 @enderror">
                                                            <option value="">Select Marital Status</option>
                                                            <option value="Single">Single</option>
                                                            <option value="Married">Married</option>
                                                            <option value="Widowed">Widowed</option>
                                                            <option value="Divorced">Divorced</option>
                                                        </select>
                                                        @error('marital_status') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                                        </div>
                                                    
                                                    <!-- Date of Birth -->
                                                    <div>
                                                        <label class="block text-sm font-medium text-foreground mb-2">Date of Birth</label>
                                                        <input type="date" wire:model="date_of_birth" 
                                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('date_of_birth') border-red-500 @enderror">
                                                        @error('date_of_birth') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                                    </div>
                                                    
                                                    <!-- Age -->
                                                    <div>
                                                        <label class="block text-sm font-medium text-foreground mb-2">Age</label>
                                                        <input type="number" wire:model="age" 
                                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('age') border-red-500 @enderror"
                                                               placeholder="Enter age" min="1" max="150">
                                                        @error('age') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                                                </div>
                                                    
                                                    <!-- Email -->
                                                    <div>
                                                        <label class="block text-sm font-medium text-foreground mb-2">Email</label>
                                                        <input type="email" wire:model="email" 
                                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('email') border-red-500 @enderror"
                                                               placeholder="Enter email">
                                                        @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                                                    </div>
                                                    
                                                                </div>
                                                
                                                <!-- Address -->
                                                <div>
                                                    <label class="block text-sm font-medium text-foreground mb-2">Address</label>
                                                    <textarea wire:model="address" rows="3"
                                                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('address') border-red-500 @enderror"
                                                              placeholder="Enter your address"></textarea>
                                                    @error('address') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                                            </div>
                                                
                                                <!-- Profile Image Upload -->
                                                <div>
                                                    <label class="block text-sm font-medium text-foreground mb-2">Profile Image</label>
                                                    <div class="flex items-center space-x-4">
                                                        @if($user && $user->profile_image && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->profile_image))
                                                            <img src="{{ route('public.file', ['path' => $user->profile_image]) }}" 
                                                                 alt="Profile" class="w-16 h-16 rounded-full object-cover">
                                                        @else
                                                            <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center">
                                                                <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                                                </svg>
                                                                </div>
                                                        @endif
                                                        <div>
                                                            <input type="file" accept="image/*" class="text-sm text-gray-500">
                                                            <p class="text-xs text-gray-500 mt-1">JPG, PNG or GIF (Max. 2MB)</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                            @endif
                                        </form>
                                                                </div>
                                                            </div>
                                <!-- END: Profile Information -->
                                
                                <!-- BEGIN: Change Password -->
                                <div class="box relative before:absolute before:inset-0 before:mx-3 before:-mb-3 before:border before:border-foreground/10 before:bg-background/30 before:shadow-[0px_3px_5px_#0000000b] before:z-[-1] before:rounded-xl after:absolute after:inset-0 after:border after:border-foreground/10 after:bg-background after:shadow-[0px_3px_5px_#0000000b] after:rounded-xl after:z-[-1] after:backdrop-blur-md col-span-12 p-0 2xl:col-span-6">
                                    <div class="flex items-center border-b px-5 py-5 sm:py-3">
                                        <h2 class="mr-auto text-base font-medium">Change Password</h2>
                                        <button wire:click="changePassword" class="[--color:var(--color-primary)] cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 text-white hover:bg-(--color)/90 bg-(--color) border-(--color) h-10 px-4 py-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="lock" class="lucide lucide-lock stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 mr-2 size-4">
                                                <rect width="18" height="11" x="3" y="11" rx="2" ry="2"></rect>
                                                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                            </svg>
                                            Change Password
                                                                </button>
                                                            </div>
                                    <div class="p-5">
                                        @if (session()->has('success'))
                                            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                                                {{ session('success') }}
                                                        </div>
                                        @endif
                                        
                                        @if (session()->has('error'))
                                            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                                                {{ session('error') }}
                                                    </div>
                                        @endif
                                        
                                        <form wire:submit.prevent="changePassword" class="space-y-6">
                                            <div class="grid grid-cols-1 md:grid-cols-1 gap-6">
                                                <!-- Current Password -->
                                                <div>
                                                    <label class="block text-sm font-medium text-foreground mb-2">Current Password</label>
                                                    <div class="relative">
                                                        <input type="{{ $show_current_password ? 'text' : 'password' }}" wire:model="current_password" 
                                                               class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('current_password') border-red-500 @enderror"
                                                               placeholder="Enter your current password">
                                                        <button type="button" wire:click="toggleCurrentPasswordVisibility" 
                                                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                                            @if($show_current_password)
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye-off">
                                                                    <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"></path>
                                                                    <path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"></path>
                                                                    <path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"></path>
                                                                    <line x1="2" x2="22" y1="2" y2="22"></line>
                                                                </svg>
                                                            @else
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye">
                                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                                    <circle cx="12" cy="12" r="3"></circle>
                                                                </svg>
                                                            @endif
                                                                </button>
                                                            </div>
                                                    @error('current_password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                                        </div>
                                                
                                                <!-- New Password -->
                                                <div>
                                                    <label class="block text-sm font-medium text-foreground mb-2">New Password</label>
                                                    <div class="relative">
                                                        <input type="{{ $show_new_password ? 'text' : 'password' }}" wire:model="new_password" 
                                                               class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('new_password') border-red-500 @enderror"
                                                               placeholder="Enter your new password">
                                                        <button type="button" wire:click="toggleNewPasswordVisibility" 
                                                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                                            @if($show_new_password)
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye-off">
                                                                    <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"></path>
                                                                    <path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"></path>
                                                                    <path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"></path>
                                                                    <line x1="2" x2="22" y1="2" y2="22"></line>
                                                                </svg>
                                                            @else
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye">
                                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                                    <circle cx="12" cy="12" r="3"></circle>
                                                                </svg>
                                                            @endif
                                                                </button>
                                                            </div>
                                                    @error('new_password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                                        </div>
                                                
                                                <!-- Confirm Password -->
                                                <div>
                                                    <label class="block text-sm font-medium text-foreground mb-2">Confirm New Password</label>
                                                    <div class="relative">
                                                        <input type="{{ $show_confirm_password ? 'text' : 'password' }}" wire:model="confirm_password" 
                                                               class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('confirm_password') border-red-500 @enderror"
                                                               placeholder="Confirm your new password">
                                                        <button type="button" wire:click="toggleConfirmPasswordVisibility" 
                                                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                                            @if($show_confirm_password)
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye-off">
                                                                    <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"></path>
                                                                    <path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"></path>
                                                                    <path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"></path>
                                                                    <line x1="2" x2="22" y1="2" y2="22"></line>
                                                                </svg>
                                                            @else
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye">
                                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                                    <circle cx="12" cy="12" r="3"></circle>
                                                                </svg>
                                                            @endif
                                                                </button>
                                                            </div>
                                                    @error('confirm_password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                                        </div>
                                                    </div>
                                            
                                            <!-- Password Requirements -->
                                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                                <h4 class="text-sm font-medium text-blue-900 mb-2">Password Requirements:</h4>
                                                <ul class="text-sm text-blue-800 space-y-1">
                                                    <li>• At least 8 characters long</li>
                                                    <li>• Must match the confirmation password</li>
                                                    <li>• Current password must be correct</li>
                                                </ul>
                                                                </div>
                                        </form>
                                                                    </div>
                                                                </div>
                                <!-- END: Change Password -->
                               
                                                            </div>
                                                                </div>
                                                                </div>
                    
                    
                    
                    <script>
                        document.addEventListener('livewire:init', () => {
                            Livewire.on('otp-modal-opened', () => {
                                setTimeout(() => {
                                    const otpInput = document.getElementById('otp-input');
                                    if (otpInput) {
                                        otpInput.focus();
                                    }
                                }, 100);
                            });
                        });
                    </script>