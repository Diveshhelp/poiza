<?php

namespace App\Livewire;

use App\Models\CalendarEvent;
use App\Models\Task;
use Auth;
use Livewire\Component;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class Calendar extends Component
{
    public $month;
    public $year;
    public $selectedDate = null;
    public $events = [];
    public $tasks=[];
    public $isMobileView;
    
    // New Event Form Properties
    public $newEvent = [
        'title' => '',
        'description' => '',
        'date' => '',
        'type' => ''
    ];
    public $team_id;

    public function mount()
    {
        
        $this->team_id = Auth::user()->currentTeam->id;
        $today = Carbon::today();
        $this->month = $today->month;
        $this->year = $today->year;

        $this->events = CalendarEvent::where("team_id",$this->team_id)->get()->toArray();
        $this->tasks = Task::where("team_id",$this->team_id)->get()
            ->map(function($task) {
                $taskArray = $task->toArray();
                $taskArray['type'] = 'task';
                $taskArray['username'] = $task->assign_to->name;
                return $taskArray;
            })
            ->toArray();
    }

    public function previousMonth()
    {
        $date = Carbon::createFromDate($this->year, $this->month, 1)->subMonth();
        $this->month = $date->month;
        $this->year = $date->year;
    }

    public function nextMonth()
    {
        $date = Carbon::createFromDate($this->year, $this->month, 1)->addMonth();
        $this->month = $date->month;
        $this->year = $date->year;
    }

    public function selectDate($date)
    {
        $this->selectedDate = $date;
        $this->dispatch('dateSelected', date: $date);
        
        // Pre-fill the date in the new event form
        $this->newEvent['date'] = $date;
    }
    public function viewAllEvents($date)
    {
        $this->selectDate($date);
        // Add any additional logic to show all events for the selected date
    }
    public function addEvent()
    {
        // Validate form data
        $this->validate([
            'newEvent.title' => 'required|min:3',
            'newEvent.date' => 'required|date',
            'newEvent.type' => 'required|in:general,meeting,deadline,personal'
        ]);
        // In a real app, you would save to the database
        $this->events[] = [
            'id' => count($this->events) + 1, // Use database ID in real app
            'title' => $this->newEvent['title'],
            'description' => $this->newEvent['description'] ?? '',
            'date' => $this->newEvent['date'],
            'type' => $this->newEvent['type']
        ];
        // Normalize the date format to ensure it's in Y-m-d format
        $normalizedDate = $this->newEvent['date'];
        if (is_string($normalizedDate) && strpos($normalizedDate, 'T') !== false) {
            $normalizedDate = substr($normalizedDate, 0, 10); // Extract just YYYY-MM-DD part
        }

        CalendarEvent::create([
            'title' => $this->newEvent['title'],
            'description' => $this->newEvent['description'],
            'date' => $normalizedDate,
            'type' => $this->newEvent['type'],
            'user_id' => auth()->id(),
            'team_id'=>Auth::user()->currentTeam->id
        ]);
        
        // Reset form
        $this->reset('newEvent');
        $this->newEvent['date'] = $this->selectedDate;
        $this->newEvent['type'] = 'general';
        
        // Notify frontend
        $this->dispatch('eventAdded');
    }
    
    public function moveEvent($eventId, $newDate)
    {
        // Find the event and update its date
        foreach ($this->events as $key => $event) {
            if ($event['id'] == $eventId) {
                $this->events[$key]['date'] = $newDate;
                break;
            }
        }
        
        $this->dispatch('eventUpdated');
    }
    
    public function deleteEvent($eventId)
    {
        // Filter out the event with the given ID
        $this->events = array_filter($this->events, function($event) use ($eventId) {
            return $event['id'] != $eventId;
        });
        
        $this->dispatch('eventDeleted');
    }

    public function getCalendarData()
{
    $startOfMonth = Carbon::createFromDate($this->year, $this->month, 1)->startOfMonth();
    $endOfMonth = Carbon::createFromDate($this->year, $this->month, 1)->endOfMonth();
    // Include days from previous month to fill the first week
    $startDay = $startOfMonth->copy()->startOfWeek();
    // Include days from next month to fill the last week
    $endDay = $endOfMonth->copy()->endOfWeek();
    // Generate an array of all days in the period
    $period = CarbonPeriod::create($startDay, $endDay);
    
    // Fetch tasks for the period - simplified to include only title and date
    $tasks = Task::whereBetween('deadline', [$startDay->format('Y-m-d'), $endDay->format('Y-m-d')])
        ->where("team_id",$this->team_id)
        ->get()
        ->map(function ($task) {
            return [
                'id' => $task->id+  1,
                'title' => $task->title,
                'description' => $task->work_detail,
                'date' => Carbon::parse($task->deadline)->format('Y-m-d'),
                'type' => 'task', 
                'user_id' => auth()->id(),

            ];
        })
        ->toArray();
    
    // Combine tasks with existing events if any
    $allEvents = array_merge($this->events ?? [], $tasks);
    $calendar = [];
    foreach ($period as $date) {
        $dateString = $date->format('Y-m-d');
        
        // Filter events and tasks for this date
        $dayItems = array_filter($allEvents, function($item) use ($dateString) {
            return $item['date'] === $dateString;
        });
        
        // We're no longer grouping tasks by priority or status since we only want titles
        
        $calendar[] = [
            'date' => $dateString,
            'day' => $date->day,
            'isCurrentMonth' => $date->month === (int) $this->month,
            'isToday' => $date->isToday(),
            'isSelected' => $dateString === $this->selectedDate,
            'events' => $dayItems
        ];
    }
    return $calendar;
}

    public function render()
    {
        $currentMonth = Carbon::createFromDate($this->year, $this->month, 1);
        $calendar = $this->getCalendarData();
        return view('livewire.calendar', [
            'calendar' => $calendar,
            'monthName' => $currentMonth->format('F'),
            'year' => $this->year,
        ])->layout('layouts.app');
    }
}