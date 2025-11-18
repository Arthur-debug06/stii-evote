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
    
    <h2 class="mt-10 text-lg font-medium">Student Account Management</h2>
    <div class="mt-5 grid grid-cols-12 gap-6">
        <div class="col-span-12 mt-2 flex flex-wrap items-center sm:flex-nowrap">
            <!-- <button wire:click="openAddModal" class="cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 bg-(--color)/20 border-(--color)/60 text-(--color) hover:bg-(--color)/5 [--color:var(--color-primary)] h-10 px-4 py-2 box mr-2">
                Add New Student
            </button> -->
            
            <div class="mx-auto hidden opacity-70 md:block">
                @if($students->total() > 0)
                    Showing {{ $students->firstItem() }} to {{ $students->lastItem() }} of {{ $students->total() }} entries
                    @if(!empty($search))
                        (filtered from {{ \App\Models\students::count() }} total entries)
                    @endif
                @else
                    No entries found
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
                        placeholder="Search student name, email, student ID...">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="search" class="lucide lucide-search size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 absolute inset-y-0 right-0 my-auto mr-3 h-4 w-4"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.3-4.3"></path></svg>
                </div>
            </div>
        </div>
        
        <!-- Add New Student Modal -->
        <x-menu.modal 
            :showButton="false" 
            modalId="add-student-modal" 
            title="Add New Student Account" 
            description="Fill in the details to add new student account"
            size="lg"
            :isOpen="$showAddModal">
            
            <form wire:submit.prevent="createStudent" class="space-y-4">
                <div class="grid grid-cols-2 gap-2">
                    <div class="mb-2">
                        <label class="block text-sm mb-1" for="add-student-id">Student ID</label>
                        <input 
                            wire:model.defer="student_id" 
                            id="add-student-id"
                            type="text"
                            class="h-10 w-full rounded-md border bg-background px-3 py-2 ring-offset-background file:border-0 file:bg-transparent file:font-medium file:text-foreground placeholder:text-foreground/70 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                            placeholder="Enter student ID">
                        @error('student_id') <div class="error-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-2">
                        <label class="block text-sm mb-1" for="add-first-name">First Name</label>
                        <input 
                            wire:model.defer="first_name" 
                            id="add-first-name"
                            type="text"
                            class="h-10 w-full rounded-md border bg-background px-3 py-2 ring-offset-background file:border-0 file:bg-transparent file:font-medium file:text-foreground placeholder:text-foreground/70 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                            placeholder="Enter first name">
                        @error('first_name') <div class="error-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-2">
                        <label class="block text-sm mb-1" for="add-middle-name">Middle Name</label>
                        <input 
                            wire:model.defer="middle_name" 
                            id="add-middle-name"
                            type="text"
                            class="h-10 w-full rounded-md border bg-background px-3 py-2 ring-offset-background file:border-0 file:bg-transparent file:font-medium file:text-foreground placeholder:text-foreground/70 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                            placeholder="Enter middle name">
                        @error('middle_name') <div class="error-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-2">
                        <label class="block text-sm mb-1" for="add-last-name">Last Name</label>
                        <input 
                            wire:model.defer="last_name" 
                            id="add-last-name"
                            type="text"
                            class="h-10 w-full rounded-md border bg-background px-3 py-2 ring-offset-background file:border-0 file:bg-transparent file:font-medium file:text-foreground placeholder:text-foreground/70 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                            placeholder="Enter last name">
                        @error('last_name') <div class="error-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-2">
                        <label class="block text-sm mb-1" for="add-suffix">Suffix</label>
                        <input 
                            wire:model.defer="suffix" 
                            id="add-suffix"
                            type="text"
                            class="h-10 w-full rounded-md border bg-background px-3 py-2 ring-offset-background file:border-0 file:bg-transparent file:font-medium file:text-foreground placeholder:text-foreground/70 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                            placeholder="Jr., Sr., III, etc.">
                        @error('suffix') <div class="error-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-2">
                        <label class="block text-sm mb-1" for="add-gender">Gender</label>
                        <select 
                            wire:model.defer="gender" 
                            id="add-gender"
                            class="h-10 w-full rounded-md border bg-background px-3 py-2 ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                            <option value="">Select Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                        @error('gender') <div class="error-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-2">
                        <label class="block text-sm mb-1" for="add-date-of-birth">Date of Birth</label>
                        <input 
                            wire:model.live="date_of_birth" 
                            id="add-date-of-birth"
                            type="date"
                            class="h-10 w-full rounded-md border bg-background px-3 py-2 ring-offset-background file:border-0 file:bg-transparent file:font-medium file:text-foreground placeholder:text-foreground/70 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                        <p class="text-xs text-gray-500 mt-1">Must be 18 years or older</p>
                        @error('date_of_birth') <div class="error-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-2">
                        <label class="block text-sm mb-1" for="add-age">Age</label>
                        <input 
                            wire:model.live="age" 
                            id="add-age"
                            type="number"
                            min="1"
                            max="150"
                            class="h-10 w-full rounded-md border bg-background px-3 py-2 ring-offset-background file:border-0 file:bg-transparent file:font-medium file:text-foreground placeholder:text-foreground/70 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                            placeholder="Auto-calculated from date of birth"
                            readonly>
                        @error('age') <div class="error-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-2">
                        <label class="block text-sm mb-1" for="add-email">Email</label>
                        <input 
                            wire:model.defer="email" 
                            id="add-email"
                            type="email"
                            class="h-10 w-full rounded-md border bg-background px-3 py-2 ring-offset-background file:border-0 file:bg-transparent file:font-medium file:text-foreground placeholder:text-foreground/70 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                            placeholder="Enter email address">
                        @error('email') <div class="error-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-2">
                        <label class="block text-sm mb-1" for="add-password">Password</label>
                        <input 
                            wire:model.defer="password" 
                            id="add-password"
                            type="password"
                            class="h-10 w-full rounded-md border bg-background px-3 py-2 ring-offset-background file:border-0 file:bg-transparent file:font-medium file:text-foreground placeholder:text-foreground/70 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                            placeholder="Enter password">
                        @error('password') <div class="error-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-2">
                        <label class="block text-sm mb-1" for="add-password-confirmation">Confirm Password</label>
                        <input 
                            wire:model.defer="password_confirmation" 
                            id="add-password-confirmation"
                            type="password"
                            class="h-10 w-full rounded-md border bg-background px-3 py-2 ring-offset-background file:border-0 file:bg-transparent file:font-medium file:text-foreground placeholder:text-foreground/70 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                            placeholder="Confirm password">
                        @error('password_confirmation') <div class="error-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-2">
                        <label class="block text-sm mb-1" for="add-course">Course</label>
                        <select 
                            wire:model.defer="course_id" 
                            id="add-course"
                            class="h-10 w-full rounded-md border bg-background px-3 py-2 ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                            <option value="">Select Course</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}">{{ $course->course_name }}</option>
                            @endforeach
                        </select>
                        @error('course_id') <div class="error-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-2">
                        <label class="block text-sm mb-1" for="add-department">Department</label>
                        <select 
                            wire:model.defer="department_id" 
                            id="add-department"
                            class="h-10 w-full rounded-md border bg-background px-3 py-2 ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                            <option value="">Select Department</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->department_name }}</option>
                            @endforeach
                        </select>
                        @error('department_id') <div class="error-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-2">
                        <label class="block text-sm mb-1" for="add-school-year">School Year & Semester</label>
                        <select 
                            wire:model.defer="school_year_and_semester_id" 
                            id="add-school-year"
                            class="h-10 w-full rounded-md border bg-background px-3 py-2 ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                            <option value="">Select School Year & Semester</option>
                            @foreach($schoolYearSemesters as $schoolYear)
                                <option value="{{ $schoolYear->id }}">{{ $schoolYear->school_year }} - {{ $schoolYear->semester }}</option>
                            @endforeach
                        </select>
                        @error('school_year_and_semester_id') <div class="error-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-2">
                        <label class="block text-sm mb-1" for="add-status">Status</label>
                        <select 
                            wire:model.defer="status" 
                            id="add-status"
                            class="h-10 w-full rounded-md border bg-background px-3 py-2 ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                        @error('status') <div class="error-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-2">
                        <label class="block text-sm mb-1" for="add-address">Address</label>
                        <textarea 
                            wire:model.defer="address" 
                            id="add-address"
                            rows="3"
                            class="h-20 w-full rounded-md border bg-background px-3 py-2 ring-offset-background file:border-0 file:bg-transparent file:font-medium file:text-foreground placeholder:text-foreground/70 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                            placeholder="Enter address"></textarea>
                        @error('address') <div class="error-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-2">
                        <label class="block text-sm mb-1" for="add-profile-image">Profile Image</label>
                        <input 
                            wire:model.defer="profile_image" 
                            id="add-profile-image"
                            type="file"
                            accept="image/*"
                            class="h-10 w-full rounded-md border bg-background px-3 py-2 ring-offset-background file:border-0 file:bg-transparent file:font-medium file:text-foreground placeholder:text-foreground/70 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                        @error('profile_image') <div class="error-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-2">
                        <label class="block text-sm mb-1" for="add-student-id-image">Student ID Image</label>
                        <input 
                            wire:model.defer="student_id_image" 
                            id="add-student-id-image"
                            type="file"
                            accept="image/*"
                            class="h-10 w-full rounded-md border bg-background px-3 py-2 ring-offset-background file:border-0 file:bg-transparent file:font-medium file:text-foreground placeholder:text-foreground/70 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                        @error('student_id_image') <div class="error-message">{{ $message }}</div> @enderror
                    </div>
                </div>
            </form>

            <x-slot:footer>
                <button data-tw-dismiss="modal" type="button" wire:click="$set('showAddModal', false)" class="cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 text-foreground hover:bg-foreground/5 bg-background border-foreground/20 h-10 px-4 py-2 mr-1 w-24">
                    Cancel
                </button>
                <button type="button" wire:click="createStudent" class="cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 bg-(--color)/20 border-(--color)/60 text-(--color) hover:bg-(--color)/5 [--color:var(--color-primary)] h-10 px-4 py-2 w-24">
                    Create
                </button>
            </x-slot:footer>
        </x-menu.modal>

        <!-- Edit Student Modal -->
        <x-menu.modal 
            :showButton="false" 
            modalId="edit-student-modal" 
            title="Edit Student Account" 
            description="Update the student account details"
            size="lg"
            :isOpen="$showEditModal">
            
            <form wire:submit.prevent="updateStudent" class="space-y-4">
                <div class="grid grid-cols-2 gap-2">
                    <div class="mb-2">
                        <label class="block text-sm mb-1" for="edit-student-id">Student ID</label>
                        <input 
                            wire:model.defer="student_id" 
                            id="edit-student-id"
                            type="text"
                            class="h-10 w-full rounded-md border bg-background px-3 py-2 ring-offset-background file:border-0 file:bg-transparent file:font-medium file:text-foreground placeholder:text-foreground/70 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                            placeholder="Enter student ID">
                        @error('student_id') <div class="error-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-2">
                        <label class="block text-sm mb-1" for="edit-first-name">First Name</label>
                        <input 
                            wire:model.defer="first_name" 
                            id="edit-first-name"
                            type="text"
                            class="h-10 w-full rounded-md border bg-background px-3 py-2 ring-offset-background file:border-0 file:bg-transparent file:font-medium file:text-foreground placeholder:text-foreground/70 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                            placeholder="Enter first name">
                        @error('first_name') <div class="error-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-2">
                        <label class="block text-sm mb-1" for="edit-middle-name">Middle Name</label>
                        <input 
                            wire:model.defer="middle_name" 
                            id="edit-middle-name"
                            type="text"
                            class="h-10 w-full rounded-md border bg-background px-3 py-2 ring-offset-background file:border-0 file:bg-transparent file:font-medium file:text-foreground placeholder:text-foreground/70 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                            placeholder="Enter middle name">
                        @error('middle_name') <div class="error-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-2">
                        <label class="block text-sm mb-1" for="edit-last-name">Last Name</label>
                        <input 
                            wire:model.defer="last_name" 
                            id="edit-last-name"
                            type="text"
                            class="h-10 w-full rounded-md border bg-background px-3 py-2 ring-offset-background file:border-0 file:bg-transparent file:font-medium file:text-foreground placeholder:text-foreground/70 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                            placeholder="Enter last name">
                        @error('last_name') <div class="error-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-2">
                        <label class="block text-sm mb-1" for="edit-suffix">Suffix</label>
                        <input 
                            wire:model.defer="suffix" 
                            id="edit-suffix"
                            type="text"
                            class="h-10 w-full rounded-md border bg-background px-3 py-2 ring-offset-background file:border-0 file:bg-transparent file:font-medium file:text-foreground placeholder:text-foreground/70 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                            placeholder="Jr., Sr., III, etc.">
                        @error('suffix') <div class="error-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-2">
                        <label class="block text-sm mb-1" for="edit-gender">Gender</label>
                        <select 
                            wire:model.defer="gender" 
                            id="edit-gender"
                            class="h-10 w-full rounded-md border bg-background px-3 py-2 ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                            <option value="">Select Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                        @error('gender') <div class="error-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-2">
                        <label class="block text-sm mb-1" for="edit-date-of-birth">Date of Birth</label>
                        <input 
                            wire:model.live="date_of_birth" 
                            id="edit-date-of-birth"
                            type="date"
                            class="h-10 w-full rounded-md border bg-background px-3 py-2 ring-offset-background file:border-0 file:bg-transparent file:font-medium file:text-foreground placeholder:text-foreground/70 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                        <p class="text-xs text-gray-500 mt-1">Must be 18 years or older</p>
                        @error('date_of_birth') <div class="error-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-2">
                        <label class="block text-sm mb-1" for="edit-age">Age</label>
                        <input 
                            wire:model.live="age" 
                            id="edit-age"
                            type="number"
                            min="1"
                            max="150"
                            class="h-10 w-full rounded-md border bg-background px-3 py-2 ring-offset-background file:border-0 file:bg-transparent file:font-medium file:text-foreground placeholder:text-foreground/70 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                            placeholder="Auto-calculated from date of birth"
                            readonly>
                        @error('age') <div class="error-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-2">
                        <label class="block text-sm mb-1" for="edit-email">Email</label>
                        <input 
                            wire:model.defer="email" 
                            id="edit-email"
                            type="email"
                            class="h-10 w-full rounded-md border bg-background px-3 py-2 ring-offset-background file:border-0 file:bg-transparent file:font-medium file:text-foreground placeholder:text-foreground/70 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                            placeholder="Enter email address">
                        @error('email') <div class="error-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-2">
                        <label class="block text-sm mb-1" for="edit-password">New Password (Optional)</label>
                        <input 
                            wire:model.defer="password" 
                            id="edit-password"
                            type="password"
                            class="h-10 w-full rounded-md border bg-background px-3 py-2 ring-offset-background file:border-0 file:bg-transparent file:font-medium file:text-foreground placeholder:text-foreground/70 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                            placeholder="Leave blank to keep current password">
                        @error('password') <div class="error-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-2">
                        <label class="block text-sm mb-1" for="edit-password-confirmation">Confirm New Password</label>
                        <input 
                            wire:model.defer="password_confirmation" 
                            id="edit-password-confirmation"
                            type="password"
                            class="h-10 w-full rounded-md border bg-background px-3 py-2 ring-offset-background file:border-0 file:bg-transparent file:font-medium file:text-foreground placeholder:text-foreground/70 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                            placeholder="Confirm new password">
                        @error('password_confirmation') <div class="error-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-2">
                        <label class="block text-sm mb-1" for="edit-course">Course</label>
                        <select 
                            wire:model.defer="course_id" 
                            id="edit-course"
                            class="h-10 w-full rounded-md border bg-background px-3 py-2 ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                            <option value="">Select Course</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}">{{ $course->course_name }}</option>
                            @endforeach
                        </select>
                        @error('course_id') <div class="error-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-2">
                        <label class="block text-sm mb-1" for="edit-department">Department</label>
                        <select 
                            wire:model.defer="department_id" 
                            id="edit-department"
                            class="h-10 w-full rounded-md border bg-background px-3 py-2 ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                            <option value="">Select Department</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->department_name }}</option>
                            @endforeach
                        </select>
                        @error('department_id') <div class="error-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-2">
                        <label class="block text-sm mb-1" for="edit-school-year">School Year & Semester</label>
                        <select 
                            wire:model.defer="school_year_and_semester_id" 
                            id="edit-school-year"
                            class="h-10 w-full rounded-md border bg-background px-3 py-2 ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                            <option value="">Select School Year & Semester</option>
                            @foreach($schoolYearSemesters as $schoolYear)
                                <option value="{{ $schoolYear->id }}">{{ $schoolYear->school_year }} - {{ $schoolYear->semester }}</option>
                            @endforeach
                        </select>
                        @error('school_year_and_semester_id') <div class="error-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-2">
                        <label class="block text-sm mb-1" for="edit-status">Status</label>
                        <select 
                            wire:model.defer="status" 
                            id="edit-status"
                            class="h-10 w-full rounded-md border bg-background px-3 py-2 ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                        @error('status') <div class="error-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-2">
                        <label class="block text-sm mb-1" for="edit-address">Address</label>
                        <textarea 
                            wire:model.defer="address" 
                            id="edit-address"
                            rows="3"
                            class="h-20 w-full rounded-md border bg-background px-3 py-2 ring-offset-background file:border-0 file:bg-transparent file:font-medium file:text-foreground placeholder:text-foreground/70 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                            placeholder="Enter address"></textarea>
                        @error('address') <div class="error-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-2">
                        <label class="block text-sm mb-1" for="edit-profile-image">Profile Image</label>
                            @if($temp_profile_image)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $temp_profile_image) }}" alt="Current Profile" class="w-16 h-16 rounded-full object-cover">
                                    <p class="text-xs text-gray-500 mt-1">Current profile image</p>
                                </div>
                            @endif
                            <input 
                                wire:model.defer="profile_image" 
                                id="edit-profile-image"
                                type="file"
                                accept="image/*"
                            class="h-10 w-full rounded-md border bg-background px-3 py-2 ring-offset-background file:border-0 file:bg-transparent file:font-medium file:text-foreground placeholder:text-foreground/70 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                        @error('profile_image') <div class="error-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-2">
                        <label class="block text-sm mb-1" for="edit-student-id-image">Student ID Image</label>
                        @if($temp_student_id_image)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $temp_student_id_image) }}" alt="Current Student ID" class="w-16 h-16 rounded object-cover">
                                <p class="text-xs text-gray-500 mt-1">Current student ID image</p>
                        </div>
                        @endif
                        <input 
                            wire:model.defer="student_id_image" 
                            id="edit-student-id-image"
                            type="file"
                            accept="image/*"
                            class="h-10 w-full rounded-md border bg-background px-3 py-2 ring-offset-background file:border-0 file:bg-transparent file:font-medium file:text-foreground placeholder:text-foreground/70 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                        @error('student_id_image') <div class="error-message">{{ $message }}</div> @enderror
                    </div>
                </div>
            </form>

            <x-slot:footer>
                <button data-tw-dismiss="modal" type="button" wire:click="$set('showEditModal', false)" class="cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 text-foreground hover:bg-foreground/5 bg-background border-foreground/20 h-10 px-4 py-2 mr-1 w-24">
                    Cancel
                </button>
                <button type="button" wire:click="updateStudent" class="cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 bg-(--color)/20 border-(--color)/60 text-(--color) hover:bg-(--color)/5 [--color:var(--color-primary)] h-10 px-4 py-2 w-24">
                    Update
                </button>
            </x-slot:footer>
        </x-menu.modal>
        <!-- BEGIN: Data List -->
        <div class="col-span-12 overflow-auto lg:overflow-visible">
            <div class="relative w-full overflow-auto">
                <table class="w-full caption-bottom border-separate border-spacing-y-[10px] -mt-2">
                    <thead class="[&amp;_tr]:border-b-0 [&amp;_tr_th]:h-10">
                        <tr class="transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted border-b-0">
                            <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0">
                                PROFILE
                            </th>
                            <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0">
                                STUDENT ID
                            </th>
                            <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0">
                                NAME
                            </th>
                            <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0">
                                EMAIL
                            </th>
                            <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0">
                                COURSE
                            </th>
                            <th class="h-12 px-4 align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0 text-center">
                                STATUS
                            </th>
                            <th class="h-12 px-4 align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0 text-center">
                                CREATED DATE
                            </th>
                            <!-- <th class="h-12 px-4 align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0 text-center">
                                ACTIONS
                            </th> -->
                        </tr>
                    </thead>
                    <tbody class="[&amp;_tr:last-child]:border-0">
                        @forelse($students as $item)
                        <tr class="transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted border-b-0">
                            <td class="shadow-[3px_3px_5px_#0000000b] first:rounded-l-xl last:rounded-r-xl box rounded-none p-4 align-middle [&amp;:has([role=checkbox])]:pr-0 border-y border-foreground/10 bg-background first:border-l last:border-r">
                                <div class="flex items-center">
                                    @if($item->profile_image)
                                        <img src="{{ asset('storage/' . $item->profile_image) }}" alt="Profile" class="w-10 h-10 rounded-full object-cover">
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center">
                                            <svg class="w-6 h-6 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="shadow-[3px_3px_5px_#0000000b] first:rounded-l-xl last:rounded-r-xl box rounded-none p-4 align-middle [&amp;:has([role=checkbox])]:pr-0 border-y border-foreground/10 bg-background first:border-l last:border-r">
                                <div class="font-medium text-sm">{{ $item->student_id }}</div>
                            </td>
                            <td class="shadow-[3px_3px_5px_#0000000b] first:rounded-l-xl last:rounded-r-xl box rounded-none p-4 align-middle [&amp;:has([role=checkbox])]:pr-0 border-y border-foreground/10 bg-background first:border-l last:border-r">
                                <div class="font-medium">
                                    {{ $item->first_name }} {{ $item->middle_name }} {{ $item->last_name }}
                                    @if($item->suffix)
                                        , {{ $item->suffix }}
                                    @endif
                                </div>
                            </td>
                            <td class="shadow-[3px_3px_5px_#0000000b] first:rounded-l-xl last:rounded-r-xl box rounded-none p-4 align-middle [&amp;:has([role=checkbox])]:pr-0 border-y border-foreground/10 bg-background first:border-l last:border-r">
                                <div class="text-sm text-gray-600">{{ $item->email }}</div>
                            </td>
                            <td class="shadow-[3px_3px_5px_#0000000b] first:rounded-l-xl last:rounded-r-xl box rounded-none p-4 align-middle [&amp;:has([role=checkbox])]:pr-0 border-y border-foreground/10 bg-background first:border-l last:border-r">
                                <div class="text-sm">
                                    @if($item->course)
                                        {{ $item->course->course_name }}
                                    @else
                                        <span class="text-gray-400">No course assigned</span>
                                    @endif
                                </div>
                            </td>
                            <td class="shadow-[3px_3px_5px_#0000000b] first:rounded-l-xl last:rounded-r-xl box rounded-none p-4 align-middle [&amp;:has([role=checkbox])]:pr-0 border-y border-foreground/10 bg-background first:border-l last:border-r">
                                <div class="flex items-center justify-center">
                                    @if($item->status === 'active')
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                            Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                            </svg>
                                            Inactive
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="shadow-[3px_3px_5px_#0000000b] first:rounded-l-xl last:rounded-r-xl box rounded-none p-4 align-middle [&amp;:has([role=checkbox])]:pr-0 border-y border-foreground/10 bg-background first:border-l last:border-r text-center">
                                <div class="text-sm">{{ optional($item->created_at)->format('M d, Y') ?? 'N/A' }}</div>
                                <div class="text-xs opacity-70">{{ optional($item->created_at)->format('h:i A') ?? '' }}</div>
                            </td>
                            <!-- <td class="shadow-[3px_3px_5px_#0000000b] first:rounded-l-xl last:rounded-r-xl box rounded-none p-4 align-middle [&amp;:has([role=checkbox])]:pr-0 border-y border-foreground/10 bg-background first:border-l last:border-r">
                                <div class="flex items-center justify-center">
                                    <button wire:click="openEditModal({{ $item->id }})" class="mr-3 flex items-center text-blue-600 hover:text-blue-800">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 mr-1">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                        </svg>
                                        Edit
                                    </button>
                                    <button wire:click="openDeleteModal({{ $item->id }})" class="text-red-600 hover:text-red-800 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 mr-1">
                                            <path d="M3 6h18"></path>
                                            <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                            <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                        </svg>
                                        Delete
                                    </button>
                                </div>
                            </td> -->
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-8 text-gray-500">
                                No student accounts found. Be the first to add a student account!
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <!-- END: Data List -->
        <!-- BEGIN: Pagination -->
        <x-menu.pagination :paginator="$students" :perPageOptions="[10, 25, 35, 50]" />
        <!-- END: Pagination -->
    </div>
    <!-- Delete Student Modal -->
    @if($showDeleteModal)
    <x-menu.modal 
        :showButton="false" 
        modalId="delete-student-modal" 
        title="Delete Student Account" 
        description="This action cannot be undone."
        size="md"
        :isOpen="$showDeleteModal">
        <div class="text-center py-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-10 h-10 text-red-500 mx-auto mb-3"><path d="M3 6h18"></path><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path></svg>
            <div class="mt-2 text-sm">Are you sure you want to delete this student account?</div>
        </div>
        <x-slot:footer>
            <button data-tw-dismiss="modal" type="button" wire:click="cancelDelete" class="cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 text-foreground hover:bg-foreground/5 bg-background border-foreground/20 h-10 px-4 py-2 mr-1 w-24">
                Cancel
            </button>
            <button type="button" wire:click="deleteConfirmed" class="cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 bg-(--color)/20 border-(--color)/60 text-(--color) hover:bg-(--color)/5 [--color:var(--color-danger)] h-10 px-4 py-2 w-32">
                Delete
            </button>
        </x-slot:footer>
    </x-menu.modal>
    @endif
 
</div>