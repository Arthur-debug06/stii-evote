<div class="rubick min-h-screen dark:bg-background before:bg-primary dark:before:bg-foreground/[.01] before:fixed before:inset-0 before:bg-noise after:bg-accent after:bg-contain after:fixed after:inset-0 after:blur-xl dark:after:opacity-20">
        <div class="side-menu text-background dark:text-foreground xl:ml-0 transition-[margin] duration-200 fixed top-0 left-0 z-50 group before:content-[''] before:fixed before:inset-0 before:bg-black/80 dark:before:bg-foreground/5 before:backdrop-blur before:xl:hidden after:content-[''] after:absolute after:inset-0 after:bg-primary after:xl:hidden dark:after:bg-background after:bg-noise [&.side-menu--mobile-menu-open]:ml-0 [&.side-menu--mobile-menu-open]:before:block -ml-[275px] before:hidden">
            <div class="close-mobile-menu fixed ml-[275px] xl:hidden z-50 cursor-pointer [&.close-mobile-menu--mobile-menu-open]:block hidden">
                <div class="ml-5 mt-5 flex size-10 items-center justify-center">
                    <i data-lucide="x" class="[--color:currentColor] stroke-(--color) fill-(--color)/25 size-7 stroke-1"></i>
                </div>
            </div>
            <div class="side-menu__content z-20 pt-5 pb-[7.5rem] relative w-[275px] duration-200 transition-[width] group-[.side-menu--collapsed]:xl:w-[110px] group-[.side-menu--collapsed.side-menu--on-hover]:xl:w-[275px] h-screen flex flex-col">
                <div class="relative z-10 hidden h-[65px] w-[275px] flex-none items-center overflow-hidden px-6 duration-200 xl:flex group-[.side-menu--collapsed.side-menu--on-hover]:xl:w-[275px] group-[.side-menu--collapsed]:xl:w-[110px]">
                    <a class="flex items-center transition-[margin] duration-200 xl:ml-2 group-[.side-menu--collapsed.side-menu--on-hover]:xl:ml-2 group-[.side-menu--collapsed]:xl:ml-6" href="">
                        @php
                        $logoPath = 'http://127.0.0.1:8000/storage/system_settings/1757310054_958f3106-933e-45f6-94d4-16494684712d-modified.png';
                    @endphp

<img class="size-12" src="{{ $logoPath }}" alt="Sidebar Logo">

                        <div class="ml-3.5 text-nowrap transition-opacity group-[.side-menu--collapsed.side-menu--on-hover]:xl:opacity-100 group-[.side-menu--collapsed]:xl:opacity-0">
                            @php
                                $sidebarTextLogo = \App\Models\system_settings::where('key', 'sidebar_text_logo')
                                    ->where('type', 'text')
                                    ->where('module_id', 2)
                                    ->where('status', 'active')
                                    ->first();
                                $textLogo = $sidebarTextLogo ? $sidebarTextLogo->value : 'stii-evote';
                            @endphp
                            <span class="text-base font-medium">{{ $textLogo }}</span>
                        </div>
                    </a>
                    <!-- <a class="toggle-compact-menu border-background/20 bg-background/10 dark:bg-foreground/[.02] dark:border-foreground/[.09] ml-auto hidden items-center justify-center rounded-md border py-0.5 pl-0.5 pr-1 opacity-70 transition-[opacity,transform] hover:opacity-100 group-[.side-menu--collapsed]:xl:rotate-180 group-[.side-menu--collapsed.side-menu--on-hover]:xl:opacity-100 group-[.side-menu--collapsed]:xl:opacity-0 2xl:flex" href="javascript:;" @click="toggleCompactMenu()">
                        <i data-lucide="chevron-left" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25"></i>
                    </a> -->
                </div>
                <div class="w-full h-full z-20 px-4 overflow-y-auto overflow-x-hidden pb-3 [&:-webkit-scrollbar]:w-0 scroll-smooth [&_.simplebar-scrollbar]:before:!bg-background/70 [-webkit-mask-image:_linear-gradient(to_top,_rgba(0,_0,_0,_0),_black_30px),_linear-gradient(to_bottom,_rgba(0,_0,_0,_0),_black_30px)] [-webkit-mask-composite:_destination-in]" x-scroll data-simplebar data-simplebar-auto-hide="true">
                    <ul class="scrollable">
                        
                        <li class="side-menu__group-label">
                            GENERAL REPORTS
                        </li>
                        
                        
                        <li>
                           <x-menu.sidebar-submenu-item href="dashboard" icon="panel-bottom-close" :text="__('Dashboard')" />
                        </li>
                        
                        @php
                            $currentUser = auth()->guard('web')->user() ?? auth()->guard('students')->user();
                            // roles: stsg and selecom should have restricted view for admin management
                            $isSTSG = $currentUser && $currentUser->role === 'stsg';
                            $isSelecom = $currentUser && $currentUser->role === 'selecom';
                            // superadmin is a new role that manages admin accounts and should see all menu items
                            $isSuperadmin = $currentUser && $currentUser->role === 'superadmin';
                        @endphp

                        <!-- Example: Hide Notification Section for STSG -->
                        <li>
                           <x-menu.sidebar-submenu-item href="action-center" icon="panel-bottom-close" :text="auth('students')->check() ? __('Notification') : __('Notification')" />
                        </li>
                        @auth('students')
                        <li>
                           <x-menu.sidebar-submenu-item href="display-room" icon="panel-bottom-close" :text="__('Room Campaign')" />
                        </li>
                        <li>
                           <x-menu.sidebar-submenu-item href="display-meeting-abanse" icon="panel-bottom-close" :text="__('Meeting de Abanse')" />
                        </li>
                        <li>
                           <x-menu.sidebar-submenu-item href="calendar" icon="panel-bottom-close" :text="__('Calendar')" />
                        </li>
                        <li>
                            <x-menu.sidebar-submenu-item href="on-going-election" icon="panel-bottom-close" :text="__('On Going Election')" />
                        </li>
                        @endauth
                        @auth('students')
                        <li x-data="sideMenuDropdown({ open: {{ request()->is('candidacy-management*') ? 'true' : 'false' }} })">
                            <a href="javascript:;" class="side-menu__link" @click="toggle($event)">
                                <i data-lucide="square-kanban" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__icon"></i>
                                <div class="side-menu__link__title">Candidacy</div>
                                <i data-lucide="chevron-down" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__chevron transition" :class="{ 'rotate-180': open }"></i>
                            </a>
                            <ul class="{{ request()->is('candidacy-management*') ? 'block' : 'hidden' }}" :class="{ 'hidden': !open }">
                                <li>
                                    <x-menu.sidebar-submenu-item href="{{ url('/candidacy-management') }}" icon="panel-bottom-close" :text="__('Apply Now')" />
                                </li>
                            </ul>
                        </li>
                        @endauth
                        @auth('web')
                        <!-- Example: Hide Vote Management Section for STSG -->
                        @if(!$isSTSG || $isSuperadmin)
                        
                        <li x-data="sideMenuDropdown({ open: {{ request()->is('voting-exclusive*') || request()->is('on-going-election*') ? 'true' : 'false' }} })">
                            <a href="javascript:;" class="side-menu__link" @click="toggle($event)">
                                <i data-lucide="circle-gauge" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__icon"></i>
                                <div class="side-menu__link__title">Vote Management</div>
                                <i data-lucide="chevron-down" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__chevron transition" :class="{ 'rotate-180': open }"></i>
                            </a>
                            <ul class="{{ request()->is('voting-exclusive*') || request()->is('on-going-election*') ? 'block' : 'hidden' }}" :class="{ 'hidden': !open }">
                                <li>
                                    <x-menu.sidebar-submenu-item href="voting-exclusive" icon="panel-bottom-close" :text="__('Voting Exclusive')" />
                                </li>
                                <li>
                                    <x-menu.sidebar-submenu-item href="on-going-election" icon="panel-bottom-close" :text="__('On Going Election')" />
                                </li>
                            </ul>
                        </li>
                        
                        @endif
                        @endauth
                        @auth('web')
                        <li class="side-menu__group-label">
                            APPS
                        </li>
                        @if((!$isSelecom) || $isSuperadmin)
                       <li x-data="sideMenuDropdown({ open: {{ request()->is('candidacy-management1*') ? 'true' : 'false' }} })">
                            <a href="javascript:;" class="side-menu__link" @click="toggle($event)">
                                <i data-lucide="square-kanban" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__icon"></i>
                                <div class="side-menu__link__title">Candidacy</div>
                                <i data-lucide="chevron-down" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__chevron transition" :class="{ 'rotate-180': open }"></i>
                            </a>
                            <ul class="{{ request()->is('candidacy-management1*') ? 'block' : 'hidden' }}" :class="{ 'hidden': !open }">
                                <li>
                                    <x-menu.sidebar-submenu-item href="{{ url('/candidacy-management1') }}" icon="panel-bottom-close" :text="__('Candidacy List')" />
                                </li>
                            </ul>
                        </li>
                                                @endif

                        {{-- Allow STSG and SELECOM users to access Activity Logs (single link) --}}
                        @if($isSTSG || $isSelecom)
                        <li>
                            <x-menu.sidebar-submenu-item href="activity-logs" icon="panel-bottom-close" :text="__('Activity Logs')" />
                        </li>
                        @endif
                        <li x-data="sideMenuDropdown({ open: {{ request()->is('department-management*') || request()->is('course-management*') || request()->is('schoolyear-semester*') || request()->is('position*') || request()->is('set-signatory*') || request()->is('room-to-room*') || request()->is('meeting-abanse*') ? 'true' : 'false' }} })">
                            <a href="javascript:;" class="side-menu__link" @click="toggle($event)">
                                <i data-lucide="square-kanban" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__icon"></i>
                                <div class="side-menu__link__title">Manage Information</div>
                                <i data-lucide="chevron-down" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__chevron transition" :class="{ 'rotate-180': open }"></i>
                            </a>
                            <ul class="{{ request()->is('department-management*') || request()->is('course-management*') || request()->is('schoolyear-semester*') || request()->is('position*') || request()->is('set-signatory*') || request()->is('room-to-room*') || request()->is('meeting-abanse*') ? 'block' : 'hidden' }}" :class="{ 'hidden': !open }">
                                                        @if(!$isSTSG)
                            <li>
                                    <x-menu.sidebar-submenu-item href="department-management" icon="panel-bottom-close" :text="__('Department')" />
                                </li>
                                <li>
                                    <x-menu.sidebar-submenu-item href="course-management" icon="panel-bottom-close" :text="__('Course')" />
                                </li>
                                <li>
                                    <x-menu.sidebar-submenu-item href="schoolyear-semester" icon="panel-bottom-close" :text="__('SY & Semester')" />
                                </li>
                                <li>
                                    <x-menu.sidebar-submenu-item href="position" icon="panel-bottom-close" :text="__('Position')" />
                                </li>
                                                        @endif
                        @if(!$isSelecom)
                                <li>
                                    <x-menu.sidebar-submenu-item href="set-signatory" icon="panel-bottom-close" :text="__('Set Signatory')" />
                                </li>
                                <li>
                                    <x-menu.sidebar-submenu-item href="room-to-room" icon="panel-bottom-close" :text="__('Room Campaign')" />
                                </li>
                                <li>
                                    <x-menu.sidebar-submenu-item href="meeting-abanse" icon="panel-bottom-close" :text="__('Meeting de Abanse')" />
                                </li>
                                                                                @endif
                            </ul>
                        </li>
                        @if(!$isSelecom)
                        <li x-data="sideMenuDropdown({ open: {{ request()->is('registration-request*') || request()->is('registration-rejected*') || request()->is('registration-approved*') ? 'true' : 'false' }} })">
                            <a href="javascript:;" class="side-menu__link" @click="toggle($event)">
                                <i data-lucide="circle-gauge" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__icon"></i>
                                <div class="side-menu__link__title">Registration Request</div>
                                <i data-lucide="chevron-down" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__chevron transition" :class="{ 'rotate-180': open }"></i>
                            </a>
                            <ul class="{{ request()->is('registration-request*') || request()->is('registration-rejected*') || request()->is('registration-approved*') ? 'block' : 'hidden' }}" :class="{ 'hidden': !open }">
                                <li>
                                    <x-menu.sidebar-submenu-item href="registration-request" icon="panel-bottom-close" :text="__('Student Request')" />
                                </li>
                                <li>
                                    <x-menu.sidebar-submenu-item href="registration-rejected" icon="panel-bottom-close" :text="__('Student Rejected')" />
                                </li>
                                <li>
                                    <x-menu.sidebar-submenu-item href="registration-approved" icon="panel-bottom-close" :text="__('Student Approved')" />
                                </li>
                            </ul>
                        </li>
                                                                        @endif
                                                @if($isSuperadmin)
                        <li x-data="sideMenuDropdown({ open: {{ request()->is('admin-account*') || request()->is('student-account*') || request()->is('activity-logs*') ? 'true' : 'false' }} })">
                            <a href="javascript:;" class="side-menu__link" @click="toggle($event)">
                                <i data-lucide="circle-gauge" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__icon"></i>
                                <div class="side-menu__link__title">User Management</div>
                                <i data-lucide="chevron-down" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__chevron transition" :class="{ 'rotate-180': open }"></i>
                            </a>
                            <ul class="{{ request()->is('admin-account*') || request()->is('student-account*') || request()->is('activity-logs*') ? 'block' : 'hidden' }}" :class="{ 'hidden': !open }">
                                <li>
                                    <x-menu.sidebar-submenu-item href="admin-account" icon="panel-bottom-close" :text="__('Admin Account')" />
                                </li>
                                <li>
                                    <x-menu.sidebar-submenu-item href="student-account" icon="panel-bottom-close" :text="__('Student Account')" />
                                </li>
                                <li>
                                    <x-menu.sidebar-submenu-item href="activity-logs" icon="panel-bottom-close" :text="__('Activity Logs')" />
                                </li>
                            </ul>
                        </li>
                                                @endif

                        <li x-data="sideMenuDropdown({ open: {{ request()->is('candidates-position*') || request()->is('candidates-election*') || request()->is('list-students-account*') || request()->is('list-admin-account*') ? 'true' : 'false' }} })">
                            <a href="javascript:;" class="side-menu__link" @click="toggle($event)">
                                <i data-lucide="circle-gauge" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__icon"></i>
                                <div class="side-menu__link__title">Generate Report</div>
                                <i data-lucide="chevron-down" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__chevron transition" :class="{ 'rotate-180': open }"></i>
                            </a>
                            <ul class="{{ request()->is('candidates-position*') || request()->is('candidates-election*') || request()->is('list-students-account*') || request()->is('list-admin-account*') ? 'block' : 'hidden' }}" :class="{ 'hidden': !open }">
                                                        @if(!$isSTSG)
                        @if(!$isSelecom)
                            <li>
                                    <x-menu.sidebar-submenu-item href="candidates-position" icon="panel-bottom-close" :text="__('Candidates Position')" />
                                </li>
                                                        @endif
                                                                                                        @endif
                                <li>
                                    <x-menu.sidebar-submenu-item href="candidates-election" icon="panel-bottom-close" :text="__('Candidates Election')" />
                                </li>
                                <li>
                                    <x-menu.sidebar-submenu-item href="student-voter" icon="users" :text="__('Student Voter')" />
                                </li>
                                <li>
                                    <x-menu.sidebar-submenu-item href="voting-histories" icon="clock" :text="__('Voting History')" />
                                </li>
                        @if(!$isSTSG)
                        @if(!$isSelecom)
                               
                                                        @endif
                                                                                                        @endif
                            </ul>
                        </li>
                        
                        <li x-data="sideMenuDropdown({ open: {{ request()->is('partylist-management*') ? 'true' : 'false' }} })">
                            <a href="javascript:;" class="side-menu__link" @click="toggle($event)">
                                <i data-lucide="circle-gauge" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__icon"></i>
                                <div class="side-menu__link__title">Partylist Management</div>
                                <i data-lucide="chevron-down" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__chevron transition" :class="{ 'rotate-180': open }"></i>
                            </a>
                            <ul class="{{ request()->is('partylist-management*') ? 'block' : 'hidden' }}" :class="{ 'hidden': !open }">
                                <li>
                                    <x-menu.sidebar-submenu-item href="partylist-management" icon="panel-bottom-close" :text="__('Partylist Management')" />
                                </li>
                            </ul>
                        </li>
                        

                        <!-- <li>
                           <x-menu.sidebar-submenu-item href="system-settings" icon="panel-bottom-close" :text="__('System Settings')" />
                        </li> -->
                        @endauth
                        
                        <!-- <li>
                            <a href="rubick-side-menu-post.html" class="side-menu__link ">
                                <i data-lucide="circle-gauge" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__icon"></i>
                                <div class="side-menu__link__title">Post</div>
                            </a>
                            
                        </li> -->
                        
                        
                     
                        
                        <!-- <li class="side-menu__group-label">
                            PAGES
                        </li> -->
                        
                        
                        <!-- <li>
                            <a href="javascript:;" class="side-menu__link ">
                                <i data-lucide="circle-gauge" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__icon"></i>
                                <div class="side-menu__link__title">Crud</div>
                                <i data-lucide="chevron-down" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__chevron transition"></i>
                            </a>
                            <ul class="hidden">
                                <li>
                                    <a href="rubick-side-menu-crud-data-list.html" class="side-menu__link ">
                                        <i data-lucide="circle-gauge" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__icon"></i>
                                        <div class="side-menu__link__title">
                                            Data List
                                        </div>
                                    </a>
                           
                                </li>
                                <li>
                                    <a href="rubick-side-menu-crud-form.html" class="side-menu__link ">
                                        <i data-lucide="circle-gauge" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__icon"></i>
                                        <div class="side-menu__link__title">
                                            Form
                                        </div>
                                    </a>
                                   
                                </li>
                            </ul>
                        </li> -->
                      
                        <!-- <li>
                            <a href="javascript:;" class="side-menu__link ">
                                <i data-lucide="circle-gauge" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__icon"></i>
                                <div class="side-menu__link__title">Users</div>
                                <i data-lucide="chevron-down" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__chevron transition"></i>
                            </a>
                            <ul class="hidden">
                                <li>
                                    <a href="rubick-side-menu-users-layout-1.html" class="side-menu__link ">
                                        <i data-lucide="circle-gauge" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__icon"></i>
                                        <div class="side-menu__link__title">
                                            Layout 1
                                        </div>
                                    </a>
                                 
                                </li>
                                <li>
                                    <a href="rubick-side-menu-users-layout-2.html" class="side-menu__link ">
                                        <i data-lucide="circle-gauge" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__icon"></i>
                                        <div class="side-menu__link__title">
                                            Layout 2
                                        </div>
                                    </a>
                                  
                                </li>
                                <li>
                                    <a href="rubick-side-menu-users-layout-3.html" class="side-menu__link ">
                                        <i data-lucide="circle-gauge" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__icon"></i>
                                        <div class="side-menu__link__title">
                                            Layout 3
                                        </div>
                                    </a>
                                  
                                </li>
                            </ul>
                        </li>
                       
                        <li>
                            <a href="javascript:;" class="side-menu__link ">
                                <i data-lucide="circle-gauge" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__icon"></i>
                                <div class="side-menu__link__title">Profile</div>
                                <i data-lucide="chevron-down" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__chevron transition"></i>
                            </a>
                            <ul class="hidden">
                                <li>
                                    <a href="rubick-side-menu-profile-overview-1.html" class="side-menu__link ">
                                        <i data-lucide="circle-gauge" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__icon"></i>
                                        <div class="side-menu__link__title">
                                            Overview 1
                                        </div>
                                    </a>
                                 
                                </li>
                                <li>
                                    <a href="rubick-side-menu-profile-overview-2.html" class="side-menu__link ">
                                        <i data-lucide="circle-gauge" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__icon"></i>
                                        <div class="side-menu__link__title">
                                            Overview 2
                                        </div>
                                    </a>
                                   
                                </li>
                                <li>
                                    <a href="rubick-side-menu-profile-overview-3.html" class="side-menu__link ">
                                        <i data-lucide="circle-gauge" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__icon"></i>
                                        <div class="side-menu__link__title">
                                            Overview 3
                                        </div>
                                    </a>
                                  
                                </li>
                            </ul>
                        </li>
                       
                        <li>
                            <a href="javascript:;" class="side-menu__link ">
                                <i data-lucide="circle-gauge" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__icon"></i>
                                <div class="side-menu__link__title">Pages</div>
                                <i data-lucide="chevron-down" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__chevron transition"></i>
                            </a>
                            <ul class="hidden">
                                <li>
                                    <a href="javascript:;" class="side-menu__link ">
                                        <i data-lucide="circle-gauge" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__icon"></i>
                                        <div class="side-menu__link__title">
                                            Wizards
                                        </div>
                                        <i data-lucide="chevron-down" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__chevron transition"></i>
                                    </a>
                                    <ul class="hidden">
                                        <li>
                                            <a href="rubick-side-menu-wizard-layout-1.html" class="side-menu__link ">
                                                <i data-lucide="circle-gauge" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__icon"></i>
                                                <div class="side-menu__link__title">
                                                    Layout 1
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="rubick-side-menu-wizard-layout-2.html" class="side-menu__link ">
                                                <i data-lucide="circle-gauge" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__icon"></i>
                                                <div class="side-menu__link__title">
                                                    Layout 2
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="rubick-side-menu-wizard-layout-3.html" class="side-menu__link ">
                                                <i data-lucide="circle-gauge" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__icon"></i>
                                                <div class="side-menu__link__title">
                                                    Layout 3
                                                </div>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="javascript:;" class="side-menu__link ">
                                        <i data-lucide="circle-gauge" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__icon"></i>
                                        <div class="side-menu__link__title">
                                            Blog
                                        </div>
                                        <i data-lucide="chevron-down" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__chevron transition"></i>
                                    </a>
                                    <ul class="hidden">
                                        <li>
                                            <a href="rubick-side-menu-blog-layout-1.html" class="side-menu__link ">
                                                <i data-lucide="circle-gauge" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__icon"></i>
                                                <div class="side-menu__link__title">
                                                    Layout 1
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="rubick-side-menu-blog-layout-2.html" class="side-menu__link ">
                                                <i data-lucide="circle-gauge" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__icon"></i>
                                                <div class="side-menu__link__title">
                                                    Layout 2
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="rubick-side-menu-blog-layout-3.html" class="side-menu__link ">
                                                <i data-lucide="circle-gauge" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__icon"></i>
                                                <div class="side-menu__link__title">
                                                    Layout 3
                                                </div>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="javascript:;" class="side-menu__link ">
                                        <i data-lucide="circle-gauge" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__icon"></i>
                                        <div class="side-menu__link__title">
                                            Pricing
                                        </div>
                                        <i data-lucide="chevron-down" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__chevron transition"></i>
                                    </a>
                                    <ul class="hidden">
                                        <li>
                                            <a href="rubick-side-menu-pricing-layout-1.html" class="side-menu__link ">
                                                <i data-lucide="circle-gauge" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__icon"></i>
                                                <div class="side-menu__link__title">
                                                    Layout 1
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="rubick-side-menu-pricing-layout-2.html" class="side-menu__link ">
                                                <i data-lucide="circle-gauge" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__icon"></i>
                                                <div class="side-menu__link__title">
                                                    Layout 2
                                                </div>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="javascript:;" class="side-menu__link ">
                                        <i data-lucide="circle-gauge" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__icon"></i>
                                        <div class="side-menu__link__title">
                                            Invoice
                                        </div>
                                        <i data-lucide="chevron-down" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__chevron transition"></i>
                                    </a>
                                    <ul class="hidden">
                                        <li>
                                            <a href="rubick-side-menu-invoice-layout-1.html" class="side-menu__link ">
                                                <i data-lucide="circle-gauge" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__icon"></i>
                                                <div class="side-menu__link__title">
                                                    Layout 1
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="rubick-side-menu-invoice-layout-2.html" class="side-menu__link ">
                                                <i data-lucide="circle-gauge" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__icon"></i>
                                                <div class="side-menu__link__title">
                                                    Layout 2
                                                </div>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="javascript:;" class="side-menu__link ">
                                        <i data-lucide="circle-gauge" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__icon"></i>
                                        <div class="side-menu__link__title">
                                            FAQ
                                        </div>
                                        <i data-lucide="chevron-down" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__chevron transition"></i>
                                    </a>
                                    <ul class="hidden">
                                        <li>
                                            <a href="rubick-side-menu-faq-layout-1.html" class="side-menu__link ">
                                                <i data-lucide="circle-gauge" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__icon"></i>
                                                <div class="side-menu__link__title">
                                                    Layout 1
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="rubick-side-menu-faq-layout-2.html" class="side-menu__link ">
                                                <i data-lucide="circle-gauge" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__icon"></i>
                                                <div class="side-menu__link__title">
                                                    Layout 2
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="rubick-side-menu-faq-layout-3.html" class="side-menu__link ">
                                                <i data-lucide="circle-gauge" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__icon"></i>
                                                <div class="side-menu__link__title">
                                                    Layout 3
                                                </div>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="rubick-side-menu-login.html" class="side-menu__link ">
                                        <i data-lucide="circle-gauge" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__icon"></i>
                                        <div class="side-menu__link__title">
                                            Login
                                        </div>
                                    </a>
                                
                                </li>
                                <li>
                                    <a href="rubick-side-menu-register.html" class="side-menu__link ">
                                        <i data-lucide="circle-gauge" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__icon"></i>
                                        <div class="side-menu__link__title">
                                            Register
                                        </div>
                                    </a>
                                 
                                </li>
                                <li>
                                    <a href="rubick-side-menu-error-page.html" class="side-menu__link ">
                                        <i data-lucide="circle-gauge" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__icon"></i>
                                        <div class="side-menu__link__title">
                                            Error Page
                                        </div>
                                    </a>
                                   
                                </li>
                                <li>
                                    <a href="rubick-side-menu-update-profile.html" class="side-menu__link ">
                                        <i data-lucide="circle-gauge" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__icon"></i>
                                        <div class="side-menu__link__title">
                                            Update profile
                                        </div>
                                    </a>
                                 
                                </li>
                                <li>
                                    <a href="rubick-side-menu-change-password.html" class="side-menu__link ">
                                        <i data-lucide="circle-gauge" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__icon"></i>
                                        <div class="side-menu__link__title">
                                            Change Password
                                        </div>
                                    </a>
                                   
                                </li>
                            </ul>
                        </li> -->
                      
                        <!-- <li class="side-menu__group-label">
                            UI COMPONENTS
                        </li>
                        
                        <li>
                            <a href="javascript:;" class="side-menu__link ">
                                <i data-lucide="circle-gauge" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__icon"></i>
                                <div class="side-menu__link__title">Components</div>
                                <i data-lucide="chevron-down" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__chevron transition"></i>
                            </a>
                            <ul class="hidden">
                                <li>
                                    <a href="javascript:;" class="side-menu__link ">
                                        <i data-lucide="circle-gauge" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__icon"></i>
                                        <div class="side-menu__link__title">
                                            Grid
                                        </div>
                                        <i data-lucide="chevron-down" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__chevron transition"></i>
                                    </a>
                                    <ul class="hidden">
                                        <li>
                                            <a href="rubick-side-menu-regular-table.html" class="side-menu__link ">
                                                <i data-lucide="circle-gauge" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__icon"></i>
                                                <div class="side-menu__link__title">
                                                    Regular Table
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="rubick-side-menu-tabulator.html" class="side-menu__link ">
                                                <i data-lucide="circle-gauge" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__icon"></i>
                                                <div class="side-menu__link__title">
                                                    Tabulator
                                                </div>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="javascript:;" class="side-menu__link ">
                                        <i data-lucide="circle-gauge" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__icon"></i>
                                        <div class="side-menu__link__title">
                                            Overlay
                                        </div>
                                        <i data-lucide="chevron-down" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__chevron transition"></i>
                                    </a>
                                    <ul class="hidden">
                                        <li>
                                            <a href="rubick-side-menu-dialog.html" class="side-menu__link ">
                                                <i data-lucide="circle-gauge" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__icon"></i>
                                                <div class="side-menu__link__title">
                                                    Dialog
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="rubick-side-menu-sheet.html" class="side-menu__link ">
                                                <i data-lucide="circle-gauge" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__icon"></i>
                                                <div class="side-menu__link__title">
                                                    Sheet
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="rubick-side-menu-toast.html" class="side-menu__link ">
                                                <i data-lucide="circle-gauge" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__icon"></i>
                                                <div class="side-menu__link__title">
                                                    Toast
                                                </div>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="rubick-side-menu-tabs.html" class="side-menu__link ">
                                        <i data-lucide="circle-gauge" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__icon"></i>
                                        <div class="side-menu__link__title">
                                            Tabs
                                        </div>
                                    </a>
                                   
                                </li>
                                <li>
                                    <a href="rubick-side-menu-accordion.html" class="side-menu__link ">
                                        <i data-lucide="circle-gauge" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__icon"></i>
                                        <div class="side-menu__link__title">
                                            Accordion
                                        </div>
                                    </a>
                                   
                                </li>
                                <li>
                                    <a href="rubick-side-menu-button.html" class="side-menu__link ">
                                        <i data-lucide="circle-gauge" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__icon"></i>
                                        <div class="side-menu__link__title">
                                            Button
                                        </div>
                                    </a>
                                  
                                </li>
                                <li>
                                    <a href="rubick-side-menu-alert.html" class="side-menu__link ">
                                        <i data-lucide="circle-gauge" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__icon"></i>
                                        <div class="side-menu__link__title">
                                            Alert
                                        </div>
                                    </a>
                             
                                </li>
                                <li>
                                    <a href="rubick-side-menu-progress.html" class="side-menu__link ">
                                        <i data-lucide="circle-gauge" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__icon"></i>
                                        <div class="side-menu__link__title">
                                            Progress
                                        </div>
                                    </a>
                                 
                                </li>
                                <li>
                                    <a href="rubick-side-menu-tooltip.html" class="side-menu__link ">
                                        <i data-lucide="circle-gauge" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__icon"></i>
                                        <div class="side-menu__link__title">
                                            Tooltip
                                        </div>
                                    </a>
                                 
                                </li>
                                <li>
                                    <a href="rubick-side-menu-dropdown-menu.html" class="side-menu__link ">
                                        <i data-lucide="circle-gauge" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__icon"></i>
                                        <div class="side-menu__link__title">
                                            Dropdown Menu
                                        </div>
                                    </a>
                                  
                                </li>
                                <li>
                                    <a href="rubick-side-menu-typography.html" class="side-menu__link ">
                                        <i data-lucide="circle-gauge" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__icon"></i>
                                        <div class="side-menu__link__title">
                                            Typography
                                        </div>
                                    </a>
                                   
                                </li>
                                <li>
                                    <a href="rubick-side-menu-icon.html" class="side-menu__link ">
                                        <i data-lucide="circle-gauge" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__icon"></i>
                                        <div class="side-menu__link__title">
                                            Icon
                                        </div>
                                    </a>
                                    
                                </li>
                            </ul>
                        </li>
                        
                        <li>
                            <a href="javascript:;" class="side-menu__link ">
                                <i data-lucide="circle-gauge" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__icon"></i>
                                <div class="side-menu__link__title">Forms</div>
                                <i data-lucide="chevron-down" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__chevron transition"></i>
                            </a>
                            <ul class="hidden">
                                <li>
                                    <a href="rubick-side-menu-regular-form.html" class="side-menu__link ">
                                        <i data-lucide="circle-gauge" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__icon"></i>
                                        <div class="side-menu__link__title">
                                            Regular Form
                                        </div>
                                    </a>
                               
                                </li>
                                <li>
                                    <a href="rubick-side-menu-easepick.html" class="side-menu__link ">
                                        <i data-lucide="circle-gauge" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__icon"></i>
                                        <div class="side-menu__link__title">
                                            Easepick
                                        </div>
                                    </a>
                               
                                </li>
                                <li>
                                    <a href="rubick-side-menu-tom-select.html" class="side-menu__link ">
                                        <i data-lucide="circle-gauge" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__icon"></i>
                                        <div class="side-menu__link__title">
                                            Tom Select
                                        </div>
                                    </a>
                                    
                                </li>
                                <li>
                                    <a href="rubick-side-menu-dropzone.html" class="side-menu__link ">
                                        <i data-lucide="circle-gauge" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__icon"></i>
                                        <div class="side-menu__link__title">
                                            Dropzone
                                        </div>
                                    </a>
                                    
                                </li>
                                <li>
                                    <a href="javascript:;" class="side-menu__link ">
                                        <i data-lucide="circle-gauge" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__icon"></i>
                                        <div class="side-menu__link__title">
                                            Wysiwyg Editor
                                        </div>
                                        <i data-lucide="chevron-down" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__chevron transition"></i>
                                    </a>
                                    
                                    <ul class="hidden">
                                        <li>
                                            <a href="rubick-side-menu-ckeditor-classic.html" class="side-menu__link ">
                                                <i data-lucide="circle-gauge" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__icon"></i>
                                                <div class="side-menu__link__title">
                                                    CKEditor Classic
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="rubick-side-menu-ckeditor-inline.html" class="side-menu__link ">
                                                <i data-lucide="circle-gauge" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__icon"></i>
                                                <div class="side-menu__link__title">
                                                    CKEditor Inline
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="rubick-side-menu-ckeditor-balloon.html" class="side-menu__link ">
                                                <i data-lucide="circle-gauge" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__icon"></i>
                                                <div class="side-menu__link__title">
                                                    CKEditor Balloon
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="rubick-side-menu-ckeditor-balloon-block.html" class="side-menu__link ">
                                                <i data-lucide="circle-gauge" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__icon"></i>
                                                <div class="side-menu__link__title">
                                                    CKEditor Balloon Block
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="rubick-side-menu-ckeditor-document.html" class="side-menu__link ">
                                                <i data-lucide="circle-gauge" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__icon"></i>
                                                <div class="side-menu__link__title">
                                                    CKEditor Document
                                                </div>
                                            </a>
                                        </li>
                                    </ul>
                                    
                                </li>
                                <li>
                                    <a href="rubick-side-menu-form-validation.html" class="side-menu__link ">
                                        <i data-lucide="circle-gauge" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__icon"></i>
                                        <div class="side-menu__link__title">
                                            Form Validation
                                        </div>
                                    </a>
                                    
                                </li>
                            </ul>
                        </li>
                      
                        <li>
                            <a href="javascript:;" class="side-menu__link ">
                                <i data-lucide="circle-gauge" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__icon"></i>
                                <div class="side-menu__link__title">Widgets</div>
                                <i data-lucide="chevron-down" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__chevron transition"></i>
                            </a>
                            
                            <ul class="hidden">
                                <li>
                                    <a href="rubick-side-menu-chart.html" class="side-menu__link ">
                                        <i data-lucide="circle-gauge" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__icon"></i>
                                        <div class="side-menu__link__title">
                                            Chart
                                        </div>
                                    </a>
                                    
                                </li>
                                <li>
                                    <a href="rubick-side-menu-slider.html" class="side-menu__link ">
                                        <i data-lucide="circle-gauge" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__icon"></i>
                                        <div class="side-menu__link__title">
                                            Slider
                                        </div>
                                    </a>
                                    
                                </li>
                                <li>
                                    <a href="rubick-side-menu-image-zoom.html" class="side-menu__link ">
                                        <i data-lucide="circle-gauge" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__icon"></i>
                                        <div class="side-menu__link__title">
                                            Image Zoom
                                        </div>
                                    </a>
                                    
                                </li>
                            </ul>
                        </li> -->
                        
                    </ul>
                </div>
                <div class="side-menu__account group/profile absolute inset-x-0 bottom-0 mx-4 mb-8 transition-[width] group-[.side-menu--collapsed.side-menu--on-hover]:block group-[.side-menu--collapsed]:justify-center xl:group-[.side-menu--collapsed]:flex">
                    <div class="bg-background/10 border-background/20 dark:bg-foreground/[.02] dark:border-foreground/[.09] flex cursor-pointer items-center rounded-full border p-2.5 opacity-80 backdrop-blur-2xl transition hover:opacity-100" onclick="toggleProfileDropdown()">
                        <div class="border-background/20 dark:border-foreground/20 relative h-10 w-10 flex-none overflow-hidden rounded-full border-4">
                            @php
                                $currentUser = auth()->guard('web')->user() ?? auth()->guard('students')->user();
                            @endphp
                            @if($currentUser)
                                @if($currentUser->profile_image)
                                    <img class="absolute top-0 h-full w-full object-cover" src="{{ asset('storage/' . $currentUser->profile_image) }}" alt="User Profile">
                                @else
                                    <div class="absolute top-0 h-full w-full flex items-center justify-center bg-gray-200 text-gray-600 text-sm font-bold">
                                        @if($currentUser instanceof \App\Models\students)
                                            {{ strtoupper(substr($currentUser->first_name ?? 'S', 0, 1)) }}
                                        @else
                                            {{ strtoupper(substr($currentUser->name ?? 'U', 0, 1)) }}
                                        @endif
                                    </div>
                                @endif
                            @else
                                <img class="absolute top-0 h-full w-full object-cover" src="dist/images/fakers/profile-11.jpg" alt="Guest User">
                            @endif
                        </div>
                        <div class="ms-3 flex w-full items-center overflow-hidden transition-opacity group-[.side-menu--collapsed.side-menu--on-hover]:ms-3 group-[.side-menu--collapsed.side-menu--on-hover]:w-full group-[.side-menu--collapsed.side-menu--on-hover]:opacity-100 xl:group-[.side-menu--collapsed]:ms-0 xl:group-[.side-menu--collapsed]:w-0 xl:group-[.side-menu--collapsed]:opacity-0">
                            <div class="w-28">
                                <div class="w-full truncate font-medium">
                                    @if($currentUser)
                                        @if($currentUser instanceof \App\Models\students)
                                            {{ $currentUser->first_name ?? '' }} {{ $currentUser->last_name ?? '' }}
                                        @else
                                            {{ $currentUser->name ?? 'User' }}
                                        @endif
                                    @else
                                        Guest
                                    @endif
                                </div>
                                <div class="w-full truncate text-xs opacity-60">
                                    @if($currentUser)
                                        @if($currentUser instanceof \App\Models\students)
                                            Student
                                        @else
                                            {{ ucfirst($currentUser->role ?? 'Administrator') }}
                                        @endif
                                    @else
                                        Guest
                                    @endif
                                </div>
                            </div>
                            <i data-lucide="move-right" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 me-4 ms-auto opacity-50"></i>
                        </div>
                    </div>
                    <div class="hidden" id="profileDropdown">
                        <div class="box p-5 before:absolute before:inset-0 before:mx-3 before:-mb-3 before:border before:border-foreground/10 before:bg-background/30 before:z-[-1] after:absolute after:inset-0 after:border after:border-foreground/10 after:bg-background after:shadow-[0px_3px_5px_#0000000b] after:z-[-1] after:backdrop-blur-md text-foreground before:shadow-foreground/5 absolute bottom-0 left-[100%] z-50 ml-2 flex w-64 flex-col gap-2.5 px-6 py-5 before:rounded-2xl before:shadow-xl before:backdrop-blur after:rounded-2xl">
                            <div class="flex flex-col gap-0.5">
                                <div class="font-medium">
                                    @if($currentUser)
                                        @if($currentUser instanceof \App\Models\students)
                                            {{ $currentUser->first_name ?? '' }} {{ $currentUser->last_name ?? '' }}
                                        @else
                                            {{ $currentUser->name ?? 'User' }}
                                        @endif
                                    @else
                                        Guest
                                    @endif
                                </div>
                                <div class="mt-0.5 text-xs opacity-70">
                                    @if($currentUser)
                                        @if($currentUser instanceof \App\Models\students)
                                            Student
                                        @else
                                            {{ ucfirst($currentUser->role ?? 'Administrator') }}
                                        @endif
                                    @else
                                        Guest User
                                    @endif
                                </div>
                            </div>
                            <div class="bg-foreground/5 h-px"></div>
                            <div class="flex flex-col gap-0.5">
                                <a class="hover:bg-foreground/5 -mx-3 flex gap-2.5 rounded-lg px-4 py-1.5" href="profile-management">
                                    <i data-lucide="users" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25"></i>
                                    Profile
                                </a>
                                <!-- <a class="hover:bg-foreground/5 -mx-3 flex gap-2.5 rounded-lg px-4 py-1.5" href="">
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
                                </a> -->
                            </div>
                            <div class="bg-foreground/5 h-px"></div>
                            <div class="flex flex-col gap-0.5">
                                <a class="hover:bg-foreground/5 -mx-3 flex gap-2.5 rounded-lg px-4 py-1.5" href="logout">
                                    <i data-lucide="power" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25"></i>
                                    Logout
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function toggleProfileDropdown() {
                const dropdown = document.getElementById('profileDropdown');
                if (dropdown.classList.contains('hidden')) {
                    dropdown.classList.remove('hidden');
                    dropdown.classList.add('block');
                } else {
                    dropdown.classList.remove('block');
                    dropdown.classList.add('hidden');
                }
            }

            // Close dropdown when clicking outside
            document.addEventListener('click', function(event) {
                const profileAccount = document.querySelector('.side-menu__account');
                const dropdown = document.getElementById('profileDropdown');
                
                if (!profileAccount.contains(event.target)) {
                    dropdown.classList.remove('block');
                    dropdown.classList.add('hidden');
                }
            });
        </script>