@section('title', 'Calendar - Schedules')

<div>
    <!-- Toast Notification Template -->
    <x-menu.notification-toast seconds="10" layout="compact" animated="true" />
    
    <h2 class="mt-10 text-lg font-medium">Calendar - Schedules</h2>
<div class="mt-5 grid grid-cols-12 gap-5">
    <!-- BEGIN: Calendar Side Menu -->
    <div class="col-span-12 xl:col-span-4 2xl:col-span-3">

            <!-- Events List -->
            <div class="box relative p-5 before:absolute before:inset-0 before:mx-3 before:-mb-3 before:border before:border-foreground/10 before:bg-background/30 before:shadow-[0px_3px_5px_#0000000b] before:z-[-1] before:rounded-xl after:absolute after:inset-0 after:border after:border-foreground/10 after:bg-background after:shadow-[0px_3px_5px_#0000000b] after:z-[-1] after:backdrop-blur-md">
                <h3 class="text-lg font-medium mb-4">This Month's Events</h3>
                <div class="space-y-3">
                    @forelse($monthEvents as $event)
                    <div class="flex items-center p-3 rounded-lg border border-foreground/10 hover:bg-foreground/5 transition-colors">
                        <div class="w-3 h-3 rounded-full mr-3" style="background-color: {{ $event['color'] }}"></div>
                        <div class="flex-1 min-w-0">
                            <div class="font-medium text-sm truncate">{{ $event['title'] }}</div>
                            <div class="text-xs text-foreground/70">
                                {{ \Carbon\Carbon::parse($event['start'])->format('M d, Y h:i A') }}
                            </div>
                            <div class="text-xs text-foreground/50">
                                {{ $event['student'] }} • {{ ucfirst(str_replace('_', ' ', $event['type'])) }}
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8 text-foreground/50">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-4">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                        <p>No events this month</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Legend -->
            <div class="box relative p-5 before:absolute before:inset-0 before:mx-3 before:-mb-3 before:border before:border-foreground/10 before:bg-background/30 before:shadow-[0px_3px_5px_#0000000b] before:z-[-1] before:rounded-xl after:absolute after:inset-0 after:border after:border-foreground/10 after:bg-background after:shadow-[0px_3px_5px_#0000000b] after:z-[-1] after:backdrop-blur-md mt-5">
                <h3 class="text-lg font-medium mb-4">Legend</h3>
                <div class="space-y-2">
                    <div class="flex items-center">
                        <div class="w-3 h-3 rounded-full mr-3 bg-primary"></div>
                        <span class="text-sm">Room Campaigns</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-3 h-3 rounded-full mr-3 bg-green-500"></div>
                        <span class="text-sm">Meeting De Abanse</span>
                    </div>
                </div>
            </div>
        </div>
        <!-- END: Calendar Side Menu -->

        <!-- BEGIN: Calendar Content -->
        <div class="col-span-12 xl:col-span-8 2xl:col-span-9">
            <div class="box relative p-5 before:absolute before:inset-0 before:mx-3 before:-mb-3 before:border before:border-foreground/10 before:bg-background/30 before:shadow-[0px_3px_5px_#0000000b] before:z-[-1] before:rounded-xl after:absolute after:inset-0 after:border after:border-foreground/10 after:bg-background after:shadow-[0px_3px_5px_#0000000b] after:z-[-1] after:backdrop-blur-md">
                <!-- Calendar Header -->
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-4">
                        <button wire:click="previousMonth" class="cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-background border-foreground/20 hover:bg-foreground/5 h-10 w-10">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m15 18-6-6 6-6"></path>
                            </svg>
                        </button>
                        <h2 class="text-xl font-semibold">{{ $currentMonthName }}</h2>
                        <button wire:click="nextMonth" class="cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-background border-foreground/20 hover:bg-foreground/5 h-10 w-10">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m9 18 6-6-6-6"></path>
                            </svg>
                        </button>
            </div>
                    <div class="text-sm text-foreground/70">
                        {{ $monthEvents->count() }} events this month
                </div>
                </div>

                @if($viewMode === 'month')
                <!-- Month View -->
                <div class="calendar-month">
                    <!-- Day Headers -->
                    <div class="grid grid-cols-7 gap-1 mb-2">
                        <div class="text-center font-medium text-sm py-2 text-foreground/70">Sun</div>
                        <div class="text-center font-medium text-sm py-2 text-foreground/70">Mon</div>
                        <div class="text-center font-medium text-sm py-2 text-foreground/70">Tue</div>
                        <div class="text-center font-medium text-sm py-2 text-foreground/70">Wed</div>
                        <div class="text-center font-medium text-sm py-2 text-foreground/70">Thu</div>
                        <div class="text-center font-medium text-sm py-2 text-foreground/70">Fri</div>
                        <div class="text-center font-medium text-sm py-2 text-foreground/70">Sat</div>
                    </div>

                    <!-- Calendar Days -->
                    <div class="grid grid-cols-7 gap-1">
                        @foreach($calendarDays as $day)
                        <div class="min-h-[120px] border border-foreground/10 rounded-lg p-2 {{ $day['isCurrentMonth'] ? 'bg-background' : 'bg-foreground/5' }} {{ $day['isToday'] ? 'ring-2 ring-primary' : '' }}">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-sm font-medium {{ $day['isCurrentMonth'] ? 'text-foreground' : 'text-foreground/50' }} {{ $day['isToday'] ? 'text-primary font-bold' : '' }}">
                                    {{ $day['day'] }}
                                </span>
                                @if($day['events']->count() > 0)
                                    <span class="text-xs bg-primary text-primary-foreground rounded-full px-2 py-1">
                                        {{ $day['events']->count() }}
                                    </span>
                                @endif
                            </div>
                            
                            <!-- Events for this day -->
                            <div class="space-y-1">
                                @foreach($day['events']->take(2) as $event)
                                <div class="text-xs p-1 rounded truncate" style="background-color: {{ $event['color'] }}20; color: {{ $event['color'] }}">
                                    {{ $event['title'] }}
                                </div>
                                @endforeach
                                @if($day['events']->count() > 2)
                                <div class="text-xs text-foreground/50">
                                    +{{ $day['events']->count() - 2 }} more
                </div>
                                @endif
                </div>
            </div>
                        @endforeach
                    </div>
                </div>
                @elseif($viewMode === 'list')
                <!-- List View -->
                <div class="space-y-4">
                    @forelse($monthEvents->sortBy('start') as $event)
                    <div class="border border-foreground/10 rounded-lg p-4 hover:bg-foreground/5 transition-colors">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <div class="w-3 h-3 rounded-full" style="background-color: {{ $event['color'] }}"></div>
                                    <h3 class="font-semibold text-lg">{{ $event['title'] }}</h3>
                                    <span class="px-2 py-1 text-xs rounded-full bg-foreground/10">
                                        {{ ucfirst(str_replace('_', ' ', $event['type'])) }}
                                    </span>
                                </div>
                                <p class="text-foreground/70 mb-2">{{ $event['description'] }}</p>
                                <div class="flex items-center gap-4 text-sm text-foreground/60">
                                    <div class="flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                            <line x1="16" y1="2" x2="16" y2="6"></line>
                                            <line x1="8" y1="2" x2="8" y2="6"></line>
                                            <line x1="3" y1="10" x2="21" y2="10"></line>
                                        </svg>
                                        {{ \Carbon\Carbon::parse($event['start'])->format('M d, Y') }}
                    </div>
                                    <div class="flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <polyline points="12,6 12,12 16,14"></polyline>
                                        </svg>
                                        {{ \Carbon\Carbon::parse($event['start'])->format('h:i A') }} - {{ \Carbon\Carbon::parse($event['end'])->format('h:i A') }}
                </div>
                                    <div class="flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="12" cy="7" r="4"></circle>
                                        </svg>
                                        {{ $event['student'] }}
                    </div>
                </div>
            </div>
        </div>
    </div>
                    @empty
                    <div class="text-center py-12 text-foreground/50">
                        <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-4">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                        <h3 class="text-lg font-medium mb-2">No events scheduled</h3>
                        <p>There are no room campaigns or meetings scheduled for this month.</p>
                    </div>
                    @endforelse
                </div>
                @endif
            </div>
        </div>
        <!-- END: Calendar Content -->
    </div>
</div>
