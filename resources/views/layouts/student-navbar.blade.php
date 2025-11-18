<div class="student-topbar relative bg-primary dark:bg-background text-background dark:text-foreground py-3 px-6">
    <div class="max-w-full mx-auto flex items-center gap-4">
        <div class="flex items-center gap-3">
            <button class="open-mobile-menu bg-transparent mr-2 flex size-9 cursor-pointer items-center justify-center rounded-xl border border-foreground/10 text-white xl:hidden" aria-expanded="false" aria-controls="mobile-student-menu">
                <i data-lucide="menu" class="size-5 stroke-[1.5] text-white"></i>
            </button>
            <div class="text-lg font-semibold">Welcome, {{ optional(auth()->guard('students')->user())->first_name ?? 'Student' }}</div>
        </div>

        <div class="ml-auto hidden xl:flex items-center gap-3">
            <a href="{{ route('dashboard') }}" class="text-sm px-3 py-2 rounded hover:bg-gray-100 text-white">Dashboard</a>
            <a href="{{ url('/profile-management') }}" class="text-sm px-3 py-2 rounded hover:bg-gray-100 text-white">My Profile</a>
            <a href="{{ url('/candidacy-management') }}" class="text-sm px-3 py-2 rounded hover:bg-gray-100 text-white">Apply</a>
            <a href="{{ url('/on-going-election') }}" class="text-sm px-3 py-2 rounded hover:bg-gray-100 text-white">Elections</a>
            <a href="{{ url('/display-room') }}" class="text-sm px-3 py-2 rounded hover:bg-gray-100 text-white">Rooms</a>
            <a href="{{ url('/action-center') }}" class="text-sm px-3 py-2 rounded hover:bg-gray-100 text-white">Notifications</a>
            <a href="{{ url('/display-meeting-abanse') }}" class="text-sm px-3 py-2 rounded hover:bg-gray-100 text-white">Meeting de Abanse</a>
            <a href="{{ url('/calendar') }}" class="text-sm px-3 py-2 rounded hover:bg-gray-100 text-white">Calendar</a>
            <a href="{{ route('logout') }}" class="text-sm px-3 py-2 rounded hover:bg-gray-100 text-white">Logout</a>
        </div>

        <!-- Mobile menu (hidden by default, shown when toggled) -->
        <div id="mobile-student-menu" class="xl:hidden hidden absolute left-0 right-0 top-full z-50 bg-background border-t">
            <div class="max-w-full mx-auto px-4 py-3 flex flex-col gap-2">
                <a href="{{ route('dashboard') }}" class="block text-sm px-3 py-2 rounded hover:bg-gray-100 text-foreground">Dashboard</a>
                <a href="{{ url('/profile-management') }}" class="block text-sm px-3 py-2 rounded hover:bg-gray-100 text-foreground">My Profile</a>
                <a href="{{ url('/candidacy-management') }}" class="block text-sm px-3 py-2 rounded hover:bg-gray-100 text-foreground">Apply</a>
                <a href="{{ url('/on-going-election') }}" class="block text-sm px-3 py-2 rounded hover:bg-gray-100 text-foreground">Elections</a>
                <a href="{{ url('/display-room') }}" class="block text-sm px-3 py-2 rounded hover:bg-gray-100 text-foreground">Rooms</a>
                <a href="{{ url('/action-center') }}" class="block text-sm px-3 py-2 rounded hover:bg-gray-100 text-foreground">Notifications</a>
                <a href="{{ url('/display-meeting-abanse') }}" class="block text-sm px-3 py-2 rounded hover:bg-gray-100 text-foreground">Meeting de Abanse</a>
                <a href="{{ url('/calendar') }}" class="block text-sm px-3 py-2 rounded hover:bg-gray-100 text-foreground">Calendar</a>
                <a href="{{ route('logout') }}" class="block text-sm px-3 py-2 rounded hover:bg-gray-100 text-foreground">Logout</a>
            </div>
        </div>
            <script>
                (function(){
                    document.addEventListener('DOMContentLoaded', function(){
                        var btn = document.querySelector('.open-mobile-menu');
                        var mobile = document.getElementById('mobile-student-menu');
                        if (!btn || !mobile) return;
                        btn.addEventListener('click', function(e){
                            e.preventDefault();
                            if (mobile.classList.contains('hidden')) {
                                mobile.classList.remove('hidden');
                            } else {
                                mobile.classList.add('hidden');
                            }
                        });
                    });
                })();
            </script>
    </div>
</div>
