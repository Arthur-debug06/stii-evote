<!DOCTYPE html><!--
Template Name: Midone - Admin Dashboard Template
Author: Left4code
Website: http://www.left4code.com/
Contact: leftforcode@gmail.com
Purchase: https://themeforest.net/user/left4code/portfolio
Renew Support: https://themeforest.net/user/left4code/portfolio
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
-->
<html xmlns="http://www.w3.org/1999/xhtml" lang="en"><!-- BEGIN: Head -->

<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="edjGgac9mtFsWPbrGHhItAsXhkBE8VClTqg62ZE4">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description"
        content="Dentrack is super flexible, powerful, clean & modern responsive tailwind admin template with unlimited possibilities.">
    <meta name="keywords"
        content="admin template, midone Admin Template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="LEFT4CODE">
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin="">
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
        rel="stylesheet">
    <title>stii-evote - Register</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    <!-- BEGIN: CSS Assets-->
    <!-- END: CSS Assets-->
    @vite('resources/css/app.css')
    <script src="/js/register/register.js" defer></script>
</head>
<!-- END: Head -->

<body>
    <!--     
    <div class="page-loader bg-background fixed inset-0 z-[100] flex items-center justify-center transition-opacity">
        <div class="loader-spinner !w-14"></div>
    </div> -->
    <div
        class="relative h-screen overflow-y-auto overflow-x-hidden bg-primary bg-noise xl:bg-background xl:bg-none before:hidden before:xl:block before:content-[''] before:w-[57%] before:-mt-[28%] before:-mb-[16%] before:-ml-[12%] before:absolute before:inset-y-0 before:left-0 before:transform before:rotate-[6deg] before:bg-primary/[.95] before:bg-noise before:rounded-[35%] after:hidden after:xl:block after:content-[''] after:w-[57%] after:-mt-[28%] after:-mb-[16%] after:-ml-[12%] after:absolute after:inset-y-0 after:left-0 after:transform after:rotate-[6deg] after:border after:bg-accent after:bg-cover after:blur-xl after:rounded-[35%] after:border-[20px] after:border-primary">
        <div
            class="p-3 sm:px-8 relative h-full before:hidden before:xl:block before:w-[57%] before:-mt-[20%] before:-mb-[13%] before:-ml-[12%] before:absolute before:inset-y-0 before:left-0 before:transform before:rotate-[-6deg] before:bg-primary/40 before:bg-noise before:border before:border-primary/50 before:opacity-60 before:rounded-[20%]">
            <div class="container relative z-10 mx-auto sm:px-20">
                <div class="block grid-cols-2 gap-4 xl:grid">
                    <!-- BEGIN: Login Info -->
                    <div class="hidden min-h-screen flex-col xl:flex">
                        <a class="flex items-center pt-10" href="">
                            @php
                                $loginTopLogo = \App\Models\system_settings::where('key', 'login_top_logo')
                                    ->where('type', 'image')
                                    ->where('module_id', 6)
                                    ->where('status', 'active')
                                    ->first();
                                $topLogoPath = $loginTopLogo ? asset('storage/' . $loginTopLogo->value) : asset('assets/dist/images/logo.svg');
                            @endphp
                            <img class="w-6" src="{{ $topLogoPath }}" alt="Login Logo">
                            @php
                                $loginTopText = \App\Models\system_settings::where('key', 'login_top_text')
                                    ->where('type', 'text')
                                    ->where('module_id', 4)
                                    ->where('status', 'active')
                                    ->first();
                                $topText = $loginTopText ? $loginTopText->value : 'stii-evote Student Portal';
                            @endphp
                            <span class="ml-3 text-xl font-medium text-white">
                                {{ $topText }}
                            </span>
                        </a>
                        <div class="my-auto">
                            @php
                                $loginCenterLogo = \App\Models\system_settings::where('key', 'login_center_logo')
                                    ->where('type', 'image')
                                    ->where('module_id', 3)
                                    ->where('status', 'active')
                                    ->first();
                                $centerLogoPath = $loginCenterLogo ? asset('storage/' . $loginCenterLogo->value) : asset('assets/dist/images/illustration.svg');
                            @endphp
                            <img class="-mt-16 w-1/2" src="{{ $centerLogoPath }}" alt="Login Illustration">
                            @php
                                $loginCenterText = \App\Models\system_settings::where('key', 'login_center_text')
                                    ->where('type', 'text')
                                    ->where('module_id', 5)
                                    ->where('status', 'active')
                                    ->first();
                                $centerText = $loginCenterText ? $loginCenterText->value : 'stii-evote<br>Sign in to your account.';
                            @endphp
                            <div class="mt-10 text-4xl font-medium leading-tight text-white">
                                {!! $centerText !!}
                            </div>
                            <!-- <div class="mt-5 text-lg text-white opacity-60">
                                Access your student portal and participate in voting
                            </div> -->
                        </div>
                    </div>
                    <!-- END: Login Info -->
                    <!-- BEGIN: Login Form -->
                    <div class="my-10 flex h-screen py-5 xl:my-0 xl:h-auto xl:py-0">
                        <div
                            class="box relative p-5 before:absolute before:inset-0 before:mx-3 before:-mb-3 before:border before:border-foreground/10 before:bg-background/30 before:shadow-[0px_3px_5px_#0000000b] before:z-[-1] before:rounded-xl after:absolute after:inset-0 after:border after:border-foreground/10 after:bg-background after:shadow-[0px_3px_5px_#0000000b] after:rounded-xl after:z-[-1] after:backdrop-blur-md mx-auto my-auto w-full px-5 py-8 sm:w-3/4 sm:px-8 lg:w-2/4 xl:ml-24 xl:w-auto xl:p-0 xl:before:hidden xl:after:hidden">
                            <h2 class="text-center text-2xl font-semibold xl:text-left xl:text-3xl">
                                Student Registration
                            </h2>
                            <div class="mt-2 text-center opacity-70 xl:hidden">
                                Fill in your details to create your student account and join stii-evote
                            </div>
                            <form id="registration-form" method="POST" action="{{ route('register.store') }}"
                                enctype="multipart/form-data">
                                @csrf

                                @if ($errors->any())
                                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                                        @foreach ($errors->all() as $error)
                                            <div>{{ $error }}</div>
                                        @endforeach
                                    </div>
                                @endif

                                @if(session('success'))
                                    <x-menu.modal :showButton="false" modalId="success-dialog"
                                        title="Registration Successful!" description="{{ session('success') }}" size="md"
                                        :isOpen="true">

                                        <div class="text-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="mx-auto text-green-500 mb-4">
                                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                                <polyline points="22,4 12,14.01 9,11.01"></polyline>
                                            </svg>
                                            <p class="text-sm text-gray-600 mb-4">
                                                Your account is now pending approval. You will be notified once approved.
                                            </p>
                                        </div>

                                        <x-slot:footer>
                                            <button data-tw-dismiss="modal" type="button"
                                                class="cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 text-foreground hover:bg-foreground/5 bg-background border-foreground/20 h-10 px-4 py-2 mr-1 w-24">
                                                Close
                                            </button>
                                            <a href="{{ route('login') }}"
                                                class="cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 bg-(--color)/20 border-(--color)/60 text-(--color) hover:bg-(--color)/5 [--color:var(--color-primary)] h-10 px-4 py-2 w-24">
                                                Go to Login
                                            </a>
                                        </x-slot:footer>
                                    </x-menu.modal>

                                    <script>
                                        document.addEventListener('DOMContentLoaded', function () {
                                            // Auto-trigger the modal
                                            const modal = document.getElementById('success-dialog');
                                            if (modal) {
                                                modal.classList.add('show');
                                            }
                                        });
                                    </script>
                                @endif

                                <div class="intro-x mt-6">
                                    <!-- All fields in pairs -->
                                    <div class="grid grid-cols-2 gap-2">
                                        <!-- Student ID and Course -->
                                        <div class="mb-2">
                                            <label class="block text-sm mb-1">Student ID</label>
                                            <input type="text" name="student_id"
                                                class="h-10 w-full rounded-md border bg-background px-3 py-2 ring-offset-background file:border-0 file:bg-transparent file:font-medium file:text-foreground placeholder:text-foreground/70 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                                                placeholder="202023-4334" pattern="[0-9]{6}-[0-9]{4}"
                                                title="Please enter a valid student ID in format: 202023-4334"
                                                value="{{ old('student_id') }}" required>
                                            @error('student_id')
                                                <div class="error-message">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-2">
                                            <label class="block text-sm mb-1">Course</label>
                                            <select name="course_id"
                                                class="h-10 w-full rounded-md border bg-background px-3 py-2 ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                                                required>
                                                <option value="">Select Course</option>
                                                @foreach($courses as $course)
                                                    <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                                        {{ $course->course_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('course_id')
                                                <div class="error-message">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Department and Year Level -->
                                        <div class="mb-2">
                                            <label class="block text-sm mb-1">Department</label>
                                            <select name="department_id"
                                                class="h-10 w-full rounded-md border bg-background px-3 py-2 ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                                                required>
                                                <option value="">Select Department</option>
                                                @foreach($departments as $department)
                                                    <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                                        {{ $department->department_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('department_id')
                                                <div class="error-message">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-2">
                                            <label class="block text-sm mb-1">Year Level</label>
                                            <select name="year_level"
                                                class="h-10 w-full rounded-md border bg-background px-3 py-2 ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                                                required>
                                                <option value="">Select Year Level</option>
                                                <option value="1st Year" {{ old('year_level') == '1st Year' ? 'selected' : '' }}>1st Year</option>
                                                <option value="2nd Year" {{ old('year_level') == '2nd Year' ? 'selected' : '' }}>2nd Year</option>
                                                <option value="3rd Year" {{ old('year_level') == '3rd Year' ? 'selected' : '' }}>3rd Year</option>
                                                <option value="4th Year" {{ old('year_level') == '4th Year' ? 'selected' : '' }}>4th Year</option>
                                                <!-- 5th Year removed per requirements -->
                                            </select>
                                            @error('year_level')
                                                <div class="error-message">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Gender and First Name -->
                                        <div class="mb-2">
                                            <label class="block text-sm mb-1">Gender</label>
                                            <select name="gender"
                                                class="h-10 w-full rounded-md border bg-background px-3 py-2 ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                                                required>
                                                <option value="">Select Gender</option>
                                                <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male
                                                </option>
                                                <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>
                                                    Female</option>
                                            </select>
                                            @error('gender')
                                                <div class="error-message">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-2">
                                            <label class="block text-sm mb-1">First Name</label>
                                            <input type="text" name="first_name"
                                                class="h-10 w-full rounded-md border bg-background px-3 py-2 ring-offset-background file:border-0 file:bg-transparent file:font-medium file:text-foreground placeholder:text-foreground/70 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                                                placeholder="Enter First Name" value="{{ old('first_name') }}" required>
                                            @error('first_name')
                                                <div class="error-message">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Middle Name and Last Name -->
                                        <div class="mb-2">
                                            <label class="block text-sm mb-1">Middle Name</label>
                                            <input type="text" name="middle_name"
                                                class="h-10 w-full rounded-md border bg-background px-3 py-2 ring-offset-background file:border-0 file:bg-transparent file:font-medium file:text-foreground placeholder:text-foreground/70 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                                                placeholder="Enter Middle Name" value="{{ old('middle_name') }}">
                                            @error('middle_name')
                                                <div class="error-message">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-2">
                                            <label class="block text-sm mb-1">Last Name</label>
                                            <input type="text" name="last_name"
                                                class="h-10 w-full rounded-md border bg-background px-3 py-2 ring-offset-background file:border-0 file:bg-transparent file:font-medium file:text-foreground placeholder:text-foreground/70 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                                                placeholder="Enter Last Name" value="{{ old('last_name') }}" required>
                                            @error('last_name')
                                                <div class="error-message">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Suffix and Email -->
                                        <div class="mb-2">
                                            <label class="block text-sm mb-1">Suffix</label>
                                            <input type="text" name="suffix"
                                                class="h-10 w-full rounded-md border bg-background px-3 py-2 ring-offset-background file:border-0 file:bg-transparent file:font-medium file:text-foreground placeholder:text-foreground/70 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                                                placeholder="Enter Suffix (if any)" value="{{ old('suffix') }}">
                                            @error('suffix')
                                                <div class="error-message">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-2">
                                            <label class="block text-sm mb-1">Email</label>
                                            <input type="email" name="email"
                                                class="h-10 w-full rounded-md border bg-background px-3 py-2 ring-offset-background file:border-0 file:bg-transparent file:font-medium file:text-foreground placeholder:text-foreground/70 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                                                placeholder="Enter Email" value="{{ old('email') }}" required>
                                            @error('email')
                                                <div class="error-message">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Password and Date of Birth -->
                                        <div class="mb-2">
                                            <label class="block text-sm mb-1">Password</label>
                                            <input type="password" name="password"
                                                class="h-10 w-full rounded-md border bg-background px-3 py-2 ring-offset-background file:border-0 file:bg-transparent file:font-medium file:text-foreground placeholder:text-foreground/70 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                                                placeholder="Enter Password" required>
                                            <div id="password-hint" class="text-xs mt-1" aria-live="polite"></div>
                                            @error('password')
                                                <div class="error-message">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-2">
                                            <label class="block text-sm mb-1">Confirm Password</label>
                                            <input type="password" name="password_confirmation"
                                                class="h-10 w-full rounded-md border bg-background px-3 py-2 ring-offset-background file:border-0 file:bg-transparent file:font-medium file:text-foreground placeholder:text-foreground/70 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                                                placeholder="Confirm Password" required>
                                            <div id="confirm-password-hint" class="text-xs mt-1" aria-live="polite">
                                            </div>
                                            @error('password_confirmation')
                                                <div class="error-message">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-2">
                                            <label class="block text-sm mb-1">Date of Birth</label>
                                            <input type="date" name="date_of_birth"
                                                class="h-10 w-full rounded-md border bg-background px-3 py-2 ring-offset-background file:border-0 file:bg-transparent file:font-medium file:text-foreground placeholder:text-foreground/70 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                                                value="{{ old('date_of_birth') }}" required>
                                            @error('date_of_birth')
                                                <div class="error-message">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Age and Address -->
                                        <div class="mb-2">
                                            <label class="block text-sm mb-1">Age</label>
                                            <input type="number" name="age" id="age"
                                                class="h-10 w-full rounded-md border bg-background px-3 py-2 ring-offset-background file:border-0 file:bg-transparent file:font-medium file:text-foreground placeholder:text-foreground/70 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                                                placeholder="Age" value="{{ old('age') }}" min="0" max="150" readonly
                                                required>
                                            <div id="age-hint" class="text-xs mt-1" aria-live="polite"></div>
                                            @error('age')
                                                <div class="error-message">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-2">
                                            <label class="block text-sm mb-1">Address</label>
                                            <textarea name="address"
                                                class="h-20 w-full rounded-md border bg-background px-3 py-2 ring-offset-background file:border-0 file:bg-transparent file:font-medium file:text-foreground placeholder:text-foreground/70 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                                                placeholder="Enter Complete Address" required
                                                rows="3">{{ old('address') }}</textarea>
                                            @error('address')
                                                <div class="error-message">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <!-- <div class="mb-2">
                                            <label class="block text-sm mb-1">Gender</label>
                                            <select name="gender"
                                                class="h-10 w-full rounded-md border bg-background px-3 py-2 ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                                                required>
                                                <option value="">Select Gender</option>
                                                <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>
                                                    Male
                                                </option>
                                                <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>
                                                    Female</option>
                                                <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>
                                                    Other</option>
                                            </select>
                                            @error('gender')
                                                <div class="error-message">{{ $message }}</div>
                                            @enderror
                                        </div> -->
                                        <div class="mb-2">
                                            <label class="block text-sm mb-1">Marital Status</label>
                                            <select name="marital_status"
                                                class="h-10 w-full rounded-md border bg-background px-3 py-2 ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                                                required>
                                                <option value="">Select Marital Status</option>
                                                <option value="Single" {{ old('marital_status') == 'Single' ? 'selected' : '' }}>
                                                    Single
                                                </option>
                                                <option value="Married" {{ old('marital_status') == 'Married' ? 'selected' : '' }}>
                                                    Married</option>
                                                <option value="Widowed" {{ old('marital_status') == 'Widowed' ? 'selected' : '' }}>
                                                    Widowed</option>
                                                <option value="Divorced" {{ old('marital_status') == 'Divorced' ? 'selected' : '' }}>
                                                    Divorced</option>
                                            </select>
                                            @error('marital_status')
                                                <div class="error-message">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <!-- Profile Picture and Student ID Picture -->
                                        <div class="mb-2">
                                            <label class="block text-sm mb-1">Profile Picture</label>
                                            <input type="file" name="profile_image"
                                                class="h-10 w-full rounded-md border bg-background px-3 py-2 ring-offset-background file:border-0 file:bg-transparent file:font-medium file:text-foreground placeholder:text-foreground/70 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                                                accept="image/*">
                                            @error('profile_image')
                                                <div class="error-message">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-2">
                                            <label class="block text-sm mb-1">Student ID(Front)</label>
                                            <input type="file" name="student_id_image"
                                                class="h-10 w-full rounded-md border bg-background px-3 py-2 ring-offset-background file:border-0 file:bg-transparent file:font-medium file:text-foreground placeholder:text-foreground/70 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                                                accept="image/*">
                                            @error('student_id_image')
                                                <div class="error-message">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-2">
                                            <label class="block text-sm mb-1">Student ID(Back)</label>
                                            <input type="file" name="student_id_image_back"
                                                class="h-10 w-full rounded-md border bg-background px-3 py-2 ring-offset-background file:border-0 file:bg-transparent file:font-medium file:text-foreground placeholder:text-foreground/70 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                                                accept="image/*">
                                            @error('student_id_image_back')
                                                <div class="error-message">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-5 xl:mt-8 text-center xl:text-left">
                                    <div class="flex gap-3">
                                        <button type="submit" id="register-button"
                                            class="cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg]:size-4 [&_svg]:shrink-0 bg-(--color)/20 border-(--color)/60 text-(--color) hover:bg-(--color)/5 [--color:var(--color-primary)] h-10 px-4 py-2 flex-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round" data-lucide="user-plus"
                                                class="lucide lucide-user-plus size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25">
                                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                                <circle cx="9" cy="7" r="4"></circle>
                                                <line x1="19" x2="19" y1="8" y2="14"></line>
                                                <line x1="22" x2="16" y1="11" y2="11"></line>
                                            </svg>
                                            Register
                                        </button>
                                        <a href="{{ route('login') }}"
                                            class="[--color:var(--color-foreground)] cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg]:size-4 [&_svg]:shrink-0 text-(--color) hover:bg-(--color)/5 bg-background border-(--color)/20 h-10 px-4 py-2 flex-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round" data-lucide="log-in"
                                                class="lucide lucide-log-in size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25">
                                                <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                                                <polyline points="10,17 15,12 10,7"></polyline>
                                                <line x1="15" x2="3" y1="12" y2="12"></line>
                                            </svg>
                                            Sign in
                                        </a>
                                    </div>
                                </div>
                        </div>
                        </form>
                        <!-- <div class="mt-10 text-center opacity-70 xl:mt-24 xl:text-left">
                                By signin up, you agree to our
                                <a class="text-primary" href="">
                                    Terms and Conditions
                                </a>
                                &
                                <a class="text-primary" href="">
                                    Privacy Policy
                                </a>
                            </div> -->
                    </div>
                </div>
                <!-- END: Login Form -->
            </div>
        </div>
    </div>
    </div>
</body>

</html>