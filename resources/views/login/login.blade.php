
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
    <title>stii-evote - Login</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    <!-- BEGIN: CSS Assets-->
    <!-- END: CSS Assets-->
    @vite('resources/css/app.css')
    <script src="/js/login.js" defer></script>
    
    <!-- Include notification toast component -->
    <x-menu.notification-toast />
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
                                $topText = $loginTopText ? $loginTopText->value : 'STII-evote Student Portal';
                            @endphp
                            <span class="ml-3 text-xl font-medium text-white">
                                STII-Evote
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
                                STII-Evote
                            </div>
                            <!-- <div class="mt-5 text-lg text-white opacity-60">
                                Access your student portal and participate in voting
                            </div> -->
                        </div>
                    </div>
                    <!-- END: Login Info -->
                    <!-- BEGIN: Login Form -->
                    <div class="my-10 flex h-screen py-5 xl:my-0 xl:h-auto xl:py-0">
                        <div class="box relative p-5 before:absolute before:inset-0 before:mx-3 before:-mb-3 before:border before:border-foreground/10 before:bg-background/30 before:shadow-[0px_3px_5px_#0000000b] before:z-[-1] before:rounded-xl after:absolute after:inset-0 after:border after:border-foreground/10 after:bg-background after:shadow-[0px_3px_5px_#0000000b] after:rounded-xl after:z-[-1] after:backdrop-blur-md mx-auto my-auto w-full px-5 py-8 sm:w-3/4 sm:px-8 lg:w-2/4 xl:ml-24 xl:w-auto xl:p-0 xl:before:hidden xl:after:hidden">
                            <h2 class="text-center text-2xl font-semibold xl:text-left xl:text-3xl">
                                Sign In
                            </h2>
                            <div class="mt-2 text-center opacity-70 xl:hidden">
                                A few more clicks to sign in to your account. Access your student portal and participate in voting
                            </div>
                            <form method="POST" action="{{ route('authenticate') }}" class="mt-8 flex flex-col gap-5">
                                @csrf
                                @if ($errors->any())
                                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                                        @foreach ($errors->all() as $error)
                                            <div>{{ $error }}</div>
                                        @endforeach
                                    </div>
                                @endif
                                <input class="h-10 w-full rounded-md border bg-background ring-offset-background file:border-0 file:bg-transparent file:font-medium file:text-foreground placeholder:text-foreground/70 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 box block min-w-full px-5 py-6 xl:min-w-[28rem]" type="email" name="email" value="{{ old('email') }}" placeholder="Email" required>
                                <input class="h-10 w-full rounded-md border bg-background ring-offset-background file:border-0 file:bg-transparent file:font-medium file:text-foreground placeholder:text-foreground/70 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 box block min-w-full px-5 py-6 xl:min-w-[28rem]" type="password" name="password" placeholder="Password" required>
                                <div class="flex text-xs sm:text-sm">
                                    <div class="flex gap-2.5 mr-auto flex-row items-center">
                                        <div class="bg-background border-foreground/70 relative size-4 rounded-sm border">
                                            <input class="peer relative z-10 size-full cursor-pointer opacity-0" type="checkbox" id="remember-me" name="remember">
                                            <div class="z-4 bg-foreground invisible absolute inset-0 flex items-center justify-center text-white peer-checked:visible">
                                                <i data-lucide="check" class="stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 size-4"></i>
                                            </div>
                                        </div>
                                        <label class="font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70 opacity-70" for="remember-me">Remember me</label>
                                    </div>
                                    <a class="opacity-70" href="{{ route('forgot-password') }}">Forgot Password?</a>
                                </div>
                            </form>
                            <div class="mt-5 text-center xl:mt-10 xl:text-left">
                                <button type="submit" form="login-form" class="cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg]:size-4 [&_svg]:shrink-0 bg-(--color)/20 border-(--color)/60 text-(--color) hover:bg-(--color)/5 [--color:var(--color-primary)] h-10 login-button box w-full px-4 py-5" onclick="document.querySelector('form').submit();">
                                    Login
                                </button>
                                <a class="[--color:var(--color-foreground)] cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg]:size-4 [&_svg]:shrink-0 text-(--color) hover:bg-(--color)/5 bg-background border-(--color)/20 h-10 box mt-4 w-full px-4 py-5" href="{{ route('register') }}">
                                    Register
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
    
    <!-- Login Toast Notification Script -->
    <script>
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

        // Check for successful login and trigger toast
        document.addEventListener('DOMContentLoaded', function() {
            // Check if there's a success message in the URL or session
            const urlParams = new URLSearchParams(window.location.search);
            const loginSuccess = urlParams.get('login') === 'success';
            
            // Check for success flash message
            const successMessage = document.querySelector('.bg-green-100, .alert-success');
            
            if (loginSuccess || successMessage) {
                // Wait for toast system to be ready
                setTimeout(() => {
                    triggerLoginToast();
                }, 1000);
            }
        });

        // Also trigger on form submission success (if using AJAX)
        document.addEventListener('submit', function(e) {
            if (e.target.tagName === 'FORM' && e.target.action.includes('authenticate')) {
                // Wait a bit for the login to process
                setTimeout(() => {
                    // Check if we're still on login page (failed) or redirected (success)
                    if (window.location.pathname !== '/login') {
                        triggerLoginToast();
                    }
                }, 2000);
            }
        });
    </script>
</body>
</html>