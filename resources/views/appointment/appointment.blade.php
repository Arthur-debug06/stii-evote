
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
    <meta name="description" content="Dentrack is super flexible, powerful, clean & modern responsive tailwind admin template with unlimited possibilities.">
    <meta name="keywords" content="admin template, midone Admin Template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="LEFT4CODE">
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin="">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <title>Login - Dentrack - Tailwind Admin Dashboard Template</title>
    <!-- BEGIN: CSS Assets-->
    <!-- END: CSS Assets-->
    @vite('resources/css/app.css')
    <script src="/js/appointment/appointment.js" defer></script>
</head>
<!-- END: Head -->
<body>
<!--     
    <div class="page-loader bg-background fixed inset-0 z-[100] flex items-center justify-center transition-opacity">
        <div class="loader-spinner !w-14"></div>
    </div> -->
    <div class="relative h-screen lg:overflow-hidden bg-primary bg-noise xl:bg-background xl:bg-none before:hidden before:xl:block before:content-[''] before:w-[57%] before:-mt-[28%] before:-mb-[16%] before:-ml-[12%] before:absolute before:inset-y-0 before:left-0 before:transform before:rotate-[6deg] before:bg-primary/[.95] before:bg-noise before:rounded-[35%] after:hidden after:xl:block after:content-[''] after:w-[57%] after:-mt-[28%] after:-mb-[16%] after:-ml-[12%] after:absolute after:inset-y-0 after:left-0 after:transform after:rotate-[6deg] after:border after:bg-accent after:bg-cover after:blur-xl after:rounded-[35%] after:border-[20px] after:border-primary">
        <div class="p-3 sm:px-8 relative h-full before:hidden before:xl:block before:w-[57%] before:-mt-[20%] before:-mb-[13%] before:-ml-[12%] before:absolute before:inset-y-0 before:left-0 before:transform before:rotate-[-6deg] before:bg-primary/40 before:bg-noise before:border before:border-primary/50 before:opacity-60 before:rounded-[20%]">
            <div class="container relative z-10 mx-auto sm:px-20">
                <div class="block grid-cols-2 gap-4 xl:grid">
                    <!-- BEGIN: Login Info -->
                    <div class="hidden min-h-screen flex-col xl:flex">
                        <a class="flex items-center pt-10" href="">
                            <img class="w-6" src="{{asset('assets/dist/images/logo.svg')}}" alt="stii-evote Logo">
                            <span class="ml-3 text-xl font-medium text-white">
                                stii-evote <span class="font-light opacity-70">Appointment System</span>
                            </span>
                        </a>
                        <div class="my-auto">
                            <img class="-mt-16 w-1/2" src="{{asset('assets/dist/images/illustration.svg')}}" alt="Midone - Tailwind Admin Dashboard Template">
                            <div class="mt-10 text-4xl font-medium leading-tight text-white">
                                stii-evote<br>
                                Sign in to your account.
                            </div>
                            <div class="mt-5 text-lg text-white opacity-60">
                                Manage all your clinic appointments in one place
                            </div>
                        </div>
                    </div>
                    <!-- END: Login Info -->
                    <!-- BEGIN: Login Form -->
                    <div class="my-10 flex h-screen py-5 xl:my-0 xl:h-auto xl:py-0">
                        <div class="box relative p-5 before:absolute before:inset-0 before:mx-3 before:-mb-3 before:border before:border-foreground/10 before:bg-background/30 before:shadow-[0px_3px_5px_#0000000b] before:z-[-1] before:rounded-xl after:absolute after:inset-0 after:border after:border-foreground/10 after:bg-background after:shadow-[0px_3px_5px_#0000000b] after:rounded-xl after:z-[-1] after:backdrop-blur-md mx-auto my-auto w-full px-5 py-8 sm:w-3/4 sm:px-8 lg:w-2/4 xl:ml-24 xl:w-auto xl:p-0 xl:before:hidden xl:after:hidden">
                            <h2 class="text-center text-2xl font-semibold xl:text-left xl:text-3xl">
                                Book Appointment
                            </h2>
                            <div class="mt-2 text-center opacity-70 xl:hidden">
                                Schedule your appointment with our dental clinic. Fill out the form below and we'll contact you to confirm.
                            </div>
                            
                            @if (session('success'))
                                <div class="mt-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                                    {{ session('success') }}
                                </div>
                            @endif

                            @if ($errors->any())
                                <div class="mt-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                                    @foreach ($errors->all() as $error)
                                        <div>{{ $error }}</div>
                                    @endforeach
                                </div>
                            @endif

                            <form method="POST" action="{{ route('appointment.store') }}" id="appointment-form" class="mt-8 flex flex-col gap-5">
                                @csrf
                                
                                <!-- Full Name -->
                                <div>
                                    <label for="fullname" class="block text-sm font-medium text-foreground/70 mb-2">Full Name</label>
                                    <input class="h-10 w-full rounded-md border bg-background ring-offset-background file:border-0 file:bg-transparent file:font-medium file:text-foreground placeholder:text-foreground/70 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 box block min-w-full px-5 py-6 xl:min-w-[28rem]" 
                                           type="text" 
                                           id="fullname" 
                                           name="fullname" 
                                           value="{{ old('fullname') }}" 
                                           placeholder="Enter your full name" 
                                           required>
                                </div>

                                <!-- Email -->
                                <div>
                                    <label for="email" class="block text-sm font-medium text-foreground/70 mb-2">Email Address</label>
                                    <input class="h-10 w-full rounded-md border bg-background ring-offset-background file:border-0 file:bg-transparent file:font-medium file:text-foreground placeholder:text-foreground/70 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 box block min-w-full px-5 py-6 xl:min-w-[28rem]" 
                                           type="email" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email') }}" 
                                           placeholder="Enter your email address" 
                                           required>
                                </div>

                                <!-- Contact Number -->
                                <div>
                                    <label for="contact_number" class="block text-sm font-medium text-foreground/70 mb-2">Contact Number</label>
                                    <input class="h-10 w-full rounded-md border bg-background ring-offset-background file:border-0 file:bg-transparent file:font-medium file:text-foreground placeholder:text-foreground/70 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 box block min-w-full px-5 py-6 xl:min-w-[28rem]" 
                                           type="tel" 
                                           id="contact_number" 
                                           name="contact_number" 
                                           value="{{ old('contact_number') }}" 
                                           placeholder="Enter your contact number" 
                                           required>
                                </div>

                                <!-- Appointment Date & Time -->
                                <div>
                                    <label for="appointment_datetime" class="block text-sm font-medium text-foreground/70 mb-2">Appointment Date & Time</label>
                                    <input class="h-10 w-full rounded-md border bg-background ring-offset-background file:border-0 file:bg-transparent file:font-medium file:text-foreground placeholder:text-foreground/70 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 box block min-w-full px-5 py-6 xl:min-w-[28rem]" 
                                           type="datetime-local" 
                                           id="appointment_datetime" 
                                           name="appointment_datetime" 
                                           value="{{ old('appointment_datetime') }}" 
                                           min="{{ date('Y-m-d\TH:i', strtotime('+1 day 9:00')) }}"
                                           max="{{ date('Y-m-d\T16:00', strtotime('+1 year')) }}"
                                           required>
                                    <small class="text-xs text-foreground/60 mt-1 block">Clinic hours: 9:00 AM - 4:00 PM, Monday to Saturday</small>
                                </div>

                                <!-- Reason -->
                                <div>
                                    <label for="reason" class="block text-sm font-medium text-foreground/70 mb-2">Reason for Visit</label>
                                    <textarea class="min-h-[120px] w-full rounded-md border bg-background ring-offset-background file:border-0 file:bg-transparent file:font-medium file:text-foreground placeholder:text-foreground/70 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 box block min-w-full px-5 py-4 xl:min-w-[28rem] resize-none" 
                                              id="reason" 
                                              name="reason" 
                                              placeholder="Please describe the reason for your visit (e.g., routine cleaning, toothache, consultation)" 
                                              required>{{ old('reason') }}</textarea>
                                </div>
                            </form>
                            
                            <div class="mt-5 text-center xl:mt-10 xl:text-left">
                                <button type="submit" 
                                        form="appointment-form" 
                                        id="submit-btn"
                                        class="cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg]:size-4 [&_svg]:shrink-0 bg-(--color)/20 border-(--color)/60 text-(--color) hover:bg-(--color)/5 [--color:var(--color-primary)] h-10 box w-full px-4 py-5">
                                    <span class="submit-text">Book Appointment</span>
                                    <span class="loading-spinner hidden">
                                        <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </span>
                                </button>
                                <a href="{{ route('login') }}" 
                                   class="[--color:var(--color-foreground)] cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg]:size-4 [&_svg]:shrink-0 text-(--color) hover:bg-(--color)/5 bg-background border-(--color)/20 h-10 box mt-4 w-full px-4 py-5">
                                    Back to Login
                                </a>
                            </div>
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