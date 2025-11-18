<div>
    <x-menu.modal size="md" :showButton="false" modalId="refresh-modal" title="Refreshing...">
    </x-menu.modal>
    <div class="grid grid-cols-12 gap-8">
        <div class="col-span-12 2xl:col-span-9">
                @guest('students')
                <!-- BEGIN: General Report -->
                <div class="col-span-12 mt-8">
                    <div class="flex h-10 items-center">
                        <h2 class="me-5 truncate text-lg font-medium">General Report</h2>
                        <a id="open-modal" class="text-primary ms-auto flex items-center gap-3" href="#">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="refresh-ccw" class="lucide lucide-refresh-ccw size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25"><path d="M21 12a9 9 0 0 0-9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path><path d="M3 3v5h5"></path><path d="M3 12a9 9 0 0 0 9 9 9.75 9.75 0 0 0 6.74-2.74L21 16"></path><path d="M16 16h5v5"></path></svg>
                            Refresh
                        </a>
                    </div>
                    <div class="mt-5 grid grid-cols-12 gap-6">
                        <div class="col-span-12 sm:col-span-6 xl:col-span-3">
                            <div class="box relative p-5 before:absolute before:inset-0 before:mx-3 before:-mb-3 before:border before:border-foreground/10 before:bg-background/30 before:shadow-[0px_3px_5px_#0000000b] before:z-[-1] before:rounded-xl after:absolute after:inset-0 after:border after:border-foreground/10 after:bg-background after:shadow-[0px_3px_5px_#0000000b] after:rounded-xl after:z-[-1] after:backdrop-blur-md cursor-pointer hover:shadow-lg transition-shadow" wire:click="showCardModal('total_votes')">
                                <div class="flex">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="circle-gauge" class="lucide lucide-circle-gauge size-4 stroke-(--color) fill-(--color)/25 h-7 w-7 stroke-1 drop-shadow [--color:var(--color-primary)]"><path d="M15.6 2.7a10 10 0 1 0 5.7 5.7"></path><circle cx="12" cy="12" r="2"></circle><path d="M13.4 10.6 19 5"></path></svg>
                                    <div class="ms-auto">
                                        <div class="bg-(--color)/20 border-(--color)/60 text-(--color) flex cursor-pointer items-center rounded-full border px-2 py-px text-xs tooltip pl-2 pr-1 [--color:var(--color-success)]" data-content="12% Higher than last month">
                                            12%
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="chevron-up" class="lucide lucide-chevron-up size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 ms-0.5"><path d="m18 15-6-6-6 6"></path></svg>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-6 text-2xl font-medium leading-8">{{ number_format($stats['total_votes']) }}</div>
                                <div class="mt-1.5 text-xs uppercase opacity-70">Total Votes Cast</div>
                            </div>
                        </div>
                        <div class="col-span-12 sm:col-span-6 xl:col-span-3">
                            <div class="box relative p-5 before:absolute before:inset-0 before:mx-3 before:-mb-3 before:border before:border-foreground/10 before:bg-background/30 before:shadow-[0px_3px_5px_#0000000b] before:z-[-1] before:rounded-xl after:absolute after:inset-0 after:border after:border-foreground/10 after:bg-background after:shadow-[0px_3px_5px_#0000000b] after:rounded-xl after:z-[-1] after:backdrop-blur-md cursor-pointer hover:shadow-lg transition-shadow" wire:click="showCardModal('total_candidates')">
                                <div class="flex">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="panel-bottom-close" class="lucide lucide-panel-bottom-close size-4 stroke-(--color) fill-(--color)/25 h-7 w-7 stroke-1 [--color:var(--color-pending)]"><rect width="18" height="18" x="3" y="3" rx="2"></rect><path d="M3 15h18"></path><path d="m15 8-3 3-3-3"></path></svg>
                                    <div class="ms-auto">
                                        <div class="bg-(--color)/20 border-(--color)/60 text-(--color) flex cursor-pointer items-center rounded-full border px-2 py-px text-xs tooltip pl-2 pr-1 [--color:var(--color-success)]" data-content="9% Higher than last month">
                                            9%
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="chevron-up" class="lucide lucide-chevron-up size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 ms-0.5"><path d="m18 15-6-6-6 6"></path></svg>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-6 text-2xl font-medium leading-8">{{ number_format($stats['total_candidates']) }}</div>
                                <div class="mt-1.5 text-xs uppercase opacity-70">Total Candidates</div>
                            </div>
                        </div>
                        <div class="col-span-12 sm:col-span-6 xl:col-span-3">
                            <div class="box relative p-5 before:absolute before:inset-0 before:mx-3 before:-mb-3 before:border before:border-foreground/10 before:bg-background/30 before:shadow-[0px_3px_5px_#0000000b] before:z-[-1] before:rounded-xl after:absolute after:inset-0 after:border after:border-foreground/10 after:bg-background after:shadow-[0px_3px_5px_#0000000b] after:rounded-xl after:z-[-1] after:backdrop-blur-md cursor-pointer hover:shadow-lg transition-shadow" wire:click="showCardModal('winners')">
                                <div class="flex">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="disc3" class="lucide lucide-disc3 size-4 stroke-(--color) fill-(--color)/25 h-7 w-7 stroke-1 [--color:var(--color-warning)]"><circle cx="12" cy="12" r="10"></circle><path d="M6 12c0-1.7.7-3.2 1.8-4.2"></path><circle cx="12" cy="12" r="2"></circle><path d="M18 12c0 1.7-.7 3.2-1.8 4.2"></path></svg>
                                    <div class="ms-auto">
                                        <div class="bg-(--color)/20 border-(--color)/60 text-(--color) flex cursor-pointer items-center rounded-full border px-2 py-px text-xs tooltip pl-2 pr-1 [--color:var(--color-danger)]" data-content="7% Lower than last month">
                                            7%
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="chevron-down" class="lucide lucide-chevron-down size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 ms-0.5"><path d="m6 9 6 6 6-6"></path></svg>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-6 text-2xl font-medium leading-8">{{ number_format($stats['winners']) }}</div>
                                <div class="mt-1.5 text-xs uppercase opacity-70">Election Winners</div>
                            </div>
                        </div>
                        <div class="col-span-12 sm:col-span-6 xl:col-span-3">
                            <div class="box relative p-5 before:absolute before:inset-0 before:mx-3 before:-mb-3 before:border before:border-foreground/10 before:bg-background/30 before:shadow-[0px_3px_5px_#0000000b] before:z-[-1] before:rounded-xl after:absolute after:inset-0 after:border after:border-foreground/10 after:bg-background after:shadow-[0px_3px_5px_#0000000b] after:rounded-xl after:z-[-1] after:backdrop-blur-md cursor-pointer hover:shadow-lg transition-shadow" wire:click="showCardModal('total_students')">
                                <div class="flex">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="album" class="lucide lucide-album size-4 stroke-(--color) fill-(--color)/25 h-7 w-7 stroke-1 [--color:var(--color-danger)]"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"></rect><polyline points="11 3 11 11 14 8 17 11 17 3"></polyline></svg>
                                    <div class="ms-auto">
                                        <div class="bg-(--color)/20 border-(--color)/60 text-(--color) flex cursor-pointer items-center rounded-full border px-2 py-px text-xs tooltip pl-2 pr-1 [--color:var(--color-success)]" data-content="41% Higher than last month">
                                            41%
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="chevron-up" class="lucide lucide-chevron-up size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 ms-0.5"><path d="m18 15-6-6-6 6"></path></svg>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-6 text-2xl font-medium leading-8">{{ number_format($stats['total_students']) }}</div>
                                <div class="mt-1.5 text-xs uppercase opacity-70">Total Students</div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END: General Report -->
                @endguest
                
                @guest('students')
                <!-- BEGIN: General Report -->
                <div class="col-span-12 mt-8 grid grid-cols-12 gap-6">
                    <div class="col-span-12 sm:col-span-6 xl:col-span-3">
                        <div class="box relative p-5 before:absolute before:inset-0 before:mx-3 before:-mb-3 before:border before:border-foreground/10 before:bg-background/30 before:shadow-[0px_3px_5px_#0000000b] before:z-[-1] before:rounded-xl after:absolute after:inset-0 after:border after:border-foreground/10 after:bg-background after:shadow-[0px_3px_5px_#0000000b] after:rounded-xl after:z-[-1] after:backdrop-blur-md cursor-pointer hover:shadow-lg transition-shadow" wire:click="showCardModal('total_users')">
                            <div class="flex">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="users" class="lucide lucide-users size-4 stroke-(--color) fill-(--color)/25 h-7 w-7 stroke-1 drop-shadow [--color:var(--color-success)]"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                                <div class="ms-auto">
                                    <div class="bg-(--color)/20 border-(--color)/60 text-(--color) flex cursor-pointer items-center rounded-full border px-2 py-px text-xs tooltip pl-2 pr-1 [--color:var(--color-success)]" data-content="{{ $stats['total_students'] > 0 ? round(($stats['total_users'] / $stats['total_students']) * 100) : 0 }}% of total students are users">
                                        {{ $stats['total_students'] > 0 ? round(($stats['total_users'] / $stats['total_students']) * 100) : 0 }}%
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="chevron-up" class="lucide lucide-chevron-up size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 ms-0.5"><path d="m18 15-6-6-6 6"></path></svg>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-6 text-2xl font-medium leading-8">{{ number_format($stats['total_users']) }}</div>
                            <div class="mt-1.5 text-xs uppercase opacity-70">Total Admin</div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-6 xl:col-span-3">
                        <div class="box relative p-5 before:absolute before:inset-0 before:mx-3 before:-mb-3 before:border before:border-foreground/10 before:bg-background/30 before:shadow-[0px_3px_5px_#0000000b] before:z-[-1] before:rounded-xl after:absolute after:inset-0 after:border after:border-foreground/10 after:bg-background after:shadow-[0px_3px_5px_#0000000b] after:rounded-xl after:z-[-1] after:backdrop-blur-md cursor-pointer hover:shadow-lg transition-shadow" wire:click="showCardModal('total_voters')">
                            <div class="flex">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="users" class="lucide lucide-users size-4 stroke-(--color) fill-(--color)/25 h-7 w-7 stroke-1 drop-shadow [--color:var(--color-primary)]"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                                <div class="ms-auto">
                                    <div class="bg-(--color)/20 border-(--color)/60 text-(--color) flex cursor-pointer items-center rounded-full border px-2 py-px text-xs tooltip pl-2 pr-1 [--color:var(--color-success)]" data-content="{{ $stats['total_students'] > 0 ? round(($stats['total_voters'] / $stats['total_students']) * 100) : 0 }}% of students have voted">
                                        {{ $stats['total_students'] > 0 ? round(($stats['total_voters'] / $stats['total_students']) * 100) : 0 }}%
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="chevron-up" class="lucide lucide-chevron-up size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 ms-0.5"><path d="m18 15-6-6-6 6"></path></svg>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-6 text-2xl font-medium leading-8">{{ number_format($stats['total_voters']) }}</div>
                            <div class="mt-1.5 text-xs uppercase opacity-70">Total Voters</div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-6 xl:col-span-3">
                        <div class="box relative p-5 before:absolute before:inset-0 before:mx-3 before:-mb-3 before:border before:border-foreground/10 before:bg-background/30 before:shadow-[0px_3px_5px_#0000000b] before:z-[-1] before:rounded-xl after:absolute after:inset-0 after:border after:border-foreground/10 after:bg-background after:shadow-[0px_3px_5px_#0000000b] after:rounded-xl after:z-[-1] after:backdrop-blur-md cursor-pointer hover:shadow-lg transition-shadow" wire:click="showCardModal('total_courses')">
                            <div class="flex">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="book-open" class="lucide lucide-book-open size-4 stroke-(--color) fill-(--color)/25 h-7 w-7 stroke-1 drop-shadow [--color:var(--color-warning)]"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg>
                                <div class="ms-auto">
                                    <div class="bg-(--color)/20 border-(--color)/60 text-(--color) flex cursor-pointer items-center rounded-full border px-2 py-px text-xs tooltip pl-2 pr-1 [--color:var(--color-success)]" data-content="{{ $stats['total_courses'] > 0 ? round(($stats['active_courses'] / $stats['total_courses']) * 100) : 0 }}% of courses are active">
                                        {{ $stats['total_courses'] > 0 ? round(($stats['active_courses'] / $stats['total_courses']) * 100) : 0 }}%
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="chevron-up" class="lucide lucide-chevron-up size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 ms-0.5"><path d="m18 15-6-6-6 6"></path></svg>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-6 text-2xl font-medium leading-8">{{ number_format($stats['total_courses']) }}</div>
                            <div class="mt-1.5 text-xs uppercase opacity-70">Total Courses</div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-6 xl:col-span-3">
                        <div class="box relative p-5 before:absolute before:inset-0 before:mx-3 before:-mb-3 before:border before:border-foreground/10 before:bg-background/30 before:shadow-[0px_3px_5px_#0000000b] before:z-[-1] before:rounded-xl after:absolute after:inset-0 after:border after:border-foreground/10 after:bg-background after:shadow-[0px_3px_5px_#0000000b] after:rounded-xl after:z-[-1] after:backdrop-blur-md cursor-pointer hover:shadow-lg transition-shadow" wire:click="showCardModal('total_meetings')">
                            <div class="flex">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="calendar" class="lucide lucide-calendar size-4 stroke-(--color) fill-(--color)/25 h-7 w-7 stroke-1 drop-shadow [--color:var(--color-danger)]"><path d="M8 2v4"></path><path d="M16 2v4"></path><rect width="18" height="18" x="3" y="4" rx="2"></rect><path d="M3 10h18"></path></svg>
                                <div class="ms-auto">
                                    <div class="bg-(--color)/20 border-(--color)/60 text-(--color) flex cursor-pointer items-center rounded-full border px-2 py-px text-xs tooltip pl-2 pr-1 [--color:var(--color-success)]" data-content="{{ $stats['total_meetings'] > 0 ? round(($stats['active_meetings'] / $stats['total_meetings']) * 100) : 0 }}% of meetings are active">
                                        {{ $stats['total_meetings'] > 0 ? round(($stats['active_meetings'] / $stats['total_meetings']) * 100) : 0 }}%
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="chevron-up" class="lucide lucide-chevron-up size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 ms-0.5"><path d="m18 15-6-6-6 6"></path></svg>
                            </div>
                                </div>
                            </div>
                            <div class="mt-6 text-2xl font-medium leading-8">{{ number_format($stats['total_meetings']) }}</div>
                            <div class="mt-1.5 text-xs uppercase opacity-70">Total Meetings</div>
                        </div>
                    </div>
                </div>
                <!-- END: General Report -->
                @endguest
                
                @auth('students')
                <!-- BEGIN: Student-Friendly Layout -->
                <div class="col-span-12 mt-6">
                    <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h2 class="text-2xl font-semibold">Welcome, {{ auth()->guard('students')->user()->first_name ?? 'Student' }}</h2>
                                <p class="text-sm text-gray-600 dark:text-gray-300">Here are the party lists and candidates you can support. Click any card to view its candidates.</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($partylists as $partylist)
                                <div wire:click="viewPartylistCandidacies({{ $partylist->id }})" class="cursor-pointer border rounded-lg p-4 hover:shadow-md transition">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 flex-none">
                                            @if($partylist->partylist_image)
                                                <img src="{{ asset('storage/' . $partylist->partylist_image) }}" alt="{{ $partylist->partylist_name }}" class="w-12 h-12 rounded-full object-cover">
                                            @else
                                                <div class="w-12 h-12 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600 font-semibold">{{ strtoupper(substr($partylist->partylist_name, 0, 2)) }}</div>
                                            @endif
                                        </div>
                                        <div class="flex-1">
                                            <div class="font-medium text-lg">{{ $partylist->partylist_name }}</div>
                                            <div class="text-xs text-gray-500">{{ $partylist->applied_candidacies->count() }} candidates</div>
                                            @if($partylist->description)
                                                <div class="text-xs text-gray-400 mt-1">{{ Str::limit($partylist->description, 80) }}</div>
                                            @endif
                                        </div>
                                        <div class="text-right">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <!-- END: Student-Friendly Layout -->
                @endauth
                
                
                <!-- END: Weekly Top Products -->
        </div>
        <div class="col-span-12 2xl:col-span-3">
            <div class="-mb-10 h-full pb-10 2xl:border-l">
                <div class="grid grid-cols-12 gap-x-6 gap-y-6 2xl:gap-x-0 2xl:pl-6">
                    <!-- Voting Transactions removed -->
                </div>
            </div>
        </div>
   
    <div data-tw-backdrop="" class="modal group bg-black/60 transition-[visibility,opacity] w-screen h-screen fixed left-0 top-0 [&amp;:not(.show)]:duration-[0s,0.2s] [&amp;:not(.show)]:delay-[0.2s,0s] [&amp;:not(.show)]:invisible [&amp;:not(.show)]:opacity-0 [&amp;.show]:visible [&amp;.show]:opacity-100 [&amp;.show]:duration-[0s,0.4s]" id="onboarding-dialog" aria-hidden="true">
        <div class="box relative before:absolute before:inset-0 before:mx-3 before:-mb-3 before:border before:border-foreground/10 before:z-[-1] after:absolute after:inset-0 after:border after:border-foreground/10 after:bg-background after:shadow-[0px_3px_5px_#0000000b] after:z-[-1] after:backdrop-blur-md before:bg-background/60 dark:before:shadow-background before:shadow-foreground/60 z-50 mx-auto -mt-16 p-6 transition-[margin-top,transform] duration-[0.4s,0.3s] before:rounded-3xl before:shadow-2xl after:rounded-3xl group-[.show]:mt-16 group-[.modal-static]:scale-[1.05] sm:max-w-xl">
            <a class="bg-background/80 hover:bg-background absolute right-0 top-0 -mr-3 -mt-3 flex size-9 items-center justify-center rounded-full border shadow outline-none backdrop-blur" data-tw-dismiss="modal" href="#">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="x" class="lucide lucide-x stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 size-5 opacity-70"><path d="M18 6 6 18"></path><path d="m6 6 12 12"></path></svg>
            </a>
            <div class="-mx-3 pb-5">
                <div class="tns-outer" id="tns2-ow"><div class="tns-nav" aria-label="Carousel Pagination"><button type="button" data-nav="0" aria-controls="tns2" style="" aria-label="Carousel Page 1" class="" tabindex="-1"></button><button type="button" data-nav="1" aria-controls="tns2" style="" aria-label="Carousel Page 2 (Current Slide)" class="tns-nav-active"></button></div><button type="button" data-action="stop"><span class="tns-visually-hidden">stop animation</span>stop</button><div class="tns-liveregion tns-visually-hidden" aria-live="polite" aria-atomic="true">slide <span class="current">4</span>  of 2</div><div id="tns2-mw" class="tns-ovh"><div class="tns-inner" id="tns2-iw"><div data-config="{
    nav: true
}" class="tiny-slider mb-11 mt-2  tns-slider tns-carousel tns-subpixel tns-calc tns-horizontal" id="tns2" style="transform: translate3d(-50%, 0px, 0px); transition-duration: 0s;"><div class="relative mx-3 flex flex-col items-center gap-1 px-3.5 tns-item tns-slide-cloned" aria-hidden="true" tabindex="-1">
                            <div class="w-full bg-primary/[.05] mb-7 border-primary/10 shadow-lg shadow-black/10 relative rounded-3xl border h-52 overflow-hidden before:bg-noise before:absolute before:inset-0 before:opacity-30 after:bg-accent after:absolute after:inset-0 after:opacity-30 after:blur-2xl">
                            <img class="absolute inset-0 mx-auto mt-10 w-2/5 scale-125" src="dist/images/phone-illustration.svg" alt="stii-evote - Admin Dashboard Template">
                        </div>
                        <div class="px-8">
                            <div class="text-center text-xl font-medium">Welcome to stii-evote</div>
                            <div class="mt-3 text-center text-base leading-relaxed opacity-70">
                                A lightweight and focused admin dashboard for the stii-evote system.<br>
                                Manage elections, candidates, and student records with confidence.
                            </div>
                        </div>
                        <div class="absolute inset-x-0 bottom-0 -mb-12 flex place-content-between px-5">
                            <a class="text-danger flex items-center gap-3 font-medium" data-tw-dismiss="modal" href="">
                                Skip Intro
                            </a>
                            <a class="text-primary flex items-center gap-3 font-medium" href="">
                                Next <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="move-right" class="lucide lucide-move-right size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25"><path d="M18 8L22 12L18 16"></path><path d="M2 12H22"></path></svg>
                            </a>
                        </div>
                    </div><div class="relative mx-3 flex flex-col items-center gap-1 px-3.5 tns-item tns-slide-cloned" aria-hidden="true" tabindex="-1">
                        <div class="w-full bg-primary/[.05] mb-7 border-primary/10 shadow-lg shadow-black/10 relative rounded-3xl border h-52 overflow-hidden before:bg-noise before:absolute before:inset-0 before:opacity-30 after:bg-accent after:absolute after:inset-0 after:opacity-30 after:blur-2xl">
                            <img class="absolute inset-0 mx-auto mt-10 w-2/5 scale-125" src="dist/images/woman-illustration.svg" alt="Midone - Tailwind Admin Dashboard Template">
                        </div>
                        <div class="w-full">
                            <div class="text-center text-xl font-medium">Example Request Information</div>
                            <div class="mt-3 text-center text-base leading-relaxed opacity-70">
                                Your premium admin dashboard template.
                            </div>
                            <div class="mt-8">
                                <div class="grid grid-cols-2 gap-5 px-5">
                                    <div class="flex flex-col gap-2.5"><label class="font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Full Name</label>
                                        <input type="text" placeholder="John Doe" class="h-10 w-full rounded-md border bg-background px-3 py-2 ring-offset-background file:border-0 file:bg-transparent file:font-medium file:text-foreground placeholder:text-foreground/70 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                                    </div>
                                    <div class="flex flex-col gap-2.5"><label class="font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Event</label>
                                        <select class="bg-(image:--background-image-chevron) bg-[position:calc(100%-theme(spacing.3))_center] bg-[size:theme(spacing.5)] bg-no-repeat relative appearance-none flex h-10 w-full rounded-md border bg-background px-3 py-2 ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                                            <option>Corporate Event</option>
                                            <option>Wedding</option>
                                            <option>Birthday</option>
                                            <option>Other</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="absolute inset-x-0 bottom-0 -mb-12 flex place-content-between px-5">
                            <a class="text-primary flex items-center gap-3 font-medium" href="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="move-left" class="lucide lucide-move-left size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25"><path d="M6 8L2 12L6 16"></path><path d="M2 12H22"></path></svg>
                                Previous
                            </a>
                        </div>
                    </div>
                    <div class="relative mx-3 flex flex-col items-center gap-1 px-3.5 tns-item tns-slide-cloned" aria-hidden="true" tabindex="-1">
                        <div class="w-full bg-primary/[.05] mb-7 border-primary/10 shadow-lg shadow-black/10 relative rounded-3xl border h-52 overflow-hidden before:bg-noise before:absolute before:inset-0 before:opacity-30 after:bg-accent after:absolute after:inset-0 after:opacity-30 after:blur-2xl">
                            <img class="absolute inset-0 mx-auto mt-10 w-2/5 scale-125" src="dist/images/woman-illustration.svg" alt="Midone - Tailwind Admin Dashboard Template">
                        </div>
                        <div class="w-full">
                            <div class="text-center text-xl font-medium">Example Request Information</div>
                            <div class="mt-3 text-center text-base leading-relaxed opacity-70">
                                Your premium admin dashboard template.
                            </div>
                            <div class="mt-8">
                                <div class="grid grid-cols-2 gap-5 px-5">
                                    <div class="flex flex-col gap-2.5"><label class="font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Full Name</label>
                                        <input type="text" placeholder="John Doe" class="h-10 w-full rounded-md border bg-background px-3 py-2 ring-offset-background file:border-0 file:bg-transparent file:font-medium file:text-foreground placeholder:text-foreground/70 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                                    </div>
                                    <div class="flex flex-col gap-2.5"><label class="font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Event</label>
                                        <select class="bg-(image:--background-image-chevron) bg-[position:calc(100%-theme(spacing.3))_center] bg-[size:theme(spacing.5)] bg-no-repeat relative appearance-none flex h-10 w-full rounded-md border bg-background px-3 py-2 ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                                            <option>Corporate Event</option>
                                            <option>Wedding</option>
                                            <option>Birthday</option>
                                            <option>Other</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="absolute inset-x-0 bottom-0 -mb-12 flex place-content-between px-5">
                            <a class="text-primary flex items-center gap-3 font-medium" href="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="move-left" class="lucide lucide-move-left size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25"><path d="M6 8L2 12L6 16"></path><path d="M2 12H22"></path></svg>
                                Previous
                            </a>
                        </div>
                    </div>
                </div>
                <!-- END: Weekly Top Products -->
        </div>
        <div class="col-span-12 2xl:col-span-3">
            <div class="-mb-10 h-full pb-10 2xl:border-l">
                <div class="grid grid-cols-12 gap-x-6 gap-y-6 2xl:gap-x-0 2xl:pl-6">
                    <!-- BEGIN: Transactions -->
                    @auth('students')
                    <!-- Hide Voting Transactions Section for Students -->
                    @else
                    <div class="col-span-12 mt-3 md:col-span-6 xl:col-span-4 2xl:col-span-12 2xl:mt-8">
                        <div class="flex h-10 items-center">
                            <h2 class="me-5 truncate text-lg font-medium">Voting Transactions</h2>
                        </div>
                        <div class="mt-5">
                            @forelse($votingTransactions as $transaction)
                            <div class="box relative p-5 before:absolute before:inset-0 before:mx-3 before:-mb-3 before:border before:border-foreground/10 before:bg-background/30 before:shadow-[0px_3px_5px_#0000000b] before:z-[-1] before:rounded-xl after:absolute after:inset-0 after:border after:border-foreground/10 after:bg-background after:shadow-[0px_3px_5px_#0000000b] after:rounded-xl after:z-[-1] after:backdrop-blur-md mb-3 flex items-center px-5 py-3 before:hidden">
                                    <span data-content="" class="tooltip border-(--color)/5 block relative size-11 flex-none overflow-hidden rounded-full border-3 ring-1 ring-(--color)/25 [--color:var(--color-success)]">
                                        @php
                                            $imageSrc = asset('images/placeholders/placeholder.jpg'); // Default placeholder
                                            
                                            // Check profile_image field first (profile_pictures folder)
                                            if ($transaction->student && $transaction->student->profile_image) {
                                                if (file_exists(public_path('storage/' . $transaction->student->profile_image))) {
                                                    $imageSrc = asset('storage/' . $transaction->student->profile_image);
                                                }
                                            }
                                            
                                            // If no profile_image or file doesn't exist, check student_images directory
                                            if ($imageSrc === asset('images/placeholders/placeholder.jpg') && $transaction->student) {
                                                $studentImagesPath = storage_path('app/public/student_images/');
                                                
                                                // Look for profile images with student ID
                                                $profilePattern = $studentImagesPath . '*_profile_*' . $transaction->student->id . '*';
                                                $profileFiles = glob($profilePattern);
                                                
                                                if (!empty($profileFiles)) {
                                                    $profileFile = basename($profileFiles[0]);
                                                    $imageSrc = asset('storage/student_images/' . $profileFile);
                                                } else {
                                                    // Look for ID images as fallback
                                                    $idPattern = $studentImagesPath . '*_id_*' . $transaction->student->id . '*';
                                                    $idFiles = glob($idPattern);
                                                    
                                                    if (!empty($idFiles)) {
                                                        $idFile = basename($idFiles[0]);
                                                        $imageSrc = asset('storage/student_images/' . $idFile);
                                                    }
                                                }
                                            }
                                        @endphp
                                        @if($imageSrc !== asset('images/placeholders/placeholder.jpg'))
                                            <img class="absolute top-0 size-full object-cover" src="{{ $imageSrc }}" alt="{{ $transaction->student->first_name ?? 'Unknown' }} {{ $transaction->student->last_name ?? 'Student' }}">
                                        @else
                                            <div class="absolute top-0 size-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-semibold text-sm">
                                                {{ strtoupper(substr($transaction->student->first_name ?? 'U', 0, 1)) }}{{ strtoupper(substr($transaction->student->last_name ?? 'N', 0, 1)) }}
                                    </div>
                                        @endif
                                </span>
                                <div class="me-auto ms-4">
                                        <div class="font-medium">{{ $transaction->student->first_name ?? 'Unknown' }} {{ $transaction->student->last_name ?? 'Student' }}</div>
                                    <div class="mt-1 text-xs opacity-70">
                                            {{ $transaction->created_at->format('M d, Y g:i A') }}
                                            @if($transaction->student && $transaction->student->course)
                                                • {{ $transaction->student->course->course_name }}
                                            @endif
                            </div>
                                    </div>
                                    <div class="text-success flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1">
                                            <path d="M9 12l2 2 4-4"></path>
                                            <path d="M21 12c.552 0 1-.448 1-1V8c0-.552-.448-1-1-1h-3.5l-1-1h-5l-1 1H3c-.552 0-1 .448-1 1v3c0 .552.448 1 1 1h18z"></path>
                                        </svg>
                                        Voted
                                    </div>
                                </div>
                            @empty
                                <div class="box relative p-8 before:absolute before:inset-0 before:mx-3 before:-mb-3 before:border before:border-foreground/10 before:bg-background/30 before:shadow-[0px_3px_5px_#0000000b] before:z-[-1] before:rounded-xl after:absolute after:inset-0 after:border after:border-foreground/10 after:bg-background after:shadow-[0px_3px_5px_#0000000b] after:rounded-xl after:z-[-1] after:backdrop-blur-md text-center">
                                    <div class="text-gray-500">
                                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <h3 class="text-sm font-medium text-gray-900 mb-1">No Voting Transactions</h3>
                                        <p class="text-sm text-gray-500">No voting activity has been recorded yet.</p>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                    @endauth
                </div>
            </div>
        </div>
   
    <div data-tw-backdrop="" class="modal group bg-black/60 transition-[visibility,opacity] w-screen h-screen fixed left-0 top-0 [&amp;:not(.show)]:duration-[0s,0.2s] [&amp;:not(.show)]:delay-[0.2s,0s] [&amp;:not(.show)]:invisible [&amp;:not(.show)]:opacity-0 [&amp;.show]:visible [&amp;.show]:opacity-100 [&amp;.show]:duration-[0s,0.4s]" id="onboarding-dialog" aria-hidden="true">
        <div class="box relative before:absolute before:inset-0 before:mx-3 before:-mb-3 before:border before:border-foreground/10 before:z-[-1] after:absolute after:inset-0 after:border after:border-foreground/10 after:bg-background after:shadow-[0px_3px_5px_#0000000b] after:z-[-1] after:backdrop-blur-md before:bg-background/60 dark:before:shadow-background before:shadow-foreground/60 z-50 mx-auto -mt-16 p-6 transition-[margin-top,transform] duration-[0.4s,0.3s] before:rounded-3xl before:shadow-2xl after:rounded-3xl group-[.show]:mt-16 group-[.modal-static]:scale-[1.05] sm:max-w-xl">
            <a class="bg-background/80 hover:bg-background absolute right-0 top-0 -mr-3 -mt-3 flex size-9 items-center justify-center rounded-full border shadow outline-none backdrop-blur" data-tw-dismiss="modal" href="#">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="x" class="lucide lucide-x stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 size-5 opacity-70"><path d="M18 6 6 18"></path><path d="m6 6 12 12"></path></svg>
            </a>
            <div class="-mx-3 pb-5">
                <div class="tns-outer" id="tns2-ow"><div class="tns-nav" aria-label="Carousel Pagination"><button type="button" data-nav="0" aria-controls="tns2" style="" aria-label="Carousel Page 1" class="" tabindex="-1"></button><button type="button" data-nav="1" aria-controls="tns2" style="" aria-label="Carousel Page 2 (Current Slide)" class="tns-nav-active"></button></div><button type="button" data-action="stop"><span class="tns-visually-hidden">stop animation</span>stop</button><div class="tns-liveregion tns-visually-hidden" aria-live="polite" aria-atomic="true">slide <span class="current">4</span>  of 2</div><div id="tns2-mw" class="tns-ovh"><div class="tns-inner" id="tns2-iw"><div data-config="{
    nav: true
}" class="tiny-slider mb-11 mt-2  tns-slider tns-carousel tns-subpixel tns-calc tns-horizontal" id="tns2" style="transform: translate3d(-50%, 0px, 0px); transition-duration: 0s;"><div class="relative mx-3 flex flex-col items-center gap-1 px-3.5 tns-item tns-slide-cloned" aria-hidden="true" tabindex="-1">
                        <div class="w-full bg-primary/[.05] mb-7 border-primary/10 shadow-lg shadow-black/10 relative rounded-3xl border h-52 overflow-hidden before:bg-noise before:absolute before:inset-0 before:opacity-30 after:bg-accent after:absolute after:inset-0 after:opacity-30 after:blur-2xl">
                            <img class="absolute inset-0 mx-auto mt-10 w-2/5 scale-125" src="dist/images/phone-illustration.svg" alt="Midone - Tailwind Admin Dashboard Template">
                        </div>
                        <div class="px-8">
                            <div class="text-center text-xl font-medium">Welcome to Midone Admin!</div>
                            <div class="mt-3 text-center text-base leading-relaxed opacity-70">
                                Premium admin dashboard template for all kinds <br> of projects.
                                With a unique and modern design, Midone offers the perfect foundation to build professional
                                applications with ease.
                            </div>
                        </div>
                        <div class="absolute inset-x-0 bottom-0 -mb-12 flex place-content-between px-5">
                            <a class="text-danger flex items-center gap-3 font-medium" data-tw-dismiss="modal" href="">
                                Skip Intro
                            </a>
                            <a class="text-primary flex items-center gap-3 font-medium" href="">
                                Next <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="move-right" class="lucide lucide-move-right size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25"><path d="M18 8L22 12L18 16"></path><path d="M2 12H22"></path></svg>
                            </a>
                        </div>
                    </div><div class="relative mx-3 flex flex-col items-center gap-1 px-3.5 tns-item tns-slide-cloned" aria-hidden="true" tabindex="-1">
                        <div class="w-full bg-primary/[.05] mb-7 border-primary/10 shadow-lg shadow-black/10 relative rounded-3xl border h-52 overflow-hidden before:bg-noise before:absolute before:inset-0 before:opacity-30 after:bg-accent after:absolute after:inset-0 after:opacity-30 after:blur-2xl">
                            <img class="absolute inset-0 mx-auto mt-10 w-2/5 scale-125" src="dist/images/woman-illustration.svg" alt="Midone - Tailwind Admin Dashboard Template">
                        </div>
                        <div class="w-full">
                            <div class="text-center text-xl font-medium">Example Request Information</div>
                            <div class="mt-3 text-center text-base leading-relaxed opacity-70">
                                Your premium admin dashboard template.
                            </div>
                            <div class="mt-8">
                                <div class="grid grid-cols-2 gap-5 px-5">
                                    <div class="flex flex-col gap-2.5"><label class="font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Full Name</label>
                                        <input type="text" placeholder="John Doe" class="h-10 w-full rounded-md border bg-background px-3 py-2 ring-offset-background file:border-0 file:bg-transparent file:font-medium file:text-foreground placeholder:text-foreground/70 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                                    </div>
                                    <div class="flex flex-col gap-2.5"><label class="font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Event</label>
                                        <select class="bg-(image:--background-image-chevron) bg-[position:calc(100%-theme(spacing.3))_center] bg-[size:theme(spacing.5)] bg-no-repeat relative appearance-none flex h-10 w-full rounded-md border bg-background px-3 py-2 ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                                            <option>Corporate Event</option>
                                            <option>Wedding</option>
                                            <option>Birthday</option>
                                            <option>Other</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="absolute inset-x-0 bottom-0 -mb-12 flex place-content-between px-5">
                            <a class="text-primary flex items-center gap-3 font-medium" href="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="move-left" class="lucide lucide-move-left size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25"><path d="M6 8L2 12L6 16"></path><path d="M2 12H22"></path></svg>
                                Previous
                            </a>
                        </div>
                    </div>
                    <div class="relative mx-3 flex flex-col items-center gap-1 px-3.5 tns-item tns-slide-cloned" aria-hidden="true" tabindex="-1">
                        <div class="w-full bg-primary/[.05] mb-7 border-primary/10 shadow-lg shadow-black/10 relative rounded-3xl border h-52 overflow-hidden before:bg-noise before:absolute before:inset-0 before:opacity-30 after:bg-accent after:absolute after:inset-0 after:opacity-30 after:blur-2xl">
                            <img class="absolute inset-0 mx-auto mt-10 w-2/5 scale-125" src="dist/images/woman-illustration.svg" alt="Midone - Tailwind Admin Dashboard Template">
                        </div>
                        <div class="w-full">
                            <div class="text-center text-xl font-medium">Example Request Information</div>
                            <div class="mt-3 text-center text-base leading-relaxed opacity-70">
                                Your premium admin dashboard template.
                            </div>
                            <div class="mt-8">
                                <div class="grid grid-cols-2 gap-5 px-5">
                                    <div class="flex flex-col gap-2.5"><label class="font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Full Name</label>
                                        <input type="text" placeholder="John Doe" class="h-10 w-full rounded-md border bg-background px-3 py-2 ring-offset-background file:border-0 file:bg-transparent file:font-medium file:text-foreground placeholder:text-foreground/70 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                                    </div>
                                    <div class="flex flex-col gap-2.5"><label class="font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Event</label>
                                        <select class="bg-(image:--background-image-chevron) bg-[position:calc(100%-theme(spacing.3))_center] bg-[size:theme(spacing.5)] bg-no-repeat relative appearance-none flex h-10 w-full rounded-md border bg-background px-3 py-2 ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                                            <option>Corporate Event</option>
                                            <option>Wedding</option>
                                            <option>Birthday</option>
                                            <option>Other</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="absolute inset-x-0 bottom-0 -mb-12 flex place-content-between px-5">
                            <a class="text-primary flex items-center gap-3 font-medium" href="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="move-left" class="lucide lucide-move-left size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25"><path d="M6 8L2 12L6 16"></path><path d="M2 12H22"></path></svg>
                                Previous
                            </a>
                        </div>
                    </div>
                </div>
                <!-- END: Weekly Top Products -->
        </div>
        <div class="col-span-12 2xl:col-span-3">
            <div class="-mb-10 h-full pb-10 2xl:border-l">
                <div class="grid grid-cols-12 gap-x-6 gap-y-6 2xl:gap-x-0 2xl:pl-6">
                    <!-- BEGIN: Transactions -->
                    @auth('students')
                    <!-- Hide Voting Transactions Section for Students -->
                    @else
                    <div class="col-span-12 mt-3 md:col-span-6 xl:col-span-4 2xl:col-span-12 2xl:mt-8">
                        <div class="flex h-10 items-center">
                            <h2 class="me-5 truncate text-lg font-medium">Voting Transactions</h2>
                        </div>
                        <div class="mt-5">
                            @forelse($votingTransactions as $transaction)
                            <div class="box relative p-5 before:absolute before:inset-0 before:mx-3 before:-mb-3 before:border before:border-foreground/10 before:bg-background/30 before:shadow-[0px_3px_5px_#0000000b] before:z-[-1] before:rounded-xl after:absolute after:inset-0 after:border after:border-foreground/10 after:bg-background after:shadow-[0px_3px_5px_#0000000b] after:rounded-xl after:z-[-1] after:backdrop-blur-md mb-3 flex items-center px-5 py-3 before:hidden">
                                    <span data-content="" class="tooltip border-(--color)/5 block relative size-11 flex-none overflow-hidden rounded-full border-3 ring-1 ring-(--color)/25 [--color:var(--color-success)]">
                                        @php
                                            $imageSrc = asset('images/placeholders/placeholder.jpg'); // Default placeholder
                                            
                                            // Check profile_image field first (profile_pictures folder)
                                            if ($transaction->student && $transaction->student->profile_image) {
                                                if (file_exists(public_path('storage/' . $transaction->student->profile_image))) {
                                                    $imageSrc = asset('storage/' . $transaction->student->profile_image);
                                                }
                                            }
                                            
                                            // If no profile_image or file doesn't exist, check student_images directory
                                            if ($imageSrc === asset('images/placeholders/placeholder.jpg') && $transaction->student) {
                                                $studentImagesPath = storage_path('app/public/student_images/');
                                                
                                                // Look for profile images with student ID
                                                $profilePattern = $studentImagesPath . '*_profile_*' . $transaction->student->id . '*';
                                                $profileFiles = glob($profilePattern);
                                                
                                                if (!empty($profileFiles)) {
                                                    $profileFile = basename($profileFiles[0]);
                                                    $imageSrc = asset('storage/student_images/' . $profileFile);
                                                } else {
                                                    // Look for ID images as fallback
                                                    $idPattern = $studentImagesPath . '*_id_*' . $transaction->student->id . '*';
                                                    $idFiles = glob($idPattern);
                                                    
                                                    if (!empty($idFiles)) {
                                                        $idFile = basename($idFiles[0]);
                                                        $imageSrc = asset('storage/student_images/' . $idFile);
                                                    }
                                                }
                                            }
                                        @endphp
                                        @if($imageSrc !== asset('images/placeholders/placeholder.jpg'))
                                            <img class="absolute top-0 size-full object-cover" src="{{ $imageSrc }}" alt="{{ $transaction->student->first_name ?? 'Unknown' }} {{ $transaction->student->last_name ?? 'Student' }}">
                                        @else
                                            <div class="absolute top-0 size-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-semibold text-sm">
                                                {{ strtoupper(substr($transaction->student->first_name ?? 'U', 0, 1)) }}{{ strtoupper(substr($transaction->student->last_name ?? 'N', 0, 1)) }}
                                    </div>
                                        @endif
                                </span>
                                <div class="me-auto ms-4">
                                        <div class="font-medium">{{ $transaction->student->first_name ?? 'Unknown' }} {{ $transaction->student->last_name ?? 'Student' }}</div>
                                    <div class="mt-1 text-xs opacity-70">
                                            {{ $transaction->created_at->format('M d, Y g:i A') }}
                                            @if($transaction->student && $transaction->student->course)
                                                • {{ $transaction->student->course->course_name }}
                                            @endif
                            </div>
                                    </div>
                                    <div class="text-success flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1">
                                            <path d="M9 12l2 2 4-4"></path>
                                            <path d="M21 12c.552 0 1-.448 1-1V8c0-.552-.448-1-1-1h-3.5l-1-1h-5l-1 1H3c-.552 0-1 .448-1 1v3c0 .552.448 1 1 1h18z"></path>
                                        </svg>
                                        Voted
                                    </div>
                                </div>
                            @empty
                                <div class="box relative p-8 before:absolute before:inset-0 before:mx-3 before:-mb-3 before:border before:border-foreground/10 before:bg-background/30 before:shadow-[0px_3px_5px_#0000000b] before:z-[-1] before:rounded-xl after:absolute after:inset-0 after:border after:border-foreground/10 after:bg-background after:shadow-[0px_3px_5px_#0000000b] after:rounded-xl after:z-[-1] after:backdrop-blur-md text-center">
                                    <div class="text-gray-500">
                                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <h3 class="text-sm font-medium text-gray-900 mb-1">No Voting Transactions</h3>
                                        <p class="text-sm text-gray-500">No voting activity has been recorded yet.</p>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                    @endauth
                </div>
            </div>
        </div>
   
    <div data-tw-backdrop="" class="modal group bg-black/60 transition-[visibility,opacity] w-screen h-screen fixed left-0 top-0 [&amp;:not(.show)]:duration-[0s,0.2s] [&amp;:not(.show)]:delay-[0.2s,0s] [&amp;:not(.show)]:invisible [&amp;:not(.show)]:opacity-0 [&amp;.show]:visible [&amp;.show]:opacity-100 [&amp;.show]:duration-[0s,0.4s]" id="onboarding-dialog" aria-hidden="true">
        <div class="box relative before:absolute before:inset-0 before:mx-3 before:-mb-3 before:border before:border-foreground/10 before:z-[-1] after:absolute after:inset-0 after:border after:border-foreground/10 after:bg-background after:shadow-[0px_3px_5px_#0000000b] after:z-[-1] after:backdrop-blur-md before:bg-background/60 dark:before:shadow-background before:shadow-foreground/60 z-50 mx-auto -mt-16 p-6 transition-[margin-top,transform] duration-[0.4s,0.3s] before:rounded-3xl before:shadow-2xl after:rounded-3xl group-[.show]:mt-16 group-[.modal-static]:scale-[1.05] sm:max-w-xl">
            <a class="bg-background/80 hover:bg-background absolute right-0 top-0 -mr-3 -mt-3 flex size-9 items-center justify-center rounded-full border shadow outline-none backdrop-blur" data-tw-dismiss="modal" href="#">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="x" class="lucide lucide-x stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 size-5 opacity-70"><path d="M18 6 6 18"></path><path d="m6 6 12 12"></path></svg>
            </a>
            <div class="-mx-3 pb-5">
                <div class="tns-outer" id="tns2-ow"><div class="tns-nav" aria-label="Carousel Pagination"><button type="button" data-nav="0" aria-controls="tns2" style="" aria-label="Carousel Page 1" class="" tabindex="-1"></button><button type="button" data-nav="1" aria-controls="tns2" style="" aria-label="Carousel Page 2 (Current Slide)" class="tns-nav-active"></button></div><button type="button" data-action="stop"><span class="tns-visually-hidden">stop animation</span>stop</button><div class="tns-liveregion tns-visually-hidden" aria-live="polite" aria-atomic="true">slide <span class="current">4</span>  of 2</div><div id="tns2-mw" class="tns-ovh"><div class="tns-inner" id="tns2-iw"><div data-config="{
    nav: true
}" class="tiny-slider mb-11 mt-2  tns-slider tns-carousel tns-subpixel tns-calc tns-horizontal" id="tns2" style="transform: translate3d(-50%, 0px, 0px); transition-duration: 0s;"><div class="relative mx-3 flex flex-col items-center gap-1 px-3.5 tns-item tns-slide-cloned" aria-hidden="true" tabindex="-1">
                        <div class="w-full bg-primary/[.05] mb-7 border-primary/10 shadow-lg shadow-black/10 relative rounded-3xl border h-52 overflow-hidden before:bg-noise before:absolute before:inset-0 before:opacity-30 after:bg-accent after:absolute after:inset-0 after:opacity-30 after:blur-2xl">
                            <img class="absolute inset-0 mx-auto mt-10 w-2/5 scale-125" src="dist/images/phone-illustration.svg" alt="Midone - Tailwind Admin Dashboard Template">
                        </div>
                        <div class="px-8">
                            <div class="text-center text-xl font-medium">Welcome to Midone Admin!</div>
                            <div class="mt-3 text-center text-base leading-relaxed opacity-70">
                                Premium admin dashboard template for all kinds <br> of projects.
                                With a unique and modern design, Midone offers the perfect foundation to build professional
                                applications with ease.
                            </div>
                        </div>
                        <div class="absolute inset-x-0 bottom-0 -mb-12 flex place-content-between px-5">
                            <a class="text-danger flex items-center gap-3 font-medium" data-tw-dismiss="modal" href="">
                                Skip Intro
                            </a>
                            <a class="text-primary flex items-center gap-3 font-medium" href="">
                                Next <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="move-right" class="lucide lucide-move-right size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25"><path d="M18 8L22 12L18 16"></path><path d="M2 12H22"></path></svg>
                            </a>
                        </div>
                    </div><div class="relative mx-3 flex flex-col items-center gap-1 px-3.5 tns-item tns-slide-cloned" aria-hidden="true" tabindex="-1">
                        <div class="w-full bg-primary/[.05] mb-7 border-primary/10 shadow-lg shadow-black/10 relative rounded-3xl border h-52 overflow-hidden before:bg-noise before:absolute before:inset-0 before:opacity-30 after:bg-accent after:absolute after:inset-0 after:opacity-30 after:blur-2xl">
                            <img class="absolute inset-0 mx-auto mt-10 w-2/5 scale-125" src="dist/images/woman-illustration.svg" alt="Midone - Tailwind Admin Dashboard Template">
                        </div>
                        <div class="w-full">
                            <div class="text-center text-xl font-medium">Example Request Information</div>
                            <div class="mt-3 text-center text-base leading-relaxed opacity-70">
                                Your premium admin dashboard template.
                            </div>
                            <div class="mt-8">
                                <div class="grid grid-cols-2 gap-5 px-5">
                                    <div class="flex flex-col gap-2.5"><label class="font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Full Name</label>
                                        <input type="text" placeholder="John Doe" class="h-10 w-full rounded-md border bg-background px-3 py-2 ring-offset-background file:border-0 file:bg-transparent file:font-medium file:text-foreground placeholder:text-foreground/70 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                                    </div>
                                    <div class="flex flex-col gap-2.5"><label class="font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Event</label>
                                        <select class="bg-(image:--background-image-chevron) bg-[position:calc(100%-theme(spacing.3))_center] bg-[size:theme(spacing.5)] bg-no-repeat relative appearance-none flex h-10 w-full rounded-md border bg-background px-3 py-2 ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                                            <option>Corporate Event</option>
                                            <option>Wedding</option>
                                            <option>Birthday</option>
                                            <option>Other</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="absolute inset-x-0 bottom-0 -mb-12 flex place-content-between px-5">
                            <a class="text-primary flex items-center gap-3 font-medium" href="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="move-left" class="lucide lucide-move-left size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25"><path d="M6 8L2 12L6 16"></path><path d="M2 12H22"></path></svg>
                                Previous
                            </a>
                        </div>
                    </div>
                </div>
                <!-- END: Weekly Top Products -->
        </div>
        <div class="col-span-12 2xl:col-span-3">
            <div class="-mb-10 h-full pb-10 2xl:border-l">
                <div class="grid grid-cols-12 gap-x-6 gap-y-6 2xl:gap-x-0 2xl:pl-6">
                    <!-- BEGIN: Transactions -->
                    @auth('students')
                    <!-- Hide Voting Transactions Section for Students -->
                    @else
                    <div class="col-span-12 mt-3 md:col-span-6 xl:col-span-4 2xl:col-span-12 2xl:mt-8">
                        <div class="flex h-10 items-center">
                            <h2 class="me-5 truncate text-lg font-medium">Voting Transactions</h2>
                        </div>
                        <div class="mt-5">
                            @forelse($votingTransactions as $transaction)
                            <div class="box relative p-5 before:absolute before:inset-0 before:mx-3 before:-mb-3 before:border before:border-foreground/10 before:bg-background/30 before:shadow-[0px_3px_5px_#0000000b] before:z-[-1] before:rounded-xl after:absolute after:inset-0 after:border after:border-foreground/10 after:bg-background after:shadow-[0px_3px_5px_#0000000b] after:rounded-xl after:z-[-1] after:backdrop-blur-md mb-3 flex items-center px-5 py-3 before:hidden">
                                    <span data-content="" class="tooltip border-(--color)/5 block relative size-11 flex-none overflow-hidden rounded-full border-3 ring-1 ring-(--color)/25 [--color:var(--color-success)]">
                                        @php
                                            $imageSrc = asset('images/placeholders/placeholder.jpg'); // Default placeholder
                                            
                                            // Check profile_image field first (profile_pictures folder)
                                            if ($transaction->student && $transaction->student->profile_image) {
                                                if (file_exists(public_path('storage/' . $transaction->student->profile_image))) {
                                                    $imageSrc = asset('storage/' . $transaction->student->profile_image);
                                                }
                                            }
                                            
                                            // If no profile_image or file doesn't exist, check student_images directory
                                            if ($imageSrc === asset('images/placeholders/placeholder.jpg') && $transaction->student) {
                                                $studentImagesPath = storage_path('app/public/student_images/');
                                                
                                                // Look for profile images with student ID
                                                $profilePattern = $studentImagesPath . '*_profile_*' . $transaction->student->id . '*';
                                                $profileFiles = glob($profilePattern);
                                                
                                                if (!empty($profileFiles)) {
                                                    $profileFile = basename($profileFiles[0]);
                                                    $imageSrc = asset('storage/student_images/' . $profileFile);
                                                } else {
                                                    // Look for ID images as fallback
                                                    $idPattern = $studentImagesPath . '*_id_*' . $transaction->student->id . '*';
                                                    $idFiles = glob($idPattern);
                                                    
                                                    if (!empty($idFiles)) {
                                                        $idFile = basename($idFiles[0]);
                                                        $imageSrc = asset('storage/student_images/' . $idFile);
                                                    }
                                                }
                                            }
                                        @endphp
                                        @if($imageSrc !== asset('images/placeholders/placeholder.jpg'))
                                            <img class="absolute top-0 size-full object-cover" src="{{ $imageSrc }}" alt="{{ $transaction->student->first_name ?? 'Unknown' }} {{ $transaction->student->last_name ?? 'Student' }}">
                                        @else
                                            <div class="absolute top-0 size-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-semibold text-sm">
                                                {{ strtoupper(substr($transaction->student->first_name ?? 'U', 0, 1)) }}{{ strtoupper(substr($transaction->student->last_name ?? 'N', 0, 1)) }}
                                    </div>
                                        @endif
                                </span>
                                <div class="me-auto ms-4">
                                        <div class="font-medium">{{ $transaction->student->first_name ?? 'Unknown' }} {{ $transaction->student->last_name ?? 'Student' }}</div>
                                    <div class="mt-1 text-xs opacity-70">
                                            {{ $transaction->created_at->format('M d, Y g:i A') }}
                                            @if($transaction->student && $transaction->student->course)
                                                • {{ $transaction->student->course->course_name }}
                                            @endif
                            </div>
                                    </div>
                                    <div class="text-success flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1">
                                            <path d="M9 12l2 2 4-4"></path>
                                            <path d="M21 12c.552 0 1-.448 1-1V8c0-.552-.448-1-1-1h-3.5l-1-1h-5l-1 1H3c-.552 0-1 .448-1 1v3c0 .552.448 1 1 1h18z"></path>
                                        </svg>
                                        Voted
                                    </div>
                                </div>
                            @empty
                                <div class="box relative p-8 before:absolute before:inset-0 before:mx-3 before:-mb-3 before:border before:border-foreground/10 before:bg-background/30 before:shadow-[0px_3px_5px_#0000000b] before:z-[-1] before:rounded-xl after:absolute after:inset-0 after:border after:border-foreground/10 after:bg-background after:shadow-[0px_3px_5px_#0000000b] after:rounded-xl after:z-[-1] after:backdrop-blur-md text-center">
                                    <div class="text-gray-500">
                                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <h3 class="text-sm font-medium text-gray-900 mb-1">No Voting Transactions</h3>
                                        <p class="text-sm text-gray-500">No voting activity has been recorded yet.</p>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                    @endauth
                </div>
            </div>
        </div>
   
    <div data-tw-backdrop="" class="modal group bg-black/60 transition-[visibility,opacity] w-screen h-screen fixed left-0 top-0 [&amp;:not(.show)]:duration-[0s,0.2s] [&amp;:not(.show)]:delay-[0.2s,0s] [&amp;:not(.show)]:invisible [&amp;:not(.show)]:opacity-0 [&amp;.show]:visible [&amp;.show]:opacity-100 [&amp;.show]:duration-[0s,0.4s]" id="onboarding-dialog" aria-hidden="true">
        <div class="box relative before:absolute before:inset-0 before:mx-3 before:-mb-3 before:border before:border-foreground/10 before:z-[-1] after:absolute after:inset-0 after:border after:border-foreground/10 after:bg-background after:shadow-[0px_3px_5px_#0000000b] after:z-[-1] after:backdrop-blur-md before:bg-background/60 dark:before:shadow-background before:shadow-foreground/60 z-50 mx-auto -mt-16 p-6 transition-[margin-top,transform] duration-[0.4s,0.3s] before:rounded-3xl before:shadow-2xl after:rounded-3xl group-[.show]:mt-16 group-[.modal-static]:scale-[1.05] sm:max-w-xl">
            <a class="bg-background/80 hover:bg-background absolute right-0 top-0 -mr-3 -mt-3 flex size-9 items-center justify-center rounded-full border shadow outline-none backdrop-blur" data-tw-dismiss="modal" href="#">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="x" class="lucide lucide-x stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 size-5 opacity-70"><path d="M18 6 6 18"></path><path d="m6 6 12 12"></path></svg>
            </a>
            <div class="-mx-3 pb-5">
                <div class="tns-outer" id="tns2-ow"><div class="tns-nav" aria-label="Carousel Pagination"><button type="button" data-nav="0" aria-controls="tns2" style="" aria-label="Carousel Page 1" class="" tabindex="-1"></button><button type="button" data-nav="1" aria-controls="tns2" style="" aria-label="Carousel Page 2 (Current Slide)" class="tns-nav-active"></button></div><button type="button" data-action="stop"><span class="tns-visually-hidden">stop animation</span>stop</button><div class="tns-liveregion tns-visually-hidden" aria-live="polite" aria-atomic="true">slide <span class="current">4</span>  of 2</div><div id="tns2-mw" class="tns-ovh"><div class="tns-inner" id="tns2-iw"><div data-config="{
    nav: true
}" class="tiny-slider mb-11 mt-2  tns-slider tns-carousel tns-subpixel tns-calc tns-horizontal" id="tns2" style="transform: translate3d(-50%, 0px, 0px); transition-duration: 0s;"><div class="relative mx-3 flex flex-col items-center gap-1 px-3.5 tns-item tns-slide-cloned" aria-hidden="true" tabindex="-1">
                        <div class="w-full bg-primary/[.05] mb-7 border-primary/10 shadow-lg shadow-black/10 relative rounded-3xl border h-52 overflow-hidden before:bg-noise before:absolute before:inset-0 before:opacity-30 after:bg-accent after:absolute after:inset-0 after:opacity-30 after:blur-2xl">
                            <img class="absolute inset-0 mx-auto mt-10 w-2/5 scale-125" src="dist/images/phone-illustration.svg" alt="Midone - Tailwind Admin Dashboard Template">
                        </div>
                        <div class="px-8">
                            <div class="text-center text-xl font-medium">Welcome to Midone Admin!</div>
                            <div class="mt-3 text-center text-base leading-relaxed opacity-70">
                                Premium admin dashboard template for all kinds <br> of projects.
                                With a unique and modern design, Midone offers the perfect foundation to build professional
                                applications with ease.
                            </div>
                        </div>
                        <div class="absolute inset-x-0 bottom-0 -mb-12 flex place-content-between px-5">
                            <a class="text-danger flex items-center gap-3 font-medium" data-tw-dismiss="modal" href="">
                                Skip Intro
                            </a>
                            <a class="text-primary flex items-center gap-3 font-medium" href="">
                                Next <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="move-right" class="lucide lucide-move-right size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25"><path d="M18 8L22 12L18 16"></path><path d="M2 12H22"></path></svg>
                            </a>
                        </div>
                    </div><div class="relative mx-3 flex flex-col items-center gap-1 px-3.5 tns-item tns-slide-cloned" aria-hidden="true" tabindex="-1">
                        <div class="w-full bg-primary/[.05] mb-7 border-primary/10 shadow-lg shadow-black/10 relative rounded-3xl border h-52 overflow-hidden before:bg-noise before:absolute before:inset-0 before:opacity-30 after:bg-accent after:absolute after:inset-0 after:opacity-30 after:blur-2xl">
                            <img class="absolute inset-0 mx-auto mt-10 w-2/5 scale-125" src="dist/images/woman-illustration.svg" alt="Midone - Tailwind Admin Dashboard Template">
                        </div>
                        <div class="w-full">
                            <div class="text-center text-xl font-medium">Example Request Information</div>
                            <div class="mt-3 text-center text-base leading-relaxed opacity-70">
                                Your premium admin dashboard template.
                            </div>
                            <div class="mt-8">
                                <div class="grid grid-cols-2 gap-5 px-5">
                                    <div class="flex flex-col gap-2.5"><label class="font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Full Name</label>
                                        <input type="text" placeholder="John Doe" class="h-10 w-full rounded-md border bg-background px-3 py-2 ring-offset-background file:border-0 file:bg-transparent file:font-medium file:text-foreground placeholder:text-foreground/70 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                                    </div>
                                    <div class="flex flex-col gap-2.5"><label class="font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Event</label>
                                        <select class="bg-(image:--background-image-chevron) bg-[position:calc(100%-theme(spacing.3))_center] bg-[size:theme(spacing.5)] bg-no-repeat relative appearance-none flex h-10 w-full rounded-md border bg-background px-3 py-2 ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                                            <option>Corporate Event</option>
                                            <option>Wedding</option>
                                            <option>Birthday</option>
                                            <option>Other</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="absolute inset-x-0 bottom-0 -mb-12 flex place-content-between px-5">
                            <a class="text-primary flex items-center gap-3 font-medium" href="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="move-left" class="lucide lucide-move-left size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25"><path d="M6 8L2 12L6 16"></path><path d="M2 12H22"></path></svg>
                                Previous
                            </a>
                        </div>
                    </div>
                </div>
                <!-- END: Weekly Top Products -->
        </div>
        <div class="col-span-12 2xl:col-span-3">
            <div class="-mb-10 h-full pb-10 2xl:border-l">
                <div class="grid grid-cols-12 gap-x-6 gap-y-6 2xl:gap-x-0 2xl:pl-6">
                    <!-- BEGIN: Transactions -->
                    @auth('students')
                    <!-- Hide Voting Transactions Section for Students -->
                    @else
                    <div class="col-span-12 mt-3 md:col-span-6 xl:col-span-4 2xl:col-span-12 2xl:mt-8">
                        <div class="flex h-10 items-center">
                            <h2 class="me-5 truncate text-lg font-medium">Voting Transactions</h2>
                        </div>
                        <div class="mt-5">
                            @forelse($votingTransactions as $transaction)
                            <div class="box relative p-5 before:absolute before:inset-0 before:mx-3 before:-mb-3 before:border before:border-foreground/10 before:bg-background/30 before:shadow-[0px_3px_5px_#0000000b] before:z-[-1] before:rounded-xl after:absolute after:inset-0 after:border after:border-foreground/10 after:bg-background after:shadow-[0px_3px_5px_#0000000b] after:rounded-xl after:z-[-1] after:backdrop-blur-md mb-3 flex items-center px-5 py-3 before:hidden">
                                    <span data-content="" class="tooltip border-(--color)/5 block relative size-11 flex-none overflow-hidden rounded-full border-3 ring-1 ring-(--color)/25 [--color:var(--color-success)]">
                                        @php
                                            $imageSrc = asset('images/placeholders/placeholder.jpg'); // Default placeholder
                                            
                                            // Check profile_image field first (profile_pictures folder)
                                            if ($transaction->student && $transaction->student->profile_image) {
                                                if (file_exists(public_path('storage/' . $transaction->student->profile_image))) {
                                                    $imageSrc = asset('storage/' . $transaction->student->profile_image);
                                                }
                                            }
                                            
                                            // If no profile_image or file doesn't exist, check student_images directory
                                            if ($imageSrc === asset('images/placeholders/placeholder.jpg') && $transaction->student) {
                                                $studentImagesPath = storage_path('app/public/student_images/');
                                                
                                                // Look for profile images with student ID
                                                $profilePattern = $studentImagesPath . '*_profile_*' . $transaction->student->id . '*';
                                                $profileFiles = glob($profilePattern);
                                                
                                                if (!empty($profileFiles)) {
                                                    $profileFile = basename($profileFiles[0]);
                                                    $imageSrc = asset('storage/student_images/' . $profileFile);
                                                } else {
                                                    // Look for ID images as fallback
                                                    $idPattern = $studentImagesPath . '*_id_*' . $transaction->student->id . '*';
                                                    $idFiles = glob($idPattern);
                                                    
                                                    if (!empty($idFiles)) {
                                                        $idFile = basename($idFiles[0]);
                                                        $imageSrc = asset('storage/student_images/' . $idFile);
                                                    }
                                                }
                                            }
                                        @endphp
                                        @if($imageSrc !== asset('images/placeholders/placeholder.jpg'))
                                            <img class="absolute top-0 size-full object-cover" src="{{ $imageSrc }}" alt="{{ $transaction->student->first_name ?? 'Unknown' }} {{ $transaction->student->last_name ?? 'Student' }}">
                                        @else
                                            <div class="absolute top-0 size-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-semibold text-sm">
                                                {{ strtoupper(substr($transaction->student->first_name ?? 'U', 0, 1)) }}{{ strtoupper(substr($transaction->student->last_name ?? 'N', 0, 1)) }}
                                    </div>
                                        @endif
                                </span>
                                <div class="me-auto ms-4">
                                        <div class="font-medium">{{ $transaction->student->first_name ?? 'Unknown' }} {{ $transaction->student->last_name ?? 'Student' }}</div>
                                    <div class="mt-1 text-xs opacity-70">
                                            {{ $transaction->created_at->format('M d, Y g:i A') }}
                                            @if($transaction->student && $transaction->student->course)
                                                • {{ $transaction->student->course->course_name }}
                                            @endif
                            </div>
                                    </div>
                                    <div class="text-success flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1">
                                            <path d="M9 12l2 2 4-4"></path>
                                            <path d="M21 12c.552 0 1-.448 1-1V8c0-.552-.448-1-1-1h-3.5l-1-1h-5l-1 1H3c-.552 0-1 .448-1 1v3c0 .552.448 1 1 1h18z"></path>
                                        </svg>
                                        Voted
                                    </div>
                                </div>
                            @empty
                                <div class="box relative p-8 before:absolute before:inset-0 before:mx-3 before:-mb-3 before:border before:border-foreground/10 before:bg-background/30 before:shadow-[0px_3px_5px_#0000000b] before:z-[-1] before:rounded-xl after:absolute after:inset-0 after:border after:border-foreground/10 after:bg-background after:shadow-[0px_3px_5px_#0000000b] after:rounded-xl after:z-[-1] after:backdrop-blur-md text-center">
                                    <div class="text-gray-500">
                                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <h3 class="text-sm font-medium text-gray-900 mb-1">No Voting Transactions</h3>
                                        <p class="text-sm text-gray-500">No voting activity has been recorded yet.</p>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                    @endauth
                </div>
            </div>
        </div>
   
    <div data-tw-backdrop="" class="modal group bg-black/60 transition-[visibility,opacity] w-screen h-screen fixed left-0 top-0 [&amp;:not(.show)]:duration-[0s,0.2s] [&amp;:not(.show)]:delay-[0.2s,0s] [&amp;:not(.show)]:invisible [&amp;:not(.show)]:opacity-0 [&amp;.show]:visible [&amp;.show]:opacity-100 [&amp;.show]:duration-[0s,0.4s]" id="onboarding-dialog" aria-hidden="true">
        <div class="box relative before:absolute before:inset-0 before:mx-3 before:-mb-3 before:border before:border-foreground/10 before:z-[-1] after:absolute after:inset-0 after:border after:border-foreground/10 after:bg-background after:shadow-[0px_3px_5px_#0000000b] after:z-[-1] after:backdrop-blur-md before:bg-background/60 dark:before:shadow-background before:shadow-foreground/60 z-50 mx-auto -mt-16 p-6 transition-[margin-top,transform] duration-[0.4s,0.3s] before:rounded-3xl before:shadow-2xl after:rounded-3xl group-[.show]:mt-16 group-[.modal-static]:scale-[1.05] sm:max-w-xl">
            <a class="bg-background/80 hover:bg-background absolute right-0 top-0 -mr-3 -mt-3 flex size-9 items-center justify-center rounded-full border shadow outline-none backdrop-blur" data-tw-dismiss="modal" href="#">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="x" class="lucide lucide-x stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 size-5 opacity-70"><path d="M18 6 6 18"></path><path d="m6 6 12 12"></path></svg>
            </a>
            <div class="-mx-3 pb-5">
                <div class="tns-outer" id="tns2-ow"><div class="tns-nav" aria-label="Carousel Pagination"><button type="button" data-nav="0" aria-controls="tns2" style="" aria-label="Carousel Page 1" class="" tabindex="-1"></button><button type="button" data-nav="1" aria-controls="tns2" style="" aria-label="Carousel Page 2 (Current Slide)" class="tns-nav-active"></button></div><button type="button" data-action="stop"><span class="tns-visually-hidden">stop animation</span>stop</button><div class="tns-liveregion tns-visually-hidden" aria-live="polite" aria-atomic="true">slide <span class="current">4</span>  of 2</div><div id="tns2-mw" class="tns-ovh"><div class="tns-inner" id="tns2-iw"><div data-config="{
    nav: true
}" class="tiny-slider mb-11 mt-2  tns-slider tns-carousel tns-subpixel tns-calc tns-horizontal" id="tns2" style="transform: translate3d(-50%, 0px, 0px); transition-duration: 0s;"><div class="relative mx-3 flex flex-col items-center gap-1 px-3.5 tns-item tns-slide-cloned" aria-hidden="true" tabindex="-1">
                        <div class="w-full bg-primary/[.05] mb-7 border-primary/10 shadow-lg shadow-black/10 relative rounded-3xl border h-52 overflow-hidden before:bg-noise before:absolute before:inset-0 before:opacity-30 after:bg-accent after:absolute after:inset-0 after:opacity-30 after:blur-2xl">
                            <img class="absolute inset-0 mx-auto mt-10 w-2/5 scale-125" src="dist/images/phone-illustration.svg" alt="Midone - Tailwind Admin Dashboard Template">
                        </div>
                        <div class="px-8">
                            <div class="text-center text-xl font-medium">Welcome to Midone Admin!</div>
                            <div class="mt-3 text-center text-base leading-relaxed opacity-70">
                                Premium admin dashboard template for all kinds <br> of projects.
                                With a unique and modern design, Midone offers the perfect foundation to build professional
                                applications with ease.
                            </div>
                        </div>
                        <div class="absolute inset-x-0 bottom-0 -mb-12 flex place-content-between px-5">
                            <a class="text-danger flex items-center gap-3 font-medium" data-tw-dismiss="modal" href="">
                                Skip Intro
                            </a>
                            <a class="text-primary flex items-center gap-3 font-medium" href="">
                                Next <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="move-right" class="lucide lucide-move-right size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25"><path d="M18 8L22 12L18 16"></path><path d="M2 12H22"></path></svg>
                            </a>
                        </div>
                    </div><div class="relative mx-3 flex flex-col items-center gap-1 px-3.5 tns-item tns-slide-cloned" aria-hidden="true" tabindex="-1">
                        <div class="w-full bg-primary/[.05] mb-7 border-primary/10 shadow-lg shadow-black/10 relative rounded-3xl border h-52 overflow-hidden before:bg-noise before:absolute before:inset-0 before:opacity-30 after:bg-accent after:absolute after:inset-0 after:opacity-30 after:blur-2xl">
                            <img class="absolute inset-0 mx-auto mt-10 w-2/5 scale-125" src="dist/images/woman-illustration.svg" alt="Midone - Tailwind Admin Dashboard Template">
                        </div>
                        <div class="w-full">
                            <div class="text-center text-xl font-medium">Example Request Information</div>
                            <div class="mt-3 text-center text-base leading-relaxed opacity-70">
                                Your premium admin dashboard template.
                            </div>
                            <div class="mt-8">
                                <div class="grid grid-cols-2 gap-5 px-5">
                                    <div class="flex flex-col gap-2.5"><label class="font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Full Name</label>
                                        <input type="text" placeholder="John Doe" class="h-10 w-full rounded-md border bg-background px-3 py-2 ring-offset-background file:border-0 file:bg-transparent file:font-medium file:text-foreground placeholder:text-foreground/70 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                                    </div>
                                    <div class="flex flex-col gap-2.5"><label class="font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Event</label>
                                        <select class="bg-(image:--background-image-chevron) bg-[position:calc(100%-theme(spacing.3))_center] bg-[size:theme(spacing.5)] bg-no-repeat relative appearance-none flex h-10 w-full rounded-md border bg-background px-3 py-2 ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                                            <option>Corporate Event</option>
                                            <option>Wedding</option>
                                            <option>Birthday</option>
                                            <option>Other</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="absolute inset-x-0 bottom-0 -mb-12 flex place-content-between px-5">
                            <a class="text-primary flex items-center gap-3 font-medium" href="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="move-left" class="lucide lucide-move-left size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25"><path d="M6 8L2 12L6 16"></path><path d="M2 12H22"></path></svg>
                                Previous
                            </a>
                        </div>
                    </div>
                </div>
                <!-- END: Weekly Top Products -->
        </div>
        <div class="col-span-12 2xl:col-span-3">
            <div class="-mb-10 h-full pb-10 2xl:border-l">
                <div class="grid grid-cols-12 gap-x-6 gap-y-6 2xl:gap-x-0 2xl:pl-6">
                    <!-- BEGIN: Transactions -->
                    @auth('students')
                    <!-- Hide Voting Transactions Section for Students -->
                    @else
                    <div class="col-span-12 mt-3 md:col-span-6 xl:col-span-4 2xl:col-span-12 2xl:mt-8">
                        <div class="flex h-10 items-center">
                            <h2 class="me-5 truncate text-lg font-medium">Voting Transactions</h2>
                        </div>
                        <div class="mt-5">
                            @forelse($votingTransactions as $transaction)
                            <div class="box relative p-5 before:absolute before:inset-0 before:mx-3 before:-mb-3 before:border before:border-foreground/10 before:bg-background/30 before:shadow-[0px_3px_5px_#0000000b] before:z-[-1] before:rounded-xl after:absolute after:inset-0 after:border after:border-foreground/10 after:bg-background after:shadow-[0px_3px_5px_#0000000b] after:rounded-xl after:z-[-1] after:backdrop-blur-md mb-3 flex items-center px-5 py-3 before:hidden">
                                    <span data-content="" class="tooltip border-(--color)/5 block relative size-11 flex-none overflow-hidden rounded-full border-3 ring-1 ring-(--color)/25 [--color:var(--color-success)]">
                                        @php
                                            $imageSrc = asset('images/placeholders/placeholder.jpg'); // Default placeholder
                                            
                                            // Check profile_image field first (profile_pictures folder)
                                            if ($transaction->student && $transaction->student->profile_image) {
                                                if (file_exists(public_path('storage/' . $transaction->student->profile_image))) {
                                                    $imageSrc = asset('storage/' . $transaction->student->profile_image);
                                                }
                                            }
                                            
                                            // If no profile_image or file doesn't exist, check student_images directory
                                            if ($imageSrc === asset('images/placeholders/placeholder.jpg') && $transaction->student) {
                                                $studentImagesPath = storage_path('app/public/student_images/');
                                                
                                                // Look for profile images with student ID
                                                $profilePattern = $studentImagesPath . '*_profile_*' . $transaction->student->id . '*';
                                                $profileFiles = glob($profilePattern);
                                                
                                                if (!empty($profileFiles)) {
                                                    $profileFile = basename($profileFiles[0]);
                                                    $imageSrc = asset('storage/student_images/' . $profileFile);
                                                } else {
                                                    // Look for ID images as fallback
                                                    $idPattern = $studentImagesPath . '*_id_*' . $transaction->student->id . '*';
                                                    $idFiles = glob($idPattern);
                                                    
                                                    if (!empty($idFiles)) {
                                                        $idFile = basename($idFiles[0]);
                                                        $imageSrc = asset('storage/student_images/' . $idFile);
                                                    }
                                                }
                                            }
                                        @endphp
                                        @if($imageSrc !== asset('images/placeholders/placeholder.jpg'))
                                            <img class="absolute top-0 size-full object-cover" src="{{ $imageSrc }}" alt="{{ $transaction->student->first_name ?? 'Unknown' }} {{ $transaction->student->last_name ?? 'Student' }}">
                                        @else
                                            <div class="absolute top-0 size-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-semibold text-sm">
                                                {{ strtoupper(substr($transaction->student->first_name ?? 'U', 0, 1)) }}{{ strtoupper(substr($transaction->student->last_name ?? 'N', 0, 1)) }}
                                    </div>
                                        @endif
                                </span>
                                <div class="me-auto ms-4">
                                        <div class="font-medium">{{ $transaction->student->first_name ?? 'Unknown' }} {{ $transaction->student->last_name ?? 'Student' }}</div>
                                    <div class="mt-1 text-xs opacity-70">
                                            {{ $transaction->created_at->format('M d, Y g:i A') }}
                                            @if($transaction->student && $transaction->student->course)
                                                • {{ $transaction->student->course->course_name }}
                                            @endif
                            </div>
                                    </div>
                                    <div class="text-success flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1">
                                            <path d="M9 12l2 2 4-4"></path>
                                            <path d="M21 12c.552 0 1-.448 1-1V8c0-.552-.448-1-1-1h-3.5l-1-1h-5l-1 1H3c-.552 0-1 .448-1 1v3c0 .552.448 1 1 1h18z"></path>
                                        </svg>
                                        Voted
                                    </div>
                                </div>
                            @empty
                                <div class="box relative p-8 before:absolute before:inset-0 before:mx-3 before:-mb-3 before:border before:border-foreground/10 before:bg-background/30 before:shadow-[0px_3px_5px_#0000000b] before:z-[-1] before:rounded-xl after:absolute after:inset-0 after:border after:border-foreground/10 after:bg-background after:shadow-[0px_3px_5px_#0000000b] after:rounded-xl after:z-[-1] after:backdrop-blur-md text-center">
                                    <div class="text-gray-500">
                                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <h3 class="text-sm font-medium text-gray-900 mb-1">No Voting Transactions</h3>
                                        <p class="text-sm text-gray-500">No voting activity has been recorded yet.</p>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                    @endauth
                </div>
            </div>
        </div>
   
    <div data-tw-backdrop="" class="modal group bg-black/60 transition-[visibility,opacity] w-screen h-screen fixed left-0 top-0 [&amp;:not(.show)]:duration-[0s,0.2s] [&amp;:not(.show)]:delay-[0.2s,0s] [&amp;:not(.show)]:invisible [&amp;:not(.show)]:opacity-0 [&amp;.show]:visible [&amp;.show]:opacity-100 [&amp;.show]:duration-[0s,0.4s]" id="onboarding-dialog" aria-hidden="true">
        <div class="box relative before:absolute before:inset-0 before:mx-3 before:-mb-3 before:border before:border-foreground/10 before:z-[-1] after:absolute after:inset-0 after:border after:border-foreground/10 after:bg-background after:shadow-[0px_3px_5px_#0000000b] after:z-[-1] after:backdrop-blur-md before:bg-background/60 dark:before:shadow-background before:shadow-foreground/60 z-50 mx-auto -mt-16 p-6 transition-[margin-top,transform] duration-[0.4s,0.3s] before:rounded-3xl before:shadow-2xl after:rounded-3xl group-[.show]:mt-16 group-[.modal-static]:scale-[1.05] sm:max-w-xl">
            <a class="bg-background/80 hover:bg-background absolute right-0 top-0 -mr-3 -mt-3 flex size-9 items-center justify-center rounded-full border shadow outline-none backdrop-blur" data-tw-dismiss="modal" href="#">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="x" class="lucide lucide-x stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 size-5 opacity-70"><path d="M18 6 6 18"></path><path d="m6 6 12 12"></path></svg>
            </a>
            <div class="-mx-3 pb-5">
                <div class="tns-outer" id="tns2-ow"><div class="tns-nav" aria-label="Carousel Pagination"><button type="button" data-nav="0" aria-controls="tns2" style="" aria-label="Carousel Page 1" class="" tabindex="-1"></button><button type="button" data-nav="1" aria-controls="tns2" style="" aria-label="Carousel Page 2 (Current Slide)" class="tns-nav-active"></button></div><button type="button" data-action="stop"><span class="tns-visually-hidden">stop animation</span>stop</button><div class="tns-liveregion tns-visually-hidden" aria-live="polite" aria-atomic="true">slide <span class="current">4</span>  of 2</div><div id="tns2-mw" class="tns-ovh"><div class="tns-inner" id="tns2-iw"><div data-config="{
    nav: true
}" class="tiny-slider mb-11 mt-2  tns-slider tns-carousel tns-subpixel tns-calc tns-horizontal" id="tns2" style="transform: translate3d(-50%, 0px, 0px); transition-duration: 0s;"><div class="relative mx-3 flex flex-col items-center gap-1 px-3.5 tns-item tns-slide-cloned" aria-hidden="true" tabindex="-1">
                        <div class="w-full bg-primary/[.05] mb-7 border-primary/10 shadow-lg shadow-black/10 relative rounded-3xl border h-52 overflow-hidden before:bg-noise before:absolute before:inset-0 before:opacity-30 after:bg-accent after:absolute after:inset-0 after:opacity-30 after:blur-2xl">
                            <img class="absolute inset-0 mx-auto mt-10 w-2/5 scale-125" src="dist/images/phone-illustration.svg" alt="Midone - Tailwind Admin Dashboard Template">
                        </div>
                        <div class="px-8">
                            <div class="text-center text-xl font-medium">Welcome to Midone Admin!</div>
                            <div class="mt-3 text-center text-base leading-relaxed opacity-70">
                                Premium admin dashboard template for all kinds <br> of projects.
                                With a unique and modern design, Midone offers the perfect foundation to build professional
                                applications with ease.
                            </div>
                        </div>
                        <div class="absolute inset-x-0 bottom-0 -mb-12 flex place-content-between px-5">
                            <a class="text-danger flex items-center gap-3 font-medium" data-tw-dismiss="modal" href="">
                                Skip Intro
                            </a>
                            <a class="text-primary flex items-center gap-3 font-medium" href="">
                                Next <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="move-right" class="lucide lucide-move-right size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25"><path d="M18 8L22 12L18 16"></path><path d="M2 12H22"></path></svg>
                            </a>
                        </div>
                    </div><div class="relative mx-3 flex flex-col items-center gap-1 px-3.5 tns-item tns-slide-cloned" aria-hidden="true" tabindex="-1">
                        <div class="w-full bg-primary/[.05] mb-7 border-primary/10 shadow-lg shadow-black/10 relative rounded-3xl border h-52 overflow-hidden before:bg-noise before:absolute before:inset-0 before:opacity-30 after:bg-accent after:absolute after:inset-0 after:opacity-30 after:blur-2xl">
                            <img class="absolute inset-0 mx-auto mt-10 w-2/5 scale-125" src="dist/images/woman-illustration.svg" alt="Midone - Tailwind Admin Dashboard Template">
                        </div>
                        <div class="w-full">
                            <div class="text-center text-xl font-medium">Example Request Information</div>
                            <div class="mt-3 text-center text-base leading-relaxed opacity-70">
                                Your premium admin dashboard template.
                            </div>
                            <div class="mt-8">
                                <div class="grid grid-cols-2 gap-5 px-5">
                                    <div class="flex flex-col gap-2.5"><label class="font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Full Name</label>
                                        <input type="text" placeholder="John Doe" class="h-10 w-full rounded-md border bg-background px-3 py-2 ring-offset-background file:border-0 file:bg-transparent file:font-medium file:text-foreground placeholder:text-foreground/70 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                                    </div>
                                    <div class="flex flex-col gap-2.5"><label class="font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Event</label>
                                        <select class="bg-(image:--background-image-chevron) bg-[position:calc(100%-theme(spacing.3))_center] bg-[size:theme(spacing.5)] bg-no-repeat relative appearance-none flex h-10 w-full rounded-md border bg-background px-3 py-2 ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                                            <option>Corporate Event</option>
                                            <option>Wedding</option>
                                            <option>Birthday</option>
                                            <option>Other</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="absolute inset-x-0 bottom-0 -mb-12 flex place-content-between px-5">
                            <a class="text-primary flex items-center gap-3 font-medium" href="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="move-left" class="lucide lucide-move-left size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25"><path d="M6 8L2 12L6 16"></path><path d="M2 12H22"></path></svg>
                                Previous
                            </a>
                        </div>
                    </div>
                </div>
                <!-- END: Weekly Top Products -->
        </div>
        <div class="col-span-12 2xl:col-span-3">
            <div class="-mb-10 h-full pb-10 2xl:border-l">
                <div class="grid grid-cols-12 gap-x-6 gap-y-6 2xl:gap-x-0 2xl:pl-6">
                    <!-- BEGIN: Transactions -->
                    @auth('students')
                    <!-- Hide Voting Transactions Section for Students -->
                    @else
                    <div class="col-span-12 mt-3 md:col-span-6 xl:col-span-4 2xl:col-span-12 2xl:mt-8">
                        <div class="flex h-10 items-center">
                            <h2 class="me-5 truncate text-lg font-medium">Voting Transactions</h2>
                        </div>
                        <div class="mt-5">
                            @forelse($votingTransactions as $transaction)
                            <div class="box relative p-5 before:absolute before:inset-0 before:mx-3 before:-mb-3 before:border before:border-foreground/10 before:bg-background/30 before:shadow-[0px_3px_5px_#0000000b] before:z-[-1] before:rounded-xl after:absolute after:inset-0 after:border after:border-foreground/10 after:bg-background after:shadow-[0px_3px_5px_#0000000b] after:rounded-xl after:z-[-1] after:backdrop-blur-md mb-3 flex items-center px-5 py-3 before:hidden">
                                    <span data-content="" class="tooltip border-(--color)/5 block relative size-11 flex-none overflow-hidden rounded-full border-3 ring-1 ring-(--color)/25 [--color:var(--color-success)]">
                                        @php
                                            $imageSrc = asset('images/placeholders/placeholder.jpg'); // Default placeholder
                                            
                                            // Check profile_image field first (profile_pictures folder)
                                            if ($transaction->student && $transaction->student->profile_image) {
                                                if (file_exists(public_path('storage/' . $transaction->student->profile_image))) {
                                                    $imageSrc = asset('storage/' . $transaction->student->profile_image);
                                                }
                                            }
                                            
                                            // If no profile_image or file doesn't exist, check student_images directory
                                            if ($imageSrc === asset('images/placeholders/placeholder.jpg') && $transaction->student) {
                                                $studentImagesPath = storage_path('app/public/student_images/');
                                                
                                                // Look for profile images with student ID
                                                $profilePattern = $studentImagesPath . '*_profile_*' . $transaction->student->id . '*';
                                                $profileFiles = glob($profilePattern);
                                                
                                                if (!empty($profileFiles)) {
                                                    $profileFile = basename($profileFiles[0]);
                                                    $imageSrc = asset('storage/student_images/' . $profileFile);
                                                } else {
                                                    // Look for ID images as fallback
                                                    $idPattern = $studentImagesPath . '*_id_*' . $transaction->student->id . '*';
                                                    $idFiles = glob($idPattern);
                                                    
                                                    if (!empty($idFiles)) {
                                                        $idFile = basename($idFiles[0]);
                                                        $imageSrc = asset('storage/student_images/' . $idFile);
                                                    }
                                                }
                                            }
                                        @endphp
                                        @if($imageSrc !== asset('images/placeholders/placeholder.jpg'))
                                            <img class="absolute top-0 size-full object-cover" src="{{ $imageSrc }}" alt="{{ $transaction->student->first_name ?? 'Unknown' }} {{ $transaction->student->last_name ?? 'Student' }}">
                                        @else
                                            <div class="absolute top-0 size-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-semibold text-sm">
                                                {{ strtoupper(substr($transaction->student->first_name ?? 'U', 0, 1)) }}{{ strtoupper(substr($transaction->student->last_name ?? 'N', 0, 1)) }}
                                    </div>
                                        @endif
                                </span>
                                <div class="me-auto ms-4">
                                        <div class="font-medium">{{ $transaction->student->first_name ?? 'Unknown' }} {{ $transaction->student->last_name ?? 'Student' }}</div>
                                    <div class="mt-1 text-xs opacity-70">
                                            {{ $transaction->created_at->format('M d, Y g:i A') }}
                                            @if($transaction->student && $transaction->student->course)
                                                • {{ $transaction->student->course->course_name }}
                                            @endif
                            </div>
                                    </div>
                                    <div class="text-success flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1">
                                            <path d="M9 12l2 2 4-4"></path>
                                            <path d="M21 12c.552 0 1-.448 1-1V8c0-.552-.448-1-1-1h-3.5l-1-1h-5l-1 1H3c-.552 0-1 .448-1 1v3c0 .552.448 1 1 1h18z"></path>
                                        </svg>
                                        Voted
                                    </div>
                                </div>
                            @empty
                                <div class="box relative p-8 before:absolute before:inset-0 before:mx-3 before:-mb-3 before:border before:border-foreground/10 before:bg-background/30 before:shadow-[0px_3px_5px_#0000000b] before:z-[-1] before:rounded-xl after:absolute after:inset-0 after:border after:border-foreground/10 after:bg-background after:shadow-[0px_3px_5px_#0000000b] after:rounded-xl after:z-[-1] after:backdrop-blur-md text-center">
                                    <div class="text-gray-500">
                                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <h3 class="text-sm font-medium text-gray-900 mb-1">No Voting Transactions</h3>
                                        <p class="text-sm text-gray-500">No voting activity has been recorded yet.</p>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                    @endauth
                </div>
            </div>
        </div>
   
    <div data-tw-backdrop="" class="modal group bg-black/60 transition-[visibility,opacity] w-screen h-screen fixed left-0 top-0 [&amp;:not(.show)]:duration-[0s,0.2s] [&amp;:not(.show)]:delay-[0.2s,0s] [&amp;:not(.show)]:invisible [&amp;:not(.show)]:opacity-0 [&amp;.show]:visible [&amp;.show]:opacity-100 [&amp;.show]:duration-[0s,0.4s]" id="onboarding-dialog" aria-hidden="true">
        <div class="box relative before:absolute before:inset-0 before:mx-3 before:-mb-3 before:border before:border-foreground/10 before:z-[-1] after:absolute after:inset-0 after:border after:border-foreground/10 after:bg-background after:shadow-[0px_3px_5px_#0000000b] after:z-[-1] after:backdrop-blur-md before:bg-background/60 dark:before:shadow-background before:shadow-foreground/60 z-50 mx-auto -mt-16 p-6 transition-[margin-top,transform] duration-[0.4s,0.3s] before:rounded-3xl before:shadow-2xl after:rounded-3xl group-[.show]:mt-16 group-[.modal-static]:scale-[1.05] sm:max-w-xl">
            <a class="bg-background/80 hover:bg-background absolute right-0 top-0 -mr-3 -mt-3 flex size-9 items-center justify-center rounded-full border shadow outline-none backdrop-blur" data-tw-dismiss="modal" href="#">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="x" class="lucide lucide-x stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 size-5 opacity-70"><path d="M18 6 6 18"></path><path d="m6 6 12 12"></path></svg>
            </a>
            <div class="-mx-3 pb-5">
                <div class="tns-outer" id="tns2-ow"><div class="tns-nav" aria-label="Carousel Pagination"><button type="button" data-nav="0" aria-controls="tns2" style="" aria-label="Carousel Page 1" class="" tabindex="-1"></button><button type="button" data-nav="1" aria-controls="tns2" style="" aria-label="Carousel Page 2 (Current Slide)" class="tns-nav-active"></button></div><button type="button" data-action="stop"><span class="tns-visually-hidden">stop animation</span>stop</button><div class="tns-liveregion tns-visually-hidden" aria-live="polite" aria-atomic="true">slide <span class="current">4</span>  of 2</div><div id="tns2-mw" class="tns-ovh"><div class="tns-inner" id="tns2-iw"><div data-config="{
    nav: true
}" class="tiny-slider mb-11 mt-2  tns-slider tns-carousel tns-subpixel tns-calc tns-horizontal" id="tns2" style="transform: translate3d(-50%, 0px, 0px); transition-duration: 0s;"><div class="relative mx-3 flex flex-col items-center gap-1 px-3.5 tns-item tns-slide-cloned" aria-hidden="true" tabindex="-1">
                        <div class="w-full bg-primary/[.05] mb-7 border-primary/10 shadow-lg shadow-black/10 relative rounded-3xl border h-52 overflow-hidden before:bg-noise before:absolute before:inset-0 before:opacity-30 after:bg-accent after:absolute after:inset-0 after:opacity-30 after:blur-2xl">
                            <img class="absolute inset-0 mx-auto mt-10 w-2/5 scale-125" src="dist/images/phone-illustration.svg" alt="Midone - Tailwind Admin Dashboard Template">
                        </div>
                        <div class="px-8">
                            <div class="text-center text-xl font-medium">Welcome to Midone Admin!</div>
                            <div class="mt-3 text-center text-base leading-relaxed opacity-70">
                                Premium admin dashboard template for all kinds <br> of projects.
                                With a unique and modern design, Midone offers the perfect foundation to build professional
                                applications with ease.
                            </div>
                        </div>
                        <div class="absolute inset-x-0 bottom-0 -mb-12 flex place-content-between px-5">
                            <a class="text-danger flex items-center gap-3 font-medium" data-tw-dismiss="modal" href="">
                                Skip Intro
                            </a>
                            <a class="text-primary flex items-center gap-3 font-medium" href="">
                                Next <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="move-right" class="lucide lucide-move-right size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25"><path d="M18 8L22 12L18 16"></path><path d="M2 12H22"></path></svg>
                            </a>
                        </div>
                    </div><div class="relative mx-3 flex flex-col items-center gap-1 px-3.5 tns-item tns-slide-cloned" aria-hidden="true" tabindex="-1">
                        <div class="w-full bg-primary/[.05] mb-7 border-primary/10 shadow-lg shadow-black/10 relative rounded-3xl border h-52 overflow-hidden before:bg-noise before:absolute before:inset-0 before:opacity-30 after:bg-accent after:absolute after:inset-0 after:opacity-30 after:blur-2xl">
                            <img class="absolute inset-0 mx-auto mt-10 w-2/5 scale-125" src="dist/images/woman-illustration.svg" alt="Midone - Tailwind Admin Dashboard Template">
                        </div>
                        <div class="w-full">
                            <div class="text-center text-xl font-medium">Example Request Information</div>
                            <div class="mt-3 text-center text-base leading-relaxed opacity-70">
                                Your premium admin dashboard template.
                            </div>
                            <div class="mt-8">
                                <div class="grid grid-cols-2 gap-5 px-5">
                                    <div class="flex flex-col gap-2.5"><label class="font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Full Name</label>
                                        <input type="text" placeholder="John Doe" class="h-10 w-full rounded-md border bg-background px-3 py-2 ring-offset-background file:border-0 file:bg-transparent file:font-medium file:text-foreground placeholder:text-foreground/70 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                                    </div>
                                    <div class="flex flex-col gap-2.5"><label class="font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Event</label>
                                        <select class="bg-(image:--background-image-chevron) bg-[position:calc(100%-theme(spacing.3))_center] bg-[size:theme(spacing.5)] bg-no-repeat relative appearance-none flex h-10 w-full rounded-md border bg-background px-3 py-2 ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                                            <option>Corporate Event</option>
                                            <option>Wedding</option>
                                            <option>Birthday</option>
                                            <option>Other</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="absolute inset-x-0 bottom-0 -mb-12 flex place-content-between px-5">
                            <a class="text-primary flex items-center gap-3 font-medium" href="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="move-left" class="lucide lucide-move-left size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25"><path d="M6 8L2 12L6 16"></path><path d="M2 12H22"></path></svg>
                                Previous
                            </a>
                        </div>
                    </div>
                </div>
                <!-- END: Weekly Top Products -->
        </div>
        <div class="col-span-12 2xl:col-span-3">
            <div class="-mb-10 h-full pb-10 2xl:border-l">
                <div class="grid grid-cols-12 gap-x-6 gap-y-6 2xl:gap-x-0 2xl:pl-6">
                    <!-- BEGIN: Transactions -->
                    @auth('students')
                    <!-- Hide Voting Transactions Section for Students -->
                    @else
                    <div class="col-span-12 mt-3 md:col-span-6 xl:col-span-4 2xl:col-span-12 2xl:mt-8">
                        <div class="flex h-10 items-center">
                            <h2 class="me-5 truncate text-lg font-medium">Voting Transactions</h2>
                        </div>
                        <div class="mt-5">
                            @forelse($votingTransactions as $transaction)
                            <div class="box relative p-5 before:absolute before:inset-0 before:mx-3 before:-mb-3 before:border before:border-foreground/10 before:bg-background/30 before:shadow-[0px_3px_5px_#0000000b] before:z-[-1] before:rounded-xl after:absolute after:inset-0 after:border after:border-foreground/10 after:bg-background after:shadow-[0px_3px_5px_#0000000b] after:rounded-xl after:z-[-1] after:backdrop-blur-md mb-3 flex items-center px-5 py-3 before:hidden">
                                    <span data-content="" class="tooltip border-(--color)/5 block relative size-11 flex-none overflow-hidden rounded-full border-3 ring-1 ring-(--color)/25 [--color:var(--color-success)]">
                                        @php
                                            $imageSrc = asset('images/placeholders/placeholder.jpg'); // Default placeholder
                                            
                                            // Check profile_image field first (profile_pictures folder)
                                            if ($transaction->student && $transaction->student->profile_image) {
                                                if (file_exists(public_path('storage/' . $transaction->student->profile_image))) {
                                                    $imageSrc = asset('storage/' . $transaction->student->profile_image);
                                                }
                                            }
                                            
                                            // If no profile_image or file doesn't exist, check student_images directory
                                            if ($imageSrc === asset('images/placeholders/placeholder.jpg') && $transaction->student) {
                                                $studentImagesPath = storage_path('app/public/student_images/');
                                                
                                                // Look for profile images with student ID
                                                $profilePattern = $studentImagesPath . '*_profile_*' . $transaction->student->id . '*';
                                                $profileFiles = glob($profilePattern);
                                                
                                                if (!empty($profileFiles)) {
                                                    $profileFile = basename($profileFiles[0]);
                                                    $imageSrc = asset('storage/student_images/' . $profileFile);
                                                } else {
                                                    // Look for ID images as fallback
                                                    $idPattern = $studentImagesPath . '*_id_*' . $transaction->student->id . '*';
                                                    $idFiles = glob($idPattern);
                                                    
                                                    if (!empty($idFiles)) {
                                                        $idFile = basename($idFiles[0]);
                                                        $imageSrc = asset('storage/student_images/' . $idFile);
                                                    }
                                                }
                                            }
                                        @endphp
                                        @if($imageSrc !== asset('images/placeholders/placeholder.jpg'))
                                            <img class="absolute top-0 size-full object-cover" src="{{ $imageSrc }}" alt="{{ $transaction->student->first_name ?? 'Unknown' }} {{ $transaction->student->last_name ?? 'Student' }}">
                                        @else
                                            <div class="absolute top-0 size-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-semibold text-sm">
                                                {{ strtoupper(substr($transaction->student->first_name ?? 'U', 0, 1)) }}{{ strtoupper(substr($transaction->student->last_name ?? 'N', 0, 1)) }}
                                    </div>
                                        @endif
                                </span>
                                <div class="me-auto ms-4">
                                        <div class="font-medium">{{ $transaction->student->first_name ?? 'Unknown' }} {{ $transaction->student->last_name ?? 'Student' }}</div>
                                    <div class="mt-1 text-xs opacity-70">
                                            {{ $transaction->created_at->format('M d, Y g:i A') }}
                                            @if($transaction->student && $transaction->student->course)
                                                • {{ $transaction->student->course->course_name }}
                                            @endif
                            </div>
                                    </div>
                                    <div class="text-success flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1">
                                            <path d="M9 12l2 2 4-4"></path>
                                            <path d="M21 12c.552 0 1-.448 1-1V8c0-.552-.448-1-1-1h-3.5l-1-1h-5l-1 1H3c-.552 0-1 .448-1 1v3c0 .552.448 1 1 1h18z"></path>
                                        </svg>
                                        Voted
                                    </div>
                                </div>
                            @empty
                                <div class="box relative p-8 before:absolute before:inset-0 before:mx-3 before:-mb-3 before:border before:border-foreground/10 before:bg-background/30 before:shadow-[0px_3px_5px_#0000000b] before:z-[-1] before:rounded-xl after:absolute after:inset-0 after:border after:border-foreground/10 after:bg-background after:shadow-[0px_3px_5px_#0000000b] after:rounded-xl after:z-[-1] after:backdrop-blur-md text-center">
                                    <div class="text-gray-500">
                                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <h3 class="text-sm font-medium text-gray-900 mb-1">No Voting Transactions</h3>
                                        <p class="text-sm text-gray-500">No voting activity has been recorded yet.</p>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                    @endauth
                </div>
            </div>
        </div>
   
    <div data-tw-backdrop="" class="modal group bg-black/60 transition-[visibility,opacity] w-screen h-screen fixed left-0 top-0 [&amp;:not(.show)]:duration-[0s,0.2s] [&amp;:not(.show)]:delay-[0.2s,0s] [&amp;:not(.show)]:invisible [&amp;:not(.show)]:opacity-0 [&amp;.show]:visible [&amp;.show]:opacity-100 [&amp;.show]:duration-[0s,0.4s]" id="onboarding-dialog" aria-hidden="true">
        <div class="box relative before:absolute before:inset-0 before:mx-3 before:-mb-3 before:border before:border-foreground/10 before:z-[-1] after:absolute after:inset-0 after:border after:border-foreground/10 after:bg-background after:shadow-[0px_3px_5px_#0000000b] after:z-[-1] after:backdrop-blur-md before:bg-background/60 dark:before:shadow-background before:shadow-foreground/60 z-50 mx-auto -mt-16 p-6 transition-[margin-top,transform] duration-[0.4s,0.3s] before:rounded-3xl before:shadow-2xl after:rounded-3xl group-[.show]:mt-16 group-[.modal-static]:scale-[1.05] sm:max-w-xl">
            <a class="bg-background/80 hover:bg-background absolute right-0 top-0 -mr-3 -mt-3 flex size-9 items-center justify-center rounded-full border shadow outline-none backdrop-blur" data-tw-dismiss="modal" href="#">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="x" class="lucide lucide-x stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 size-5 opacity-70"><path d="M18 6 6 18"></path><path d="m6 6 12 12"></path></svg>
            </a>
            <div class="-mx-3 pb-5">
                <div class="tns-outer" id="tns2-ow"><div class="tns-nav" aria-label="Carousel Pagination"><button type="button" data-nav="0" aria-controls="tns2" style="" aria-label="Carousel Page 1" class="" tabindex="-1"></button><button type="button" data-nav="1" aria-controls="tns2" style="" aria-label="Carousel Page 2 (Current Slide)" class="tns-nav-active"></button></div><button type="button" data-action="stop"><span class="tns-visually-hidden">stop animation</span>stop</button><div class="tns-liveregion tns-visually-hidden" aria-live="polite" aria-atomic="true">slide <span class="current">4</span>  of 2</div><div id="tns2-mw" class="tns-ovh"><div class="tns-inner" id="tns2-iw"><div data-config="{
    nav: true
}" class="tiny-slider mb-11 mt-2  tns-slider tns-carousel tns-subpixel tns-calc tns-horizontal" id="tns2" style="transform: translate3d(-50%, 0px, 0px); transition-duration: 0s;"><div class="relative mx-3 flex flex-col items-center gap-1 px-3.5 tns-item tns-slide-cloned" aria-hidden="true" tabindex="-1">
                        <div class="w-full bg-primary/[.05] mb-7 border-primary/10 shadow-lg shadow-black/10 relative rounded-3xl border h-52 overflow-hidden before:bg-noise before:absolute before:inset-0 before:opacity-30 after:bg-accent after:absolute after:inset-0 after:opacity-30 after:blur-2xl">
                            <img class="absolute inset-0 mx-auto mt-10 w-2/5 scale-125" src="dist/images/phone-illustration.svg" alt="Midone - Tailwind Admin Dashboard Template">
                        </div>
                        <div class="px-8">
                            <div class="text-center text-xl font-medium">Welcome to Midone Admin!</div>
                            <div class="mt-3 text-center text-base leading-relaxed opacity-70">
                                Premium admin dashboard template for all kinds <br> of projects.
                                With a unique and modern design, Midone offers the perfect foundation to build professional
                                applications with ease.
                            </div>
                        </div>
                        <div class="absolute inset-x-0 bottom-0 -mb-12 flex place-content-between px-5">
                            <a class="text-danger flex items-center gap-3 font-medium" data-tw-dismiss="modal" href="">
                                Skip Intro
                            </a>
                            <a class="text-primary flex items-center gap-3 font-medium" href="">
                                Next <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="move-right" class="lucide lucide-move-right size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25"><path d="M18 8L22 12L18 16"></path><path d="M2 12H22"></path></svg>
                            </a>
                        </div>
                    </div><div class="relative mx-3 flex flex-col items-center gap-1 px-3.5 tns-item tns-slide-cloned" aria-hidden="true" tabindex="-1">
                        <div class="w-full bg-primary/[.05] mb-7 border-primary/10 shadow-lg shadow-black/10 relative rounded-3xl border h-52 overflow-hidden before:bg-noise before:absolute before:inset-0 before:opacity-30 after:bg-accent after:absolute after:inset-0 after:opacity-30 after:blur-2xl">
                            <img class="absolute inset-0 mx-auto mt-10 w-2/5 scale-125" src="dist/images/woman-illustration.svg" alt="Midone - Tailwind Admin Dashboard Template">
                        </div>
                        <div class="w-full">
                            <div class="text-center text-xl font-medium">Example Request Information</div>
                            <div class="mt-3 text-center text-base leading-relaxed opacity-70">
                                Your premium admin dashboard template.
                            </div>
                            <div class="mt-8">
                                <div class="grid grid-cols-2 gap-5 px-5">
                                    <div class="flex flex-col gap-2.5"><label class="font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Full Name</label>
                                        <input type="text" placeholder="John Doe" class="h-10 w-full rounded-md border bg-background px-3 py-2 ring-offset-background file:border-0 file:bg-transparent file:font-medium file:text-foreground placeholder:text-foreground/70 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                                    </div>
                                    <div class="flex flex-col gap-2.5"><label class="font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Event</label>
                                        <select class="bg-(image:--background-image-chevron) bg-[position:calc(100%-theme(spacing.3))_center] bg-[size:theme(spacing.5)] bg-no-repeat relative appearance-none flex h-10 w-full rounded-md border bg-background px-3 py-2 ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                                            <option>Corporate Event</option>
                                            <option>Wedding</option>
                                            <option>Birthday</option>
                                            <option>Other</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="absolute inset-x-0 bottom-0 -mb-12 flex place-content-between px-5">
                            <a class="text-primary flex items-center gap-3 font-medium" href="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="move-left" class="lucide lucide-move-left size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25"><path d="M6 8L2 12L6 16"></path><path d="M2 12H22"></path></svg>
                                Previous
                            </a>
                        </div>
                    </div>
                </div>
                <!-- END: Weekly Top Products -->
        </div>
        <div class="col-span-12 2xl:col-span-3">
            <div class="-mb-10 h-full pb-10 2xl:border-l">
                <div class="grid grid-cols-12 gap-x-6 gap-y-6 2xl:gap-x-0 2xl:pl-6">
                    <!-- BEGIN: Transactions -->
                    @auth('students')
                    <!-- Hide Voting Transactions Section for Students -->
                    @else
                    <div class="col-span-12 mt-3 md:col-span-6 xl:col-span-4 2xl:col-span-12 2xl:mt-8">
                        <div class="flex h-10 items-center">
                            <h2 class="me-5 truncate text-lg font-medium">Voting Transactions</h2>
                        </div>
                        <div class="mt-5">
                            @forelse($votingTransactions as $transaction)
                            <div class="box relative p-5 before:absolute before:inset-0 before:mx-3 before:-mb-3 before:border before:border-foreground/10 before:bg-background/30 before:shadow-[0px_3px_5px_#0000000b] before:z-[-1] before:rounded-xl after:absolute after:inset-0 after:border after:border-foreground/10 after:bg-background after:shadow-[0px_3px_5px_#0000000b] after:rounded-xl after:z-[-1] after:backdrop-blur-md mb-3 flex items-center px-5 py-3 before:hidden">
                                    <span data-content="" class="tooltip border-(--color)/5 block relative size-11 flex-none overflow-hidden rounded-full border-3 ring-1 ring-(--color)/25 [--color:var(--color-success)]">
                                        @php
                                            $imageSrc = asset('images/placeholders/placeholder.jpg'); // Default placeholder
                                            
                                            // Check profile_image field first (profile_pictures folder)
                                            if ($transaction->student && $transaction->student->profile_image) {
                                                if (file_exists(public_path('storage/' . $transaction->student->profile_image))) {
                                                    $imageSrc = asset('storage/' . $transaction->student->profile_image);
                                                }
                                            }
                                            
                                            // If no profile_image or file doesn't exist, check student_images directory
                                            if ($imageSrc === asset('images/placeholders/placeholder.jpg') && $transaction->student) {
                                                $studentImagesPath = storage_path('app/public/student_images/');
                                                
                                                // Look for profile images with student ID
                                                $profilePattern = $studentImagesPath . '*_profile_*' . $transaction->student->id . '*';
                                                $profileFiles = glob($profilePattern);
                                                
                                                if (!empty($profileFiles)) {
                                                    $profileFile = basename($profileFiles[0]);
                                                    $imageSrc = asset('storage/student_images/' . $profileFile);
                                                } else {
                                                    // Look for ID images as fallback
                                                    $idPattern = $studentImagesPath . '*_id_*' . $transaction->student->id . '*';
                                                    $idFiles = glob($idPattern);
                                                    
                                                    if (!empty($idFiles)) {
                                                        $idFile = basename($idFiles[0]);
                                                        $imageSrc = asset('storage/student_images/' . $idFile);
                                                    }
                                                }
                                            }
                                        @endphp
                                        @if($imageSrc !== asset('images/placeholders/placeholder.jpg'))
                                            <img class="absolute top-0 size-full object-cover" src="{{ $imageSrc }}" alt="{{ $transaction->student->first_name ?? 'Unknown' }} {{ $transaction->student->last_name ?? 'Student' }}">
                                        @else
                                            <div class="absolute top-0 size-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-semibold text-sm">
                                                {{ strtoupper(substr($transaction->student->first_name ?? 'U', 0, 1)) }}{{ strtoupper(substr($transaction->student->last_name ?? 'N', 0, 1)) }}
                                    </div>
                                        @endif
                                </span>
                                <div class="me-auto ms-4">
                                        <div class="font-medium">{{ $transaction->student->first_name ?? 'Unknown' }} {{ $transaction->student->last_name ?? 'Student' }}</div>
                                    <div class="mt-1 text-xs opacity-70">
                                            {{ $transaction->created_at->format('M d, Y g:i A') }}
                                            @if($transaction->student && $transaction->student->course)
                                                • {{ $transaction->student->course->course_name }}
                                            @endif
                            </div>
                                    </div>
                                    <div class="text-success flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1">
                                            <path d="M9 12l2 2 4-4"></path>
                                            <path d="M21 12c.552 0 1-.448 1-1V8c0-.552-.448-1-1-1h-3.5l-1-1h-5l-1 1H3c-.552 0-1 .448-1 1v3c0 .552.448 1 1 1h18z"></path>
                                        </svg>
                                        Voted
                                    </div>
                                </div>
                            @empty
                                <div class="box relative p-8 before:absolute before:inset-0 before:mx-3 before:-mb-3 before:border before:border-foreground/10 before:bg-background/30 before:shadow-[0px_3px_5px_#0000000b] before:z-[-1] before:rounded-xl after:absolute after:inset-0 after:border after:border-foreground/10 after:bg-background after:shadow-[0px_3px_5px_#0000000b] after:rounded-xl after:z-[-1] after:backdrop-blur-md text-center">
                                    <div class="text-gray-500">
                                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <h3 class="text-sm font-medium text-gray-900 mb-1">No Voting Transactions</h3>
                                        <p class="text-sm text-gray-500">No voting activity has been recorded yet.</p>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                    @endauth
                </div>
            </div>
        </div>
   
    <div data-tw-backdrop="" class="modal group bg-black/60 transition-[visibility,opacity] w-screen h-screen fixed left-0 top-0 [&amp;:not(.show)]:duration-[0s,0.2s] [&amp;:not(.show)]:delay-[0.2s,0s] [&amp;:not(.show)]:invisible [&amp;:not(.show)]:opacity-0 [&amp;.show]:visible [&amp;.show]:opacity-100 [&amp;.show]:duration-[0s,0.4s]" id="onboarding-dialog" aria-hidden="true">
        <div class="box relative before:absolute before:inset-0 before:mx-3 before:-mb-3 before:border before:border-foreground/10 before:z-[-1] after:absolute after:inset-0 after:border after:border-foreground/10 after:bg-background after:shadow-[0px_3px_5px_#0000000b] after:z-[-1] after:backdrop-blur-md before:bg-background/60 dark:before:shadow-background before:shadow-foreground/60 z-50 mx-auto -mt-16 p-6 transition-[margin-top,transform] duration-[0.4s,0.3s] before:rounded-3xl before:shadow-2xl after:rounded-3xl group-[.show]:mt-16 group-[.modal-static]:scale-[1.05] sm:max-w-xl">
            <a class="bg-background/80 hover:bg-background absolute right-0 top-0 -mr-3 -mt-3 flex size-9 items-center justify-center rounded-full border shadow outline-none backdrop-blur" data-tw-dismiss="modal" href="#">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="x" class="lucide lucide-x stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 size-5 opacity-70"><path d="M18 6 6 18"></path><path d="m6 6 12 12"></path></svg>
            </a>
            <div class="-mx-3 pb-5">
                <div class="tns-outer" id="tns2-ow"><div class="tns-nav" aria-label="Carousel Pagination"><button type="button" data-nav="0" aria-controls="tns2" style="" aria-label="Carousel Page 1" class="" tabindex="-1"></button><button type="button" data-nav="1" aria-controls="tns2" style="" aria-label="Carousel Page 2 (Current Slide)" class="tns-nav-active"></button></div><button type="button" data-action="stop"><span class="tns-visually-hidden">stop animation</span>stop</button><div class="tns-liveregion tns-visually-hidden" aria-live="polite" aria-atomic="true">slide <span class="current">4</span>  of 2</div><div id="tns2-mw" class="tns-ovh"><div class="tns-inner" id="tns2-iw"><div data-config="{
    nav: true
}" class="tiny-slider mb-11 mt-2  tns-slider tns-carousel tns-subpixel tns-calc tns-horizontal" id="tns2" style="transform: translate3d(-50%, 0px, 0px); transition-duration: 0s;"><div class="relative mx-3 flex flex-col items-center gap-1 px-3.5 tns-item tns-slide-cloned" aria-hidden="true" tabindex="-1">
                        <div class="w-full bg-primary/[.05] mb-7 border-primary/10 shadow-lg shadow-black/10 relative rounded-3xl border h-52 overflow-hidden before:bg-noise before:absolute before:inset-0 before:opacity-30 after:bg-accent after:absolute after:inset-0 after:opacity-30 after:blur-2xl">
                            <img class="absolute inset-0 mx-auto mt-10 w-2/5 scale-125" src="dist/images/phone-illustration.svg" alt="Midone - Tailwind Admin Dashboard Template">
                        </div>
                        <div class="px-8">
                            <div class="text-center text-xl font-medium">Welcome to Midone Admin!</div>
                            <div class="mt-3 text-center text-base leading-relaxed opacity-70">
                                Premium admin dashboard template for all kinds <br> of projects.
                                With a unique and modern design, Midone offers the perfect foundation to build professional