{{-- File Location:- resources/views/livewire/attendance-manager.blade.php --}}
<div>
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Employee Attendance Management</h1>
        
        <!-- Messages -->
        @if($successMessage)
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                <p>{{ $successMessage }}</p>
            </div>
        @endif
        
        @if($errorMessage)
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                <p>{{ $errorMessage }}</p>
            </div>
        @endif
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <!-- CSV Import -->
            <div class="col-span-1 bg-white rounded-lg shadow-md p-4">
                <div class="bg-blue-50 px-4 py-2 border-b border-gray-200">
                    <h3 class="text-sm font-semibold text-blue-700 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 16L6 10h4V4h4v6h4l-6 6zm2 2v-1.4l4-4v5.4c0 0.6-0.4 1-1 1H7c-0.6 0-1-0.4-1-1V12.6l4 4V19h4z"/>
                    </svg>
                    Import Punch Data
                    </h3>
                </div>
                <div class="p-4">
                <form wire:submit.prevent="importCsv" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="csvFile" class="block text-sm font-medium text-gray-700 mb-1">CSV File</label>
                        <input type="file" wire:model="csvFile" id="csvFile" class="w-full p-2 border border-gray-300 rounded">
                        @error('csvFile') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <button type="submit" class="px-2 py-1 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-sm font-semibold before:w-0 border-0">
                        Import Data
                    </button>
                </form>
                </div>
            </div>
            
            <!-- Filters -->
            <div class="col-span-2 bg-white rounded-lg shadow-md p-4">
                
                    <div class="bg-green-50 px-4 py-2 border-b border-gray-200">
                        <h3 class="text-sm font-semibold text-green-700 flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd"></path>
                        </svg>
                        Filter Records
                        </h3>
                    </div>
                    <div class="p-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                            <input type="date" wire:model="dateFilter" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Employee</label>
                            <select wire:model.live="employeeFilter" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value="">All Employees</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee }}">{{ $employee }}</option>
                            @endforeach
                            </select>
                        </div>
                        </div>
                        <div class="mt-4 flex justify-end">
                        <button wire:click="resetFilters" class="px-2 py-1 text-white hover:dark:text-dark-bg before:[content:''] relative z-[5] before:absolute before:left-0 before:h-full bg-primary dark:bg-secondary before:bg-secondary before:dark:bg-white hover:text-white no-underline transition-all ease-in-out duration-300 hover:before:w-full before:transition-all before:ease-in-out before:duration-300 before:z-[-1] flex justify-center items-center text-sm font-semibold before:w-0 border-0">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Reset Filters
                        </button>
                        </div>
                    </div>
               
            </div>
        </div>
        
        <!-- Attendance Records -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-4 border-b">
                <h2 class="text-lg font-semibold">Attendance Records</h2>
            </div>

            @if(count($attendanceData) > 0)
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr class="bg-gray-100 dark:bg-gray-700">
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-600 dark:text-gray-200">Employee ID</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-600 dark:text-gray-200">Date</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-600 dark:text-gray-200">Check In</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-600 dark:text-gray-200">Check Out</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-600 dark:text-gray-200">Total Hours</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-600 dark:text-gray-200">Break</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-600 dark:text-gray-200">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($attendanceData as $record)
                                <tr class="border-b border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-800">
                                    <td class="px-4 py-3 text-sm text-gray-800 dark:text-gray-200">{{ $record['employee_id'] }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-800 dark:text-gray-200">{{ $record['date'] }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-800 dark:text-gray-200">{{ $record['check_in'] }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-800 dark:text-gray-200">{{ $record['check_out'] }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-800 dark:text-gray-200">{{ $record['total_hours'] }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-800 dark:text-gray-200">{{ $record['break_hours'] }}</td>
                                    <td class="px-4 py-3 text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 cursor-pointer">
                                     
                                        <div x-data="{ open: false }">
                                            <button 
                                                @click="open = true"
                                                class="inline-flex items-center text-blue-600 hover:text-blue-800 transition-colors duration-200"
                                                title="View Details"
                                            >
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </button>

                                            <!-- Modal -->
                                            <div
                                                x-show="open"
                                                x-cloak
                                                class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
                                                x-transition:enter="transition ease-out duration-300"
                                                x-transition:enter-start="opacity-0"
                                                x-transition:enter-end="opacity-100"
                                                x-transition:leave="transition ease-in duration-200"
                                                x-transition:leave-start="opacity-100"
                                                x-transition:leave-end="opacity-0"
                                            >
                                                <div class="relative top-20 mx-auto p-6 border w-11/12 max-w-4xl shadow-xl rounded-lg bg-white">
                                                    <!-- Modal Header -->
                                                    <div class="flex justify-between items-center mb-6 border-b pb-3">
                                                        <h3 class="text-xl font-semibold text-gray-800">Punch Details</h3>
                                                        <button @click="open = false" class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                                                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                            </svg>
                                                        </button>
                                                    </div>

                                                    {{-- <div class="mb-4 flex justify-end">
                                                        <button 
                                                            wire:click="addPunch('{{ $record['employee_id'] }}', '{{ Carbon\Carbon::parse($record['date'])->format('Y-m-d') }}')"
                                                            class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md text-sm"
                                                        >
                                                            Add Punch
                                                        </button>
                                                    </div> --}}

                                                    <!-- Table Container with shadow and rounded corners -->
                                                    <div class="overflow-x-auto bg-white rounded-lg shadow-md">
                                                        <table class="min-w-full">
                                                            <thead>
                                                                <tr class="bg-gray-100">
                                                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b">Session</th>
                                                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b">Check In</th>
                                                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b">Check Out</th>
                                                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b">Duration</th>
                                                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b">Break After</th>
                                                                    {{-- <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b">Actions</th> --}}
                                                                </tr>
                                                            </thead>
                                                            <tbody class="divide-y divide-gray-200">
                                                                @php
                                                                    $session = 1;
                                                                    $totalDuration = 0;
                                                                    $totalBreak = 0;
                                                                @endphp
                                                                @for($i = 0; $i < count($record['punch_details']); $i += 2)
                                                                    @php
                                                                        $checkIn = $record['punch_details'][$i] ?? null;
                                                                        $checkOut = $record['punch_details'][$i + 1] ?? null;
                                                                        
                                                                        $duration = '';
                                                                        if($checkIn && $checkOut) {
                                                                            $startTime = \Carbon\Carbon::createFromFormat('h:i A', $checkIn['time']);
                                                                            $endTime = \Carbon\Carbon::createFromFormat('h:i A', $checkOut['time']);
                                                                            $durationMinutes = $startTime->diffInMinutes($endTime);
                                                                            $durationHours = floor($durationMinutes / 60);
                                                                            $durationMins = $durationMinutes % 60;
                                                                            $duration = $durationHours . "h " . $durationMins . "m";
                                                                            $totalDuration += $durationMinutes;
                                                                        }

                                                                        $breakDuration = '';
                                                                        if(isset($record['punch_details'][$i + 2])) {
                                                                            $breakStart = \Carbon\Carbon::createFromFormat('h:i A', $checkOut['time']);
                                                                            $breakEnd = \Carbon\Carbon::createFromFormat('h:i A', $record['punch_details'][$i + 2]['time']);
                                                                            $breakMinutes = $breakStart->diffInMinutes($breakEnd);
                                                                            $breakHours = floor($breakMinutes / 60);
                                                                            $breakMins = $breakMinutes % 60;
                                                                            $breakDuration = $breakHours . "h " . $breakMins . "m";
                                                                            $totalBreak += $breakMinutes;
                                                                        }
                                                                    @endphp
                                                                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                                                                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $session }}</td>
                                                                        <td class="px-6 py-4 text-sm text-green-600 font-medium">{{ $checkIn ? $checkIn['time'] : '-' }}</td>
                                                                        <td class="px-6 py-4 text-sm text-red-600 font-medium">{{ $checkOut ? $checkOut['time'] : '-' }}</td>
                                                                        <td class="px-6 py-4 text-sm text-gray-600">{{ $duration ?: '-' }}</td>
                                                                        <td class="px-6 py-4 text-sm text-gray-600">{{ $breakDuration ?: '-' }}</td>
                                                                        {{-- <td class="px-6 py-4 text-sm space-x-2">
                                                                            <button 
                                                                                wire:click="editPunch('{{ $checkIn['id'] }}')"
                                                                                class="text-blue-600 hover:text-blue-800"
                                                                            >
                                                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                                                </svg>
                                                                            </button>
                                                                            <button 
                                                                                wire:click="deletePunch('{{ $checkIn['id'] }}')"
                                                                                class="text-red-600 hover:text-red-800"
                                                                                onclick="confirm('Are you sure?') || event.stopImmediatePropagation()"
                                                                            >
                                                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                                </svg>
                                                                            </button>
                                                                        </td> --}}
                                                                    </tr>
                                                                    @php $session++; @endphp
                                                                @endfor
                                                            </tbody>
                                                        </table>
                                                        {{-- @if($isEditing)
                                                            <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                                                                <div class="bg-white p-6 rounded-lg shadow-xl w-96">
                                                                    <h3 class="text-lg font-semibold mb-4">{{ $editingPunch ? 'Edit' : 'Add' }} Punch Record</h3>
                                                                    
                                                                    <div class="mb-4">
                                                                        <label class="block text-sm font-medium text-gray-700 mb-1">Punch Time</label>
                                                                        <input 
                                                                            type="time" 
                                                                            wire:model="punchTime"
                                                                            class="w-full px-3 py-2 border rounded-md"
                                                                        >
                                                                        @error('punchTime') 
                                                                            <span class="text-red-500 text-xs">{{ $message }}</span> 
                                                                        @enderror
                                                                    </div>

                                                                    <div class="flex justify-end space-x-2">
                                                                        <button 
                                                                            wire:click="cancelEdit"
                                                                            class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md"
                                                                        >
                                                                            Cancel
                                                                        </button>
                                                                        <button 
                                                                            wire:click="savePunch"
                                                                            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md"
                                                                        >
                                                                            Save
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif --}}
                                                    </div>

                                                    <!-- Summary Cards -->
                                                    <div class="grid grid-cols-2 gap-4 mt-6">
                                                        <div class="bg-blue-50 rounded-lg p-4 border border-blue-100">
                                                            <div class="text-sm text-gray-600 mb-1">Total Working Time</div>
                                                            <div class="text-xl font-semibold text-blue-600">
                                                                {{ floor($totalDuration / 60) }}h {{ $totalDuration % 60 }}m
                                                            </div>
                                                        </div>
                                                        <div class="bg-orange-50 rounded-lg p-4 border border-orange-100">
                                                            <div class="text-sm text-gray-600 mb-1">Total Break Time</div>
                                                            <div class="text-xl font-semibold text-orange-600">
                                                                {{ floor($totalBreak / 60) }}h {{ $totalBreak % 60 }}m
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-4 text-center text-gray-500">
                    No attendance records found with the current filters.
                </div>
            @endif
        </div>
    </div>
    <div class="mt-4 px-4">
        @if (isset($uniqueRecords) && $uniqueRecords->count() > 0)
            {{ $uniqueRecords->links() }}
        @endif
    </div>
</div>

{{-- Model Table --}}
{{-- <div class="overflow-x-auto">
                                                        <table class="min-w-full divide-y divide-gray-200">
                                                            <thead>
                                                                <tr>
                                                                    <th class="px-4 py-2 text-left">Punch Time</th>
                                                                    <th class="px-4 py-2 text-left">Type</th>
                                                                    <th class="px-4 py-2 text-left">Duration</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($record['punch_details'] as $index => $punch)
                                                                    <tr>
                                                                        <td class="px-4 py-2">{{ $punch['time'] }}</td>
                                                                        <td class="px-4 py-2">{{ $punch['type'] }}</td>
                                                                        <td class="px-4 py-2">{{ $punch['duration'] ?? '-' }}</td>
                                                                    </tr>
                                                                @endforeach
                                                                <tr class="bg-gray-50">
                                                                    <td colspan="2" class="px-4 py-2 font-semibold">Total Working Time:</td>
                                                                    <td class="px-4 py-2 font-semibold">{{ $record['total_working_time'] }}</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div> --}}