<div class="calendar-container min-h-screen flex flex-col bg-gradient-to-br from-white to-blue-50 pb-safe ml-4 mb-5" 
     x-data="{ 
        showEventModal: false,
        selectedDate: @entangle('selectedDate'),
        eventTypes: ['general', 'meeting', 'deadline', 'personal'],
        isMobileView: window.innerWidth < 768,
        showSidebar: false
     }"
     @resize.window="isMobileView = window.innerWidth < 768">
    
    <div class="calendar-header bg-white rounded-lg shadow-sm mx-2 mt-4 md:mt-2">
        <div class="flex justify-between items-center p-4 pt-safe">
            <h2 class="text-xl md:text-2xl font-bold text-gray-800">
                <span class="text-blue-600">{{ $monthName }}</span> {{ $year }}
            </h2>
            <div class="flex space-x-2">
                <button wire:click="previousMonth" class="px-4 py-2 px-2 py-1 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-sm font-semibold before:w-0 border-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    <span class="hidden md:inline">Previous</span>
                </button>
                <button wire:click="nextMonth" class="px-4 py-2 px-2 py-1 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-sm font-semibold before:w-0 border-0">
                    <span class="hidden md:inline">Next</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
    
    <div class="flex flex-1 overflow-hidden m-2">
        <!-- Main Calendar -->
        <div class="calendar-grid flex-1 overflow-auto bg-white rounded-lg shadow-sm mobile-safe-padding">
            <!-- Weekday headers -->
            <div class="grid grid-cols-7 md:gap-1 sticky top-0 bg-white z-10 shadow-sm pt-1">
                @foreach(['S', 'M', 'T', 'W', 'T', 'F', 'S'] as $index => $dayName)
                    <div class="text-center font-semibold py-3 {{ $index == 0 || $index == 6 ? 'bg-red-50 text-red-500' : 'bg-blue-50 text-blue-600' }}">
                        <span class="md:hidden">{{ $dayName }}</span>
                        <span class="hidden md:inline">{{ ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'][$index] }}</span>
                    </div>
                @endforeach
            </div>
            
            <!-- Calendar days -->
            <!-- Calendar Days - More Compact -->
            <div class="grid grid-cols-7 auto-rows-min">
                    @foreach($calendar as $index => $day)
                        <div 
                            wire:click="selectDate('{{ $day['date'] }}')"
                            @click="selectedDate = '{{ $day['date'] }}'; if(isMobileView) showSidebar = true"
                            class="
                                border-b border-r border-gray-100 p-1 
                                min-h-[60px] md:min-h-[90px] 
                                overflow-hidden relative
                                hover:bg-gray-50 transition-colors duration-200
                                {{ $day['isCurrentMonth'] ? 'bg-white' : 'bg-gray-50/50 text-gray-400' }}
                                {{ $day['isToday'] ? 'ring-1 ring-inset ring-blue-500' : '' }}
                                {{ $day['isSelected'] ? 'bg-blue-50' : '' }}
                                {{ ($index % 7 == 0 || $index % 7 == 6) && $day['isCurrentMonth'] ? 'text-red-500' : '' }}
                            "
                        >
                            <!-- Date Number - Reduced Padding -->
                            <div class="flex justify-between items-center h-6">
                                <div class="flex items-center">
                                    <span class="{{ $day['isToday'] ? 'bg-blue-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs' : 'text-xs font-medium' }}">
                                        {{ $day['day'] }}
                                    </span>
                                </div>
                                @if(count($day['events']) > 0)
                                    <span class="text-[10px] bg-blue-500 text-white rounded-full w-4 h-4 flex items-center justify-center">
                                        {{ count($day['events']) }}
                                    </span>
                                @endif
                            </div>
                            
                            <!-- Event Pills - Ultra Compact -->
                            <div class="mt-0.5 space-y-0.5 overflow-y-auto max-h-[calc(100%-1.5rem)]">
                                @foreach($day['events'] as $index => $event)
                                    @if($index > 2)
                                        <div 
                                            class="text-[10px] leading-tight rounded-sm px-1 py-0.5 truncate cursor-pointer transition-all
                                                {{ $event['type'] == 'meeting' ? 'bg-green-100 border-l-2 border-green-500 text-green-800' : '' }}
                                                {{ $event['type'] == 'deadline' ? 'bg-red-100 border-l-2 border-red-500 text-red-800' : '' }}
                                                {{ $event['type'] == 'personal' ? 'bg-purple-100 border-l-2 border-purple-500 text-purple-800' : '' }}
                                                {{ $event['type'] == 'general' ? 'bg-blue-100 border-l-2 border-blue-500 text-blue-800' : '' }}
                                            "
                                            wire:click.stop="viewAllEvents('{{ $day['date'] }}')"
                                        >
                                            <div class="flex items-center gap-1">
                                                @if($event['time'] ?? false)
                                                    <span class="font-medium inline-block w-8 shrink-0">{{ \Carbon\Carbon::parse($event['time'])->format('H:i') }}</span>
                                                @endif
                                                <span class="truncate">{{ $event['title'] }}</span>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach

                                <!-- Small + indicator -->
                                @if(count($day['events']) > 4)
                                    <div 
                                        class="text-[10px] text-gray-500 px-1 cursor-pointer hover:text-blue-600 transition-colors"
                                        wire:click.stop="viewAllEvents('{{ $day['date'] }}')"
                                    >
                                        +{{ count($day['events']) - 4 }} more
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
        </div>
        <!-- Sidebar for event details - Hidden on mobile unless toggled -->
        <div 
            x-show="selectedDate && (!isMobileView || showSidebar)" 
            class="event-sidebar w-full md:w-80 border-l bg-white overflow-y-auto fixed md:relative inset-0 md:inset-auto z-20 md:z-0 rounded-lg shadow-sm md:ml-2 pt-safe"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform translate-x-full"
            x-transition:enter-end="opacity-100 transform translate-x-0"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100 transform translate-x-0"
            x-transition:leave-end="opacity-0 transform translate-x-full"
        >
            <!-- Mobile close button -->
            <button 
                x-show="isMobileView" 
                @click="showSidebar = false"
                class="absolute top-6 right-3 text-gray-400 hover:text-gray-600 bg-white rounded-full p-1 shadow-sm close-button"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            
            <div class="p-4 pt-32 sm:pt-28 md:pt-6">
                <h3 class="font-semibold text-lg text-gray-800 mb-4 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Events for <span x-text="selectedDate" class="text-blue-600 ml-1"></span>
                </h3>
                
                @if($selectedDate)
                <div class="mb-4">
                @php
                    // Get both events and tasks for the selected date
                    $allEvents = [];
                    // Get events from the events array (if it exists)
                    if (isset($events)) {
                        $filteredEvents = array_filter($events, function($event) use ($selectedDate) {
                            return $event['date'] === $selectedDate;
                        });
                        $allEvents = array_merge($allEvents, $filteredEvents);
                    }
                    // Get tasks from the tasks array
                    if (isset($tasks)) {
                        $filteredTasks = array_filter($tasks, function($task) use ($selectedDate) {
                            return $task['deadline'] === $selectedDate;
                        });
                        $allEvents = array_merge($allEvents, $filteredTasks);
                    }
                    // Debug
                    // echo "<pre>"; print_r($allEvents); echo "</pre>";
                    @endphp
                    @if(count($allEvents) > 0)
                    <ul class="space-y-3">
                        @foreach($allEvents as $event)
                            @php
                                // Determine if the current item is a task
                                $isTask = isset($event['type']) && $event['type'] == 'task';
                                
                                // For tasks, get the username of assigned user (you might need to adjust this based on your data structure)
                                $username = '';
                                $username = $event['username']??'';
                                // Task priority colors - can be customized based on your preference
                                $priorityColors = [
                                    'high' => 'bg-red-500',
                                    'medium' => 'bg-yellow-500',
                                    'low' => 'bg-blue-300',
                                    'default' => 'bg-blue-500'
                                ];
                                
                                // Determine task color based on priority
                                $taskColor = 'bg-blue-500'; // Default task color
                                if ($isTask && isset($event['priority'])) {
                                    $taskColor = $priorityColors[$event['priority']] ?? $priorityColors['default'];
                                }
                            @endphp
                            <li class="p-3 border border-gray-200 rounded-lg bg-white shadow-sm hover:shadow transition-shadow {{ $isTask ? 'border-l-4 border-l-blue-500' : '' }}">
                                <div class="font-medium text-gray-800 flex items-center">
                                    <div class="w-3 h-3 rounded-full mr-2
                                        {{ !$isTask && isset($event['type']) && $event['type'] == 'meeting' ? 'bg-green-500' : '' }}
                                        {{ !$isTask && isset($event['type']) && $event['type'] == 'deadline' ? 'bg-red-500' : '' }}
                                        {{ !$isTask && isset($event['type']) && $event['type'] == 'personal' ? 'bg-purple-500' : '' }}
                                        {{ !$isTask && isset($event['type']) && $event['type'] == 'general' ? 'bg-gray-500' : '' }}
                                        {{ $isTask ? $taskColor : '' }}
                                    "></div>
                                    {{ $event['title'] }}
                                </div>
                                
                                <div class="flex justify-between items-start mt-1">
                                    <div class="text-sm text-gray-600 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                        </svg>
                                        {{ $isTask ? 'Task' : (isset($event['type']) ? ucfirst($event['type']) : 'Task') }}
                                    </div>
                                    
                                    @if($isTask && !empty($username))
                                    <div class="text-sm bg-blue-100 text-blue-800 px-2 py-1 rounded flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        {{ $username }}
                                    </div>
                                    @endif
                                </div>
                                
                                @if(!empty($event['description']))
                                    <div class="text-sm mt-2 text-gray-700 bg-gray-50 p-2 rounded-md border border-gray-100">
                                        {{ $event['description'] }}
                                    </div>
                                @endif
                                
                                @if(!empty($event['work_detail']))
                                    <div class="text-sm mt-2 text-gray-700 bg-gray-50 p-2 rounded-md border border-gray-100">
                                        {{ $event['work_detail'] }}
                                    </div>
                                @endif
                                
                                @if($isTask && isset($event['status']))
                                    <div class="mt-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            {{ $event['status'] == 'completed' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $event['status'] == 'in_progress' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                            {{ $event['status'] == 'pending' ? 'bg-gray-100 text-gray-800' : '' }}
                                        ">
                                            {{ ucfirst(str_replace('_', ' ', $event['status'])) }}
                                        </span>
                                    </div>
                                @endif
                                
                                <div class="mt-3 flex justify-end">
                                    <button
                                        wire:click="{{ $isTask ? 'deleteTask' : 'deleteEvent' }}({{ $event['id'] }})"
                                        class="text-xs text-red-600 hover:text-red-800 flex items-center"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Delete
                                    </button>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                    @else
                    <div class="text-gray-500 bg-gray-50 p-4 rounded-lg border border-gray-200 flex items-center justify-center flex-col">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p>No events or tasks scheduled for this day.</p>
                    </div>
                    @endif
                @endif
                
                <div class="mt-4">
                    <button 
                        @click="showEventModal = true" 
                        class="w-full px-2 py-1 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-sm font-semibold before:w-0 border-0"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Add New Event
                    </button>
                    <button 
                    x-show="isMobileView" 
                        @click="showSidebar = false" 
                        class="mt-4 w-full px-2 py-1 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-sm font-semibold before:w-0 border-0"
                    >
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Add Event Modal -->
    <div 
        x-show="showEventModal" 
        class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50 backdrop-blur-sm pt-safe"
        x-cloak
    >
        <div 
            @click.away="showEventModal = false" 
            class="bg-white p-5 rounded-xl shadow-xl w-full max-w-md mx-4 md:mx-auto max-h-[95vh] overflow-y-auto border border-gray-200 my-4 md:my-0 modal-mobile-margin"
            x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200 transform"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
        >
            <div class="flex justify-between items-center mb-5">
                <h3 class="text-lg font-bold text-gray-800 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Add New Event
                </h3>
                <button @click="showEventModal = false" class="text-gray-400 hover:text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-full p-1 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <form wire:submit="addEvent" class="space-y-3">
                <!-- Title Field - Compact -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Event Title</label>
                    <input 
                        type="text" 
                        wire:model="newEvent.title" 
                        class="mt-1 w-full border border-gray-300 rounded px-3 py-1.5 text-sm focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Enter event title"
                        required
                    >
                    @error('newEvent.title') <span class="text-red-500 text-xs mt-0.5 block">{{ $message }}</span> @enderror
                </div>
                
                <!-- Description Field - Compact -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea 
                        wire:model="newEvent.description" 
                        class="mt-1 w-full border border-gray-300 rounded px-3 py-1.5 text-sm focus:ring-blue-500 focus:border-blue-500"
                        rows="2"
                        placeholder="Enter event description (optional)"
                    ></textarea>
                </div>
                
                <!-- Event Type Selection - Fixed for Livewire -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Event Type</label>
                    <div class="mt-1 grid grid-cols-2 gap-2" wire:ignore>
                        @foreach(['general', 'meeting', 'deadline', 'personal'] as $type)
                            <label 
                                class="relative border rounded px-3 py-1.5 cursor-pointer hover:bg-gray-50 transition-colors flex items-center"
                                x-data="{ isChecked: @entangle('newEvent.type').defer === '{{ $type }}' }"
                                x-bind:class="isChecked ? 'border-blue-500 bg-blue-50 ring-1 ring-blue-200' : 'border-gray-200'"
                            >
                                <input
                                    type="radio"
                                    id="type-{{ $type }}"
                                    name="event-type"
                                    value="{{ $type }}"
                                    wire:model.defer="newEvent.type"
                                    @change="isChecked = $event.target.checked"
                                    class="absolute opacity-0"
                                >
                                <div class="w-3 h-3 rounded-full mr-2
                                    {{ $type == 'meeting' ? 'bg-green-500' : '' }}
                                    {{ $type == 'deadline' ? 'bg-red-500' : '' }}
                                    {{ $type == 'personal' ? 'bg-purple-500' : '' }}
                                    {{ $type == 'general' ? 'bg-gray-500' : '' }}
                                "></div>
                                <span class="text-xs capitalize">{{ $type }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('newEvent.type') <span class="text-red-500 text-xs mt-0.5 block">{{ $message }}</span> @enderror
                </div>
                
                <!-- Date Field - Compact -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Date</label>
                    <input 
                        type="date" 
                        wire:model="newEvent.date" 
                        class="mt-1 w-full border border-gray-300 rounded px-3 py-1.5 text-sm focus:ring-blue-500 focus:border-blue-500"
                        required
                    >
                    <small class="text-xs text-gray-500 mt-0.5 block">Format: YYYY-MM-DD</small>
                    @error('newEvent.date') <span class="text-red-500 text-xs mt-0.5 block">{{ $message }}</span> @enderror
                </div>
                
                <!-- Form Actions - Compact -->
                <div class="flex justify-end space-x-2 pt-2 border-t border-gray-100">
                    <button 
                        @click="showEventModal = false" 
                        type="button"
                        class="px-3 py-1.5 border border-gray-300 rounded text-sm font-medium text-gray-700 hover:bg-gray-50"
                    >
                        Cancel
                    </button>
                    <button 
                        type="submit"
                        class="px-3 py-1.5 bg-blue-600 rounded text-sm font-medium text-white hover:bg-blue-700"
                    >
                        Save Event
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>