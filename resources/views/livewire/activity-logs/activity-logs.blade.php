@section('title', 'Activity Logs')

<div>
    <!-- Toast Notification Template -->
    <x-menu.notification-toast seconds="10" layout="compact" animated="true" />
    
    <h2 class="mt-10 text-lg font-medium">Activity Logs</h2>
    @if(isset($isRestricted) && $isRestricted)
        <div class="mt-3 p-3 rounded-md bg-yellow-50 border border-yellow-200 text-yellow-800">
            Showing activity logs for: <strong>{{ $restrictedUserName ?? 'You' }}</strong>
        </div>
    @endif
    <div class="mt-5 grid grid-cols-12 gap-6">
        <!-- Filters Section -->
        <div class="col-span-12">
            <div class="box relative before:absolute before:inset-0 before:mx-3 before:-mb-3 before:border before:border-foreground/10 before:bg-background/30 before:shadow-[0px_3px_5px_#0000000b] before:z-[-1] before:rounded-xl after:absolute after:inset-0 after:border after:border-foreground/10 after:bg-background after:shadow-[0px_3px_5px_#0000000b] after:rounded-xl after:z-[-1] after:backdrop-blur-md p-5">
                <h3 class="text-lg font-medium mb-4">Filters</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Search -->
                    <div class="relative">
                        <input 
                            wire:model.live.debounce.300ms="search" 
                            class="h-10 rounded-md border bg-background px-3 py-2 ring-offset-background file:border-0 file:bg-transparent file:font-medium file:text-foreground placeholder:text-foreground/70 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 box w-full pr-10" 
                            type="text"
                            placeholder="Search activities...">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="search" class="lucide lucide-search size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 absolute inset-y-0 right-0 my-auto mr-3 h-4 w-4"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.3-4.3"></path></svg>
                    </div>

                    <!-- Action Filter -->
                    <select wire:model.live="filterAction" class="h-10 rounded-md border bg-background px-3 py-2 ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 box">
                        <option value="">All Actions</option>
                        @foreach($actions as $action)
                            <option value="{{ $action }}">{{ ucfirst(str_replace('_', ' ', $action)) }}</option>
                        @endforeach
                        </select>

                    <!-- User Filter -->
                    @if(isset($isRestricted) && $isRestricted)
                        <div class="h-10 rounded-md border bg-background px-3 py-2 flex items-center">{{ $restrictedUserName ?? 'You' }}</div>
                    @else
                        <select wire:model.live="filterUser" class="h-10 rounded-md border bg-background px-3 py-2 ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 box">
                            <option value="">All Users</option>
                            @foreach($users as $user)
                                <option value="{{ $user['id'] }}">{{ $user['name'] }}</option>
                            @endforeach
                        </select>
                    @endif

                    <!-- Date Range -->
                    <div class="relative">
                        <input 
                            wire:model.live="filterDateRange" 
                            type="text"
                            class="h-10 rounded-md border bg-background px-3 py-2 ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 box w-full pr-10" 
                            placeholder="Select date range (e.g., 2024-01-01 to 2024-01-31)"
                            readonly
                            id="dateRangeInput">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="calendar" class="lucide lucide-calendar size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 absolute inset-y-0 right-0 my-auto mr-3 h-4 w-4">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                        
                        <!-- Calendar Dropdown -->
                        <div id="calendarDropdown" class="absolute top-full left-0 mt-1 bg-white border border-gray-300 rounded-lg shadow-lg z-50 hidden w-80">
                            <div class="p-4">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-lg font-semibold">Select Date Range</h3>
                                    <button id="closeCalendar" class="text-gray-500 hover:text-gray-700">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                    </div>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium mb-2">From Date:</label>
                                        <input type="date" id="fromDate" class="w-full p-2 border rounded-md">
                    </div>
                                    <div>
                                        <label class="block text-sm font-medium mb-2">To Date:</label>
                                        <input type="date" id="toDate" class="w-full p-2 border rounded-md">
                    </div>
                    </div>
                                <div class="flex justify-end gap-2 mt-4">
                                    <button id="clearDates" class="px-3 py-1 text-sm text-gray-600 border rounded-md hover:bg-gray-50">Clear</button>
                                    <button id="applyDates" class="px-3 py-1 text-sm bg-blue-600 text-white rounded-md hover:bg-blue-700">Apply</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 flex justify-between items-center">
                    <button wire:click="clearFilters" class="cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 text-foreground hover:bg-foreground/5 bg-background border-foreground/20 h-10 px-4 py-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="x" class="lucide lucide-x stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 mr-2 size-4"><path d="M18 6 6 18"></path><path d="M6 6l12 12"></path></svg>
                        Clear Filters
                </button>

                    <div class="text-sm text-foreground/70">
                        @if($activityLogs->total() > 0)
                            Showing {{ $activityLogs->firstItem() }} to {{ $activityLogs->lastItem() }} of {{ $activityLogs->total() }} entries
                        @else
                            No entries found
                            @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Activity Logs List -->
        <div class="col-span-12">
            <div class="box relative before:absolute before:inset-0 before:mx-3 before:-mb-3 before:border before:border-foreground/10 before:bg-background/30 before:shadow-[0px_3px_5px_#0000000b] before:z-[-1] before:rounded-xl after:absolute after:inset-0 after:border after:border-foreground/10 after:bg-background after:shadow-[0px_3px_5px_#0000000b] after:rounded-xl after:z-[-1] after:backdrop-blur-md p-0">
                <div class="overflow-x-auto">
                <table class="w-full caption-bottom border-separate border-spacing-y-[10px] -mt-2">
                        <thead class="[&_tr]:border-b-0 [&_tr_th]:h-10">
                        <tr class="transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted border-b-0">
                                <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground [&:has([role=checkbox])]:pr-0">
                                    ACTION
                            </th>
                                <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground [&:has([role=checkbox])]:pr-0">
                                    DETAILS
                            </th>
                                <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground [&:has([role=checkbox])]:pr-0">
                                    USER
                            </th>
                                <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground [&:has([role=checkbox])]:pr-0">
                                    DOCUMENT TYPE
                            </th>
                                <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground [&:has([role=checkbox])]:pr-0">
                                    IP ADDRESS
                            </th>
                                <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground [&:has([role=checkbox])]:pr-0">
                                    LOCATION
                            </th>
                                <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground [&:has([role=checkbox])]:pr-0">
                                    TIMESTAMP
                            </th>
                        </tr>
                    </thead>
                        <tbody class="[&_tr:last-child]:border-0">
                            @forelse($activityLogs as $log)
                        <tr class="transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted border-b-0">
                                <td class="shadow-[3px_3px_5px_#0000000b] first:rounded-l-xl last:rounded-r-xl box rounded-none p-4 align-middle [&:has([role=checkbox])]:pr-0 border-y border-foreground/10 bg-background first:border-l last:border-r">
                                <div class="flex items-center">
                                        @php
                                            $actionColor = match($log->action) {
                                                'created' => 'bg-green-100 text-green-800',
                                                'updated' => 'bg-blue-100 text-blue-800',
                                                'deleted' => 'bg-red-100 text-red-800',
                                                'restored' => 'bg-yellow-100 text-yellow-800',
                                                default => 'bg-gray-100 text-gray-800'
                                            };
                                        @endphp
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $actionColor }}">
                                            {{ ucfirst(str_replace('_', ' ', $log->action)) }}
                                        </span>
                                        </div>
                                </td>
                                <td class="shadow-[3px_3px_5px_#0000000b] first:rounded-l-xl last:rounded-r-xl box rounded-none p-4 align-middle [&:has([role=checkbox])]:pr-0 border-y border-foreground/10 bg-background first:border-l last:border-r">
                                    <div class="max-w-xs">
                                        <div class="font-medium text-sm">{{ $log->details }}</div>
                                        @if($log->remarks)
                                            @php
                                                $remarks = json_decode($log->remarks, true);
                                            @endphp
                                            @if(is_array($remarks) && isset($remarks['message']))
                                                <div class="text-xs text-foreground/70 mt-1">{{ $remarks['message'] }}</div>
                                            @endif
                                    @endif
                                </div>
                            </td>
                                <td class="shadow-[3px_3px_5px_#0000000b] first:rounded-l-xl last:rounded-r-xl box rounded-none p-4 align-middle [&:has([role=checkbox])]:pr-0 border-y border-foreground/10 bg-background first:border-l last:border-r">
                                    <div class="text-sm">
                                        @php
                                            $user = \App\Models\User::find($log->user_id);
                                            $student = null;
                                            $userName = 'System';
                                            
                                            if ($user) {
                                                $userName = $user->name ?? 'Unknown User';
                                            } else {
                                                $student = \App\Models\students::find($log->user_id);
                                                if ($student) {
                                                    $name = trim($student->first_name . ' ' . $student->middle_name . ' ' . $student->last_name);
                                                    $suffix = $student->suffix ? ', ' . $student->suffix : '';
                                                    $userName = $name . $suffix ?: 'Unknown Student';
                                                }
                                            }
                                        @endphp
                                        {{ $userName }}
                                </div>
                            </td>
                                <td class="shadow-[3px_3px_5px_#0000000b] first:rounded-l-xl last:rounded-r-xl box rounded-none p-4 align-middle [&:has([role=checkbox])]:pr-0 border-y border-foreground/10 bg-background first:border-l last:border-r">
                                    <div class="text-sm font-mono">
                                        {{ class_basename($log->document_type) }}
                                </div>
                            </td>
                                <td class="shadow-[3px_3px_5px_#0000000b] first:rounded-l-xl last:rounded-r-xl box rounded-none p-4 align-middle [&:has([role=checkbox])]:pr-0 border-y border-foreground/10 bg-background first:border-l last:border-r">
                                    <div class="text-sm font-mono text-foreground/70">
                                        {{ $log->ip_address ?? 'N/A' }}
                                </div>
                            </td>
                                <td class="shadow-[3px_3px_5px_#0000000b] first:rounded-l-xl last:rounded-r-xl box rounded-none p-4 align-middle [&:has([role=checkbox])]:pr-0 border-y border-foreground/10 bg-background first:border-l last:border-r">
                                    <div class="text-sm text-foreground/70">
                                        {{ $log->location ?? 'Unknown' }}
                                </div>
                            </td>
                                <td class="shadow-[3px_3px_5px_#0000000b] first:rounded-l-xl last:rounded-r-xl box rounded-none p-4 align-middle [&:has([role=checkbox])]:pr-0 border-y border-foreground/10 bg-background first:border-l last:border-r">
                                    <div class="text-sm">{{ $log->created_at->format('M d, Y') }}</div>
                                    <div class="text-xs opacity-70">{{ optional($log->created_at)->format('h:i A') ?? '' }}</div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                                <td colspan="7" class="text-center py-8 text-gray-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="activity" class="lucide lucide-activity mx-auto mb-4 text-foreground/30">
                                        <path d="M22 12h-4l-3 9L9 3l-3 9H2"></path>
                                    </svg>
                                    <h3 class="text-lg font-medium text-foreground mb-2">No activity logs found</h3>
                                    <p class="text-foreground/70">
                                        @if($search || $filterAction || $filterUser || $filterDateRange)
                                            No activity logs match your current filters.
                                        @else
                                            No activity logs have been recorded yet.
                                        @endif
                                    </p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                </div>

                <!-- Pagination -->
                <div class="border-t border-foreground/10 px-5 py-4">
                    <x-menu.pagination :paginator="$activityLogs" :perPageOptions="[10, 25, 50, 100]" />
                </div>
            </div>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dateRangeInput = document.getElementById('dateRangeInput');
            const calendarDropdown = document.getElementById('calendarDropdown');
            
            if (dateRangeInput && calendarDropdown) {
                // Show dropdown on click
                dateRangeInput.addEventListener('click', function() {
                    calendarDropdown.classList.toggle('hidden');
                });
                
                // Hide dropdown on close button
                document.getElementById('closeCalendar').addEventListener('click', function() {
                    calendarDropdown.classList.add('hidden');
                });
                
                // Clear dates
                document.getElementById('clearDates').addEventListener('click', function() {
                    document.getElementById('fromDate').value = '';
                    document.getElementById('toDate').value = '';
                    dateRangeInput.value = '';
                    dateRangeInput.dispatchEvent(new Event('input', { bubbles: true }));
                    calendarDropdown.classList.add('hidden');
                });
                
                // Apply date range
                document.getElementById('applyDates').addEventListener('click', function() {
                    const fromDate = document.getElementById('fromDate').value;
                    const toDate = document.getElementById('toDate').value;
                    
                    if (fromDate && toDate) {
                        dateRangeInput.value = fromDate + ' to ' + toDate;
                        dateRangeInput.dispatchEvent(new Event('input', { bubbles: true }));
                    } else if (fromDate) {
                        dateRangeInput.value = fromDate;
                        dateRangeInput.dispatchEvent(new Event('input', { bubbles: true }));
                    }
                    
                    calendarDropdown.classList.add('hidden');
                });
                
                // Hide dropdown when clicking outside
                document.addEventListener('click', function(e) {
                    if (!dateRangeInput.contains(e.target) && !calendarDropdown.contains(e.target)) {
                        calendarDropdown.classList.add('hidden');
                    }
                });
            }
        });
    </script>
</div>