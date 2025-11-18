<?php

namespace App\Livewire\Calendar;

use Livewire\Component;
use App\Models\room_campaign;
use App\Models\meeting_de_abanse;
use App\Models\students;
use Carbon\Carbon;

class Calendar extends Component
{
    public $currentMonth;
    public $currentYear;
    public $viewMode = 'month'; // month, week, day, list
    public $selectedDate;
    public $events = [];

    public function mount()
    {
        $this->currentMonth = now()->month;
        $this->currentYear = now()->year;
        $this->selectedDate = now()->format('Y-m-d');
        $this->loadEvents();
    }

    public function loadEvents()
    {
        $this->events = collect();

        // Load room campaigns
        $roomCampaigns = room_campaign::with('students')
            ->where('status', 'active')
            ->get()
            ->map(function ($campaign) {
                return [
                    'id' => 'room_' . $campaign->id,
                    'title' => $campaign->room_name,
                    'start' => $campaign->start_datetime,
                    'end' => $campaign->end_datetime,
                    'type' => 'room_campaign',
                    'color' => 'var(--color-primary)',
                    'description' => $campaign->description,
                    'student' => $campaign->students ? 
                        $campaign->students->first_name . ' ' . $campaign->students->last_name : 
                        'Unknown Student',
                    'status' => $campaign->status,
                ];
            });

        // Load meeting de abanse
        $meetings = meeting_de_abanse::with('students')
            ->where('status', 'active')
            ->get()
            ->map(function ($meeting) {
                return [
                    'id' => 'meeting_' . $meeting->id,
                    'title' => $meeting->meeting_de_abanse_name,
                    'start' => $meeting->start_datetime,
                    'end' => $meeting->end_datetime,
                    'type' => 'meeting_de_abanse',
                    'color' => 'var(--color-success)',
                    'description' => $meeting->description,
                    'student' => $meeting->students ? 
                        $meeting->students->first_name . ' ' . $meeting->students->last_name : 
                        'Unknown Student',
                    'status' => $meeting->status,
                ];
            });

        $this->events = $roomCampaigns->merge($meetings);
    }

    public function previousMonth()
    {
        $date = Carbon::create($this->currentYear, $this->currentMonth, 1)->subMonth();
        $this->currentMonth = $date->month;
        $this->currentYear = $date->year;
        $this->loadEvents();
    }

    public function nextMonth()
    {
        $date = Carbon::create($this->currentYear, $this->currentMonth, 1)->addMonth();
        $this->currentMonth = $date->month;
        $this->currentYear = $date->year;
        $this->loadEvents();
    }

    public function goToToday()
    {
        $this->currentMonth = now()->month;
        $this->currentYear = now()->year;
        $this->selectedDate = now()->format('Y-m-d');
        $this->loadEvents();
    }

    public function changeView($view)
    {
        $this->viewMode = $view;
    }

    public function selectDate($date)
    {
        $this->selectedDate = $date;
    }

    public function getEventsForDate($date)
    {
        return $this->events->filter(function ($event) use ($date) {
            $eventDate = Carbon::parse($event['start'])->format('Y-m-d');
            return $eventDate === $date;
        });
    }

    public function getEventsForMonth()
    {
        $startOfMonth = Carbon::create($this->currentYear, $this->currentMonth, 1)->startOfMonth();
        $endOfMonth = Carbon::create($this->currentYear, $this->currentMonth, 1)->endOfMonth();

        return $this->events->filter(function ($event) use ($startOfMonth, $endOfMonth) {
            $eventStart = Carbon::parse($event['start']);
            $eventEnd = Carbon::parse($event['end']);
            
            return $eventStart->between($startOfMonth, $endOfMonth) || 
                   $eventEnd->between($startOfMonth, $endOfMonth) ||
                   ($eventStart->lt($startOfMonth) && $eventEnd->gt($endOfMonth));
        });
    }

    public function getCalendarDays()
    {
        $startOfMonth = Carbon::create($this->currentYear, $this->currentMonth, 1)->startOfMonth();
        $endOfMonth = Carbon::create($this->currentYear, $this->currentMonth, 1)->endOfMonth();
        
        $startOfCalendar = $startOfMonth->copy()->startOfWeek();
        $endOfCalendar = $endOfMonth->copy()->endOfWeek();
        
        $days = collect();
        $current = $startOfCalendar->copy();
        
        while ($current->lte($endOfCalendar)) {
            $days->push([
                'date' => $current->format('Y-m-d'),
                'day' => $current->day,
                'isCurrentMonth' => $current->month === $this->currentMonth,
                'isToday' => $current->isToday(),
                'events' => $this->getEventsForDate($current->format('Y-m-d'))
            ]);
            $current->addDay();
        }
        
        return $days;
    }

    public function render()
    {
        $calendarDays = $this->getCalendarDays();
        $monthEvents = $this->getEventsForMonth();
        
        return view('livewire.calendar.calendar', [
            'calendarDays' => $calendarDays,
            'monthEvents' => $monthEvents,
            'currentMonthName' => Carbon::create($this->currentYear, $this->currentMonth, 1)->format('F Y'),
        ]);
    }
}
