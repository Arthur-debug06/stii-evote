<!DOCTYPE html>
<!--
Template Name: Midone - Admin Dashboard Template
Author: Left4code
Website: http://www.left4code.com/
Contact: leftforcode@gmail.com
Purchase: https://themeforest.net/user/left4code/portfolio
Renew Support: https://themeforest.net/user/left4code/portfolio
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
-->
<html xmlns="http://www.w3.org/1999/xhtml" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<!-- BEGIN: Head -->
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="@yield('meta_description', 'Midone admin is super flexible, powerful, clean & modern responsive tailwind admin template with unlimited possibilities.')">
    <meta name="keywords" content="@yield('meta_keywords', 'admin template, midone Admin Template, dashboard template, flat admin template, responsive admin template, web app')">
    <meta name="author" content="@yield('meta_author', 'LEFT4CODE')">
   
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    <!-- Preconnect to external resources -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin="">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Page Title -->
    <title>stii-evote - @yield('title', 'Dashboard')</title>
    
    <!-- BEGIN: CSS Assets-->
    @stack('css_before')
    <!-- Main CSS -->
    @vite('resources/css/app.css')
    
    <!-- Theme-specific CSS -->
    <link rel="stylesheet" href="{{ asset('build/assets/side-menu-2rTsA0si.css') }}">
    <link rel="stylesheet" href="{{ asset('build/assets/top-menu-DD1CJIdR.css') }}">
    <link rel="stylesheet" href="{{ asset('build/assets/vector-map-BKm4S9nR.css') }}">
    <link rel="stylesheet" href="{{ asset('build/assets/tiny-slider-1wbSbS80.css') }}">
    <link rel="stylesheet" href="{{ asset('build/assets/simplebar-CKCzzWRK.css') }}">
   
    @stack('css_after')
    @stack('css')
    <!-- END: CSS Assets-->
    
    <!-- Additional Head Content -->
    @stack('head')
    <!-- Runtime favicon override: if a child view pushes a meta[name="page-favicon"] it will replace the default icons -->
    <script>
        (function() {
            function setLink(rel, href, type) {
                var link = document.querySelector('link[rel="' + rel + '"]');
                if (!link) {
                    link = document.createElement('link');
                    link.setAttribute('rel', rel);
                    document.getElementsByTagName('head')[0].appendChild(link);
                }
                link.setAttribute('href', href);
                if (type) link.setAttribute('type', type);
            }

            function applyFavicon(url, appleUrl) {
                if (!url) return;
                try {
                    setLink('icon', url, 'image/x-icon');
                    setLink('shortcut icon', url, 'image/x-icon');
                    if (appleUrl) {
                        setLink('apple-touch-icon', appleUrl);
                    }
                } catch (e) {
                    console && console.error && console.error('applyFavicon error', e);
                }
            }

            // 1) Apply meta-based override (server-side @push('head') with a meta[name=page-favicon])
            try {
                var meta = document.querySelector('meta[name="page-favicon"]');
                if (meta && meta.getAttribute('content')) {
                    var appleMeta = document.querySelector('meta[name="page-apple-touch-icon"]');
                    applyFavicon(meta.getAttribute('content'), appleMeta ? appleMeta.getAttribute('content') : null);
                }
            } catch (e) {
                console && console.error && console.error('favicon meta read error', e);
            }

            // 2) Listen for runtime updates (useful for Livewire components)
            try {
                window.addEventListener('updateFavicon', function(e) {
                    var url = null;
                    var apple = null;
                    if (e && e.detail) {
                        url = e.detail.url || e.detail[0] || null;
                        apple = e.detail.apple || e.detail.apple_touch || null;
                    }
                    if (url) applyFavicon(url, apple);
                });
            } catch (e) {
                console && console.error && console.error('favicon event listener error', e);
            }
        })();
    </script>
</head>
<!-- END: Head -->

    <body class="main">
    <!-- Page Loader -->
    <!-- <div class="page-loader bg-background fixed inset-0 z-[100] flex items-center justify-center transition-opacity">
        <div class="loader-spinner !w-14"></div>
    </div> -->
        @unless(auth()->guard('students')->check())
            @include('layouts.sidebar')
        @endunless
        
        <!-- Main Content Area -->
        <div class="content transition-[margin,width] duration-200 pt-8 pb-12 px-7 z-10 relative before:absolute before:inset-y-4 before:-ml-px before:right-4 before:opacity-[.07] before:left-4 xl:before:left-0 before:bg-foreground before:rounded-4xl after:absolute after:inset-y-4 after:-ml-px after:right-4 after:left-4 xl:after:left-0 after:bg-[color-mix(in_oklch,_var(--color-background),_var(--color-foreground)_2%)] after:rounded-4xl after:border after:border-foreground/[.15] dark:after:opacity-[.59] {{ auth()->guard('students')->check() ? '' : 'xl:ml-[275px] [&.content--compact]:xl:ml-[110px]' }}">
            <div class="overflow-x-hidden">
                <div class="content__scroll-area relative z-20 pl-4 pr-11 transition-[margin] duration-200 xl:pl-0">
                    <!-- Top Navigation Bar -->
                    @if(auth()->guard('students')->check())
                        @include('layouts.student-navbar')
                    @else
                        @include('layouts.topbar')
                    @endif
                    
                    <!-- Page Content -->
                    <main class="main-content">
                        @yield('content')
                    </main>
                    
                    <!-- Notification Toast Component -->
                    <x-menu.notification-toast />
                    
                    <!-- Auto Show Notifications on Page Load -->
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            // Process expired voting status updates first
                            processVotingStatusUpdates();
                            
                            // Show notifications on page load only once
                            let retryCount = 0;
                            const maxRetries = 5;
                            let notificationsShown = false; // Prevent duplication
                            
                            function processVotingStatusUpdates() {
                                // Process expired voting status updates
                                fetch('/api/voting/process-expired', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                    }
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success && data.processed_count > 0) {
                                        console.log(`Processed ${data.processed_count} expired voting(s) and updated win/loss status`);
                                    }
                                })
                                .catch(error => {
                                    console.error('Error processing voting status updates:', error);
                                });
                            }
                            
                            function tryShowToast() {
                                if (notificationsShown) return; // Already shown
                                
                                if (window.showMenuToast) {
                                    // Fetch notifications from server
                                    fetch('/api/notifications/unread', {
                                        method: 'GET',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                        }
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.success && data.notifications && data.notifications.length > 0 && !notificationsShown) {
                                            notificationsShown = true; // Mark as shown
                                            
                                            // Show only the first notification to avoid duplication
                                            const notification = data.notifications[0];
                                            window.showMenuToast({
                                                type: notification.status === 'approved' ? 'success' : 
                                                      notification.status === 'rejected' ? 'error' : 'info',
                                                title: notification.title || 'Notification',
                                                message: notification.message || 'You have a new notification.',
                                                autoHideMs: 10000
                                            });
                                        }
                                    })
                                    .catch(error => {
                                        console.error('Error fetching notifications:', error);
                                    });
                                    
                                } else if (retryCount < maxRetries) {
                                    retryCount++;
                                    setTimeout(tryShowToast, 500);
                                }
                            }
                            
                            // Start trying to show toast after 2 seconds
                            setTimeout(tryShowToast, 2000);
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
    
    @vite('resources/js/app.js')
    @livewireScripts
    @stack('scripts')
    
    <!-- Initialize Lucide Icons for Rubick Theme -->
    <script>
        // Function to initialize Lucide icons
        function initializeLucideIcons() {
            if (typeof window.createIcons !== 'undefined' && typeof window.icons !== 'undefined') {
                window.createIcons({
                    icons: window.icons,
                    attrs: {
                        "stroke-width": 1.5,
                    },
                    nameAttr: "data-lucide",
                });
                console.log('Lucide icons initialized successfully');
                return true;
            }
            return false;
        }

        // Function to initialize sidebar dropdowns
        function initializeSidebarDropdowns() {
            // Find all menu items with submenus
            const menuItems = document.querySelectorAll('.side-menu__link');
            
            menuItems.forEach(item => {
                const parentLi = item.closest('li');
                const submenu = parentLi.querySelector('ul');
                
                if (submenu && !parentLi.hasAttribute('x-data')) {
                    // Add Alpine.js data if not already present
                    parentLi.setAttribute('x-data', 'sideMenuDropdown({ open: false })');
                    
                    // Add click handler
                    item.addEventListener('click', function(e) {
                        if (this.getAttribute('href') === 'javascript:;') {
                            e.preventDefault();
                            
                            // Toggle submenu
                            const isOpen = submenu.classList.contains('block');
                            if (isOpen) {
                                submenu.classList.remove('block');
                                submenu.classList.add('hidden');
                            } else {
                                submenu.classList.remove('hidden');
                                submenu.classList.add('block');
                            }
                            
                            // Toggle chevron rotation
                            const chevron = this.querySelector('.side-menu__link__chevron');
                            if (chevron) {
                                chevron.style.transform = isOpen ? '' : 'rotate(180deg)';
                            }
                        }
                    });
                }
            });
            
            // Initialize mobile menu toggle
            initializeMobileMenu();
        }
        
        // Function to initialize mobile menu
        function initializeMobileMenu() {
            const mobileMenuToggle = document.querySelector('.open-mobile-menu');
            const sideMenu = document.querySelector('.side-menu');
            const closeMobileMenu = document.querySelector('.close-mobile-menu');
            
            if (mobileMenuToggle && sideMenu) {
                mobileMenuToggle.addEventListener('click', function() {
                    sideMenu.classList.add('side-menu--mobile-menu-open');
                });
            }
            
            if (closeMobileMenu && sideMenu) {
                closeMobileMenu.addEventListener('click', function() {
                    sideMenu.classList.remove('side-menu--mobile-menu-open');
                });
            }
            
            // Close mobile menu when clicking outside
            document.addEventListener('click', function(e) {
                if (sideMenu && sideMenu.classList.contains('side-menu--mobile-menu-open')) {
                    if (!sideMenu.contains(e.target) && !mobileMenuToggle.contains(e.target)) {
                        sideMenu.classList.remove('side-menu--mobile-menu-open');
                    }
                }
            });
        }

        // Wait for Alpine.js to be ready (since it's loaded by Livewire)
        function waitForAlpine() {
            if (typeof window.Alpine !== 'undefined') {
                // Alpine is ready, now try to initialize icons
                if (!initializeLucideIcons()) {
                    // If icons not ready yet, wait a bit more
                    setTimeout(() => {
                        initializeLucideIcons();
                    }, 500);
                }
                
                // Initialize sidebar dropdown functionality
                initializeSidebarDropdowns();
            } else {
                // Alpine not ready yet, wait and try again
                setTimeout(waitForAlpine, 100);
            }
        }

        // Start waiting for Alpine when DOM is ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', waitForAlpine);
        } else {
            waitForAlpine();
        }

        // Also try after a longer delay as fallback
        setTimeout(() => {
            initializeLucideIcons();
            initializeSidebarDropdowns();
        }, 3000);
    </script>
    <!-- END: JS Assets-->
    </body>
</html>