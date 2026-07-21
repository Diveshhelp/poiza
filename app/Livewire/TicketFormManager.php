<?php

namespace App\Livewire;

use App\Models\Authority;
use App\Models\Branches;
use App\Models\NatureOfWork;
use App\Models\Ticket;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;

class TicketFormManager extends Component
{
    use WithPagination;
    public $form;
    public $search = '';
    public $serverSearch = ''; 
    public $moduleTitle = TICKET_TITLE;
    public $isUpdate = false;
    public $editUuid;    
    public $commonCreateSuccess;
    public $commonUpdateSuccess;
    public $commonDeleteSuccess;
    public $team_id;
    public $user_id;
    public $id;
    
    public $ticketId;
    public $establishmentName;
    public $natureOfWork;
    public $createdDate;
    public $timezone;

    // Process steps data
    public $processSteps = [];

    // Loading state
    public $loading = false;

    // Define all step names
    protected $stepNames = [
        'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten',
        'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen',
        'eighteen', 'nineteen', 'twenty', 'twentyone', 'twentytwo', 'twentythree',
        'twentyfour', 'twentyfive', 'twentysix','twentyseven','twentyeight','twentynine','thirty','thirtyone','thirtytwo',
        'thirtythree','thirtyfour','thirtyfive','thirtysix'
    ];

    protected $listeners = ['refreshComponent' => '$refresh'];
    public $token;
    public function mount()
    {
        $this->id=$_GET['link'];
        $this->form=$_GET['form'];
        $this->token=$_GET['token'];
        $this->timezone = config('app.timezone', 'UTC');
        $ticketData=Ticket::find($this->id);
        if ($ticketData) {
            $this->ticketId = $ticketData['id'] ?? null;
            $this->establishmentName = $ticketData['establish_name'] ?? '';
            $this->natureOfWork = $ticketData['natureofwork'] ?? '';
            $this->createdDate = $ticketData['created_at'] ?? now();

            // Load existing JSON data
            $decodedData = is_string($ticketData['json_data'] ?? null) 
                ? json_decode($ticketData['json_data'], true) 
                : ($ticketData['json_data'] ?? []);


            $this->loadProcessSteps($decodedData);
        } else {
            // Initialize empty form
            $this->initializeEmptyForm();
        }

        $this->team_id = Auth::user()->currentTeam->id;
        $this->user_id = Auth::user()->id;
        $this->commonCreateSuccess = str_replace('{module-name}', $this->moduleTitle, COMMON_CREATE_SUCCESS);
        $this->commonUpdateSuccess = str_replace('{module-name}', $this->moduleTitle, COMMON_UPDATE_SUCCESS);
        $this->commonDeleteSuccess = str_replace('{module-name}', $this->moduleTitle, COMMON_DELETE_SUCCESS);
        
    }

    protected function  loadProcessSteps($decodedData)
    {
        foreach ($this->stepNames as $step) {
            $this->processSteps[$step] = [
                'checkbox' => isset($decodedData[$step]['checkbox']) && $decodedData[$step]['checkbox'] === 'on',
                'comment' => $decodedData[$step]['comment'] ?? '',
                'user_name' => $decodedData[$step]['user_name'] ?? '',
                'date_time' => $decodedData[$step]['date_time'] ?? '',
                'savedby' => $decodedData[$step]['savedby'] ?? '',
                
                // Special fields for specific steps
                '7_unam' => $decodedData[$step]['7_unam'] ?? '', // For steps 5 and 8
                '7_pass' => $decodedData[$step]['7_pass'] ?? '', // For steps 5 and 8
                'mobile_number' => $decodedData[$step]['mobile_number'] ?? '', // For step 13
                'ann_fees' => $decodedData[$step]['ann_fees'] ?? '', // For step 20
                'reg_fees' => $decodedData[$step]['reg_fees'] ?? '', // For step 20
                'pmrpy_fees' => $decodedData[$step]['pmrpy_fees'] ?? '', // For step 20
                'pt_22' => $decodedData[$step]['pt_22'] ?? '', // For step 22
                'pf_22' => $decodedData[$step]['pf_22'] ?? '', // For step 22
                'esi_22' => $decodedData[$step]['esi_22'] ?? '', // For step 22

                'ee_16' => $decodedData[$step]['ee_16'] ?? '', 
                'ele_16' => $decodedData[$step]['ele_16'] ?? '', 
                'year_16' => $decodedData[$step]['year_16'] ?? '', 
                'yrl_16' => $decodedData[$step]['yrl_16'] ?? '', 
                'acc_16' => $decodedData[$step]['acc_16'] ?? '', 
                'int_16' => $decodedData[$step]['int_16'] ?? '', 
                'tot_16' => $decodedData[$step]['tot_16'] ?? '', 

                
                '4_tot_ee' => $decodedData[$step]['4_tot_ee'] ?? '', 
                '4_reg_fee' => $decodedData[$step]['4_reg_fee'] ?? '', 
                '4_sec_dep' => $decodedData[$step]['4_sec_dep'] ?? '', 

            ];
        }
    }

    protected function initializeEmptyForm()
    {
        foreach ($this->stepNames as $step) {
            $this->processSteps[$step] = [
                'checkbox' => false,
                'comment' => '',
                'user_name' => '',
                'date_time' => '',
                'savedby' => '',
                '7_unam' => '',
                '7_pass' => '',
                'mobile_number' => '',
                'ann_fees' => '',
                'reg_fees' => '',
                'pmrpy_fees' => '',
                'pt_22' => '',
                'pf_22' => '',
                'esi_22' => '',
            ];
        }
    }

    public function updatedProcessSteps($value, $key)
    {
        // Parse the key to get step and field
        $parts = explode('.', $key);
        $stepName = $parts[0];
        $fieldName = $parts[1];

        // If checkbox is checked, update timestamp and user
        if ($fieldName === 'checkbox' && $value) {
            $this->processSteps[$stepName]['date_time'] = now()->format('Y-m-d H:i:s');
            $this->processSteps[$stepName]['user_name'] = auth()->user()->name ?? 'System';
            $this->processSteps[$stepName]['savedby'] = auth()->user()->name ?? 'System';
        }
    }

    public function toggleStep($stepName)
    {
        $currentState = $this->processSteps[$stepName]['checkbox'];
        $this->processSteps[$stepName]['checkbox'] = !$currentState;

        if (!$currentState) {
            // If checking the box, set timestamp and user
            $this->processSteps[$stepName]['date_time'] = now()->format('Y-m-d H:i:s');
            $this->processSteps[$stepName]['user_name'] = auth()->user()->name ?? 'System';
            $this->processSteps[$stepName]['savedby'] = auth()->user()->name ?? 'System';
        }
    }

    public function saveForm()
    {
        try {
            $this->loading = true;

            // Prepare data for saving
            $jsonData = [];
            foreach ($this->stepNames as $step) {
                $stepData = $this->processSteps[$step];
                
                // Convert boolean checkbox to 'on' string for consistency
                $stepData['checkbox'] = $stepData['checkbox'] ? 'on' : 'off';
                
                // Remove empty fields to keep JSON clean
                $jsonData[$step] = array_filter($stepData, function($value) {
                    return $value !== '' && $value !== null;
                });
            }

            // Save to database - you'll need to implement this based on your model
            $this->saveToDatabase($jsonData);

            // Show success message
            session()->flash('message', 'Process chart data saved successfully!');
            session()->flash('alert-type', 'success');

        } catch (\Exception $e) {
            session()->flash('message', 'Error saving data: ' . $e->getMessage());
            session()->flash('alert-type', 'error');
        } finally {
            $this->loading = false;
        }
    }

    protected function saveToDatabase($jsonData)
    {
        // Example implementation - adjust based on your model
        $ticket = Ticket::find($this->ticketId);
        if ($ticket) {
            $ticket->update([
                'json_data' => json_encode($jsonData),
                'updated_at' => now()
            ]);
        }
        
        // For now, just log the data or handle as needed
        logger('ESI Process Chart Data:', $jsonData);
        
        $this->dispatch('notify-success', "Data saved!");

    }

    public function getFormattedDateTime($dateTime)
    {
        if (!$dateTime) return '';
        
        try {
            $date = Carbon::createFromFormat('Y-m-d H:i:s', $dateTime, 'UTC');
            $date->setTimezone($this->timezone);
            return $date->format('d-m-Y h:i A');
        } catch (\Exception $e) {
            return '';
        }
    }

    public function exportJson()
    {
        $jsonData = [];
        foreach ($this->stepNames as $step) {
            $stepData = $this->processSteps[$step];
            $stepData['checkbox'] = $stepData['checkbox'] ? 'on' : 'off';
            $jsonData[$step] = array_filter($stepData);
        }

        $fileName = 'esi_process_chart_' . ($this->ticketId ?? 'new') . '_' . date('Y-m-d_H-i-s') . '.json';
        
        return response()->streamDownload(function () use ($jsonData) {
            echo json_encode($jsonData, JSON_PRETTY_PRINT);
        }, $fileName, [
            'Content-Type' => 'application/json',
        ]);
    }
    public function render()
    {
        $TicketsData= $TicketsData = Ticket::leftJoin('nature_of_work', 'nature_of_work.id', '=', 'ticket.nature_of_work_id')
        ->leftJoin('branch', 'branch.id', '=', 'ticket.branch_id')
         ->select('ticket.*')->where('ticket.id',$this->id)->first();
        return view('livewire.masterForm.'.$this->form,['TicketsData' => $TicketsData,'json_data'=>$TicketsData->json_data])->layout('layouts.app');
    }
}