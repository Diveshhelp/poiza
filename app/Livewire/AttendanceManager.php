<?php

namespace App\Livewire;

use App\Models\PunchRecord;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class AttendanceManager extends Component
{
    use WithFileUploads;
    use WithPagination;
    
    public $csvFile;
    public $dateFilter;
    public $employeeFilter;
    public $successMessage;
    public $errorMessage;
    public $perPage = PER_PAGE;

    // public $isEditing = false;
    // public $editingPunch = null;
    // public $punchTime;
    // public $selectedEmployeeId;
    // public $selectedDate;
    
    protected $rules = [
        'csvFile' => 'required|file|mimes:csv,txt',
        // 'punchTime' => 'required|date_format:H:i',
    ];
    
    public function mount()
    {
        $this->dateFilter = now()->format('Y-m-d');
    }

    // public function addPunch($employeeId, $date)
    // {
    //     $this->selectedEmployeeId = $employeeId;
    //     $this->selectedDate = $date;
    //     $this->isEditing = true;
    //     $this->editingPunch = null;
    //     $this->punchTime = now()->format('H:i');
    // }

    // public function editPunch($punchId)
    // {
    //     $this->editingPunch = PunchRecord::findOrFail($punchId);
    //     $this->punchTime = Carbon::parse($this->editingPunch->punch_time)->format('H:i');
    //     $this->isEditing = true;
    // }

    // public function deletePunch($punchId)
    // {
    //     PunchRecord::findOrFail($punchId)->delete();
    //     $this->successMessage = 'Punch record deleted successfully.';
    // }

    // public function savePunch()
    // {
    //     $this->validate();

    //     if ($this->editingPunch) {
    //         // Update existing punch
    //         $this->editingPunch->update([
    //             'punch_time' => Carbon::parse($this->selectedDate . ' ' . $this->punchTime)
    //         ]);
    //         $this->successMessage = 'Punch record updated successfully.';
    //     } else {
    //         // Create new punch
    //         PunchRecord::create([
    //             'employee_id' => $this->selectedEmployeeId,
    //             'date' => $this->selectedDate,
    //             'punch_time' => Carbon::parse($this->selectedDate . ' ' . $this->punchTime)
    //         ]);
    //         $this->successMessage = 'Punch record added successfully.';
    //     }

    //     $this->cancelEdit();
    // }

    public function cancelEdit()
    {
        $this->isEditing = false;
        $this->editingPunch = null;
        $this->punchTime = null;
        $this->selectedEmployeeId = null;
        $this->selectedDate = null;
    }
    
    
    public function importCsv()
    {
        $this->validate();
        
        try {
            $path = $this->csvFile->getRealPath();
            $records = array_map('str_getcsv', file($path));
            $headers = array_shift($records); // Remove header row
            
            $importCount = 0;
            
            foreach ($records as $record) {
                if (count($record) >= 3) { // Ensure we have enough columns
                    PunchRecord::create([
                        'employee_id' => $record[0],
                        'date' => $record[1],
                        'punch_time' => $record[2],
                    ]);
                    $importCount++;
                }
            }
            
            $this->successMessage = "Successfully imported {$importCount} punch records.";
            $this->errorMessage = null;
            $this->csvFile = null;
            // $this->dispatch('refreshAttendanceData');
            
        } catch (\Exception $e) {
            $this->errorMessage = "Error importing data: " . $e->getMessage();
            $this->successMessage = null;
        }
    }
    
    public function resetFilters()
    {
        $this->reset(['dateFilter', 'employeeFilter']);
    }

    public function scopeGetDetailedPunches($query, $employeeId, $date)
    {
        return $query->where('employee_id', $employeeId)
                    ->where('date', $date)
                    ->orderBy('punch_time', 'asc')
                    ->get(['id', 'punch_time']);
    }
   
    public function render()
    {

        $query = PunchRecord::query();
    
        if ($this->dateFilter) {
            $query->where('date', $this->dateFilter);
        }
        
        if ($this->employeeFilter) {
            $query->where('employee_id', $this->employeeFilter);
        }
        
        $uniqueRecords = $query->selectRaw('DISTINCT employee_id, date')
            ->orderBy('date', 'desc')
            ->orderBy('employee_id')
            ->paginate($this->perPage);

        // print($uniqueRecords);
        // exit;
                
        $attendanceData = [];
        
        
        foreach ($uniqueRecords as $record) {
            // Get all punches for this employee on this date
            $punches = PunchRecord::getDailyPunches($record->employee_id, $record->date);
            
            $firstPunch = $punches->first();
            $lastPunch = $punches->last();
            
            $totalWorkHours = 0;
            $breakHours = 0;
            $punchDetails = [];
            $totalWorkingMinutes = 0;
            
            if ($firstPunch && $lastPunch) {
                $punchesArray = $punches->values()->all();
                $punchCount = count($punchesArray);
                
                for ($i = 0; $i < $punchCount; $i++) {
                    $currentPunch = $punchesArray[$i];
                    $nextPunch = ($i + 1 < $punchCount) ? $punchesArray[$i + 1] : null;
                    
                    $punchTime = Carbon::parse($currentPunch->punch_time);
                    $type = ($i % 2 == 0) ? 'In' : 'Out';
                    
                    $duration = null;
                    if ($nextPunch && $type === 'In') {
                        $nextPunchTime = Carbon::parse($nextPunch->punch_time);
                        $durationMinutes = $punchTime->diffInMinutes($nextPunchTime);
                        $duration = floor($durationMinutes / 60) . 'h ' . ($durationMinutes % 60) . 'm';
                        
                        if ($type === 'In') {
                            $totalWorkingMinutes += $durationMinutes;
                        }
                    }
                    
                    $punchDetails[] = [
                        // 'id' => $currentPunch->id,
                        'time' => $punchTime->format('h:i A'),
                        'type' => $type,
                        'duration' => $duration
                    ];
                }
                
                $totalWorkHours = $firstPunch->punch_time->diffInHours($lastPunch->punch_time);
                
                // breaks calculate
                for ($i = 1; $i < $punchCount - 1; $i += 2) {
                    if (isset($punchesArray[$i]) && isset($punchesArray[$i + 1])) {
                        $breakStart = Carbon::parse($punchesArray[$i]->punch_time);
                        $breakEnd = Carbon::parse($punchesArray[$i + 1]->punch_time);
                        $breakHours += $breakStart->diffInMinutes($breakEnd) / 60;
                    }
                }
            }
        
            $totalWorkingTime = floor($totalWorkingMinutes / 60) . 'h ' . ($totalWorkingMinutes % 60) . 'm';
        
            $attendanceData[] = [
                'employee_id' => $record->employee_id,
                'date' => Carbon::parse($record->date)->format('d F Y l'),
                'check_in' => $firstPunch ? $firstPunch->punch_time->format('h:i A') : 'Missing',
                'check_out' => $lastPunch ? $lastPunch->punch_time->format('h:i A') : 'Missing',
                'total_hours' => number_format($totalWorkHours, 2),
                'break_hours' => number_format($breakHours, 2),
                'punch_details' => $punchDetails,
                'total_working_time' => $totalWorkingTime
            ];
        }

        // Get unique employee IDs for filter dropdown
        $employees = PunchRecord::distinct('employee_id')
            ->orderBy('employee_id')
            ->pluck('employee_id');

        return view('livewire.attendance.attendance-manager', [
            'attendanceData' => $attendanceData,
            'employees' => $employees,
            'uniqueRecords' => $uniqueRecords, 
        ])->layout('layouts.app');
    }
}