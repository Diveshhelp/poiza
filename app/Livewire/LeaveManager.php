<?php
// File :- LeaveManager.php
namespace App\Livewire;

use App\Traits\HasSubscriptionCheck;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Leave;
use App\Models\LeaveAttachment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Log;

class LeaveManager extends Component
{
    use WithFileUploads;
    use HasSubscriptionCheck;
    public $moduleTitle = LEAVE_TITLE;
    public $commonCreateSuccess;
    public $commonUpdateSuccess;
    public $commonDeleteSuccess;

    public $uuid;
    public $start_date;
    public $end_date;
    public $total_days = 0;
    public $is_full_day = 'yes';
    public $leave_half;
    public $reason;
    public $temp_files = [];
    public $leave_attachments = [];
    public $isEditing = false;
    public $existing_attachments = [];
    public $fileUrl;
    // public $status = 'pending';
    public $team_id;

    protected function rules()
    {
        return [
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'total_days' => 'required|numeric|min:0.5',
            'is_full_day' => 'required|in:yes,no',
            'leave_half' => 'nullable|required_if:is_full_day,no|in:first_half,second_half',
            'reason' => 'required|string',
            'temp_files.*' => 'nullable',
        ];
    }
    
    protected $messages = [
        'start_date.required' => 'Start date is required',
        'end_date.required' => 'End date is required',
        'end_date.after_or_equal' => 'End date must be equal to or after start date',
        'leave_half.required_if' => 'Please select which half of the day',
        'reason.required' => 'Please provide a reason for your leave request',
        'temp_files.*.max' => 'The file may not be greater than 50MB.',
        'temp_files.*.file ' => 'The file must be a valid file upload.',
        'temp_files.*.mimes' => 'The file must be a type of: jpg, jpeg, png, gif, pdf, doc.',
    ];

    
    public function mount($uuid = null)
    {
        if (!$this->ensureSubscription()) {
            abort(402, 'Access denied. Subscription is over, Please renew the plan and get the full access!');
        }
        Log::info('Mount called with UUID: ' . $uuid);
        $this->team_id = Auth::user()->currentTeam->id;
        $this->commonCreateSuccess = str_replace('{module-name}', $this->moduleTitle, COMMON_CREATE_SUCCESS);
        $this->commonUpdateSuccess = str_replace('{module-name}', $this->moduleTitle, COMMON_UPDATE_SUCCESS);
        $this->commonDeleteSuccess = str_replace('{module-name}', $this->moduleTitle, COMMON_DELETE_SUCCESS);
        
        if ($uuid) {
            $this->isEditing = true;
            $leave = Leave::where('uuid', $uuid)->firstOrFail();
            
            
            Log::info('Found leave record with ID: ' . $leave->id);
                    
            $this->uuid = $leave->uuid;
            $this->start_date = date('Y-m-d', strtotime($leave->start_date));
            $this->end_date = date('Y-m-d', strtotime($leave->end_date));
            $this->total_days = $leave->total_days;
            $this->is_full_day = $leave->is_full_day;
            $this->leave_half = $leave->leave_half;
            $this->reason = $leave->reason;
            
            // Load existing attachments
            $this->existing_attachments = LeaveAttachment::where('leave_id', $leave->id)->get();
            
            
            Log::info('Loaded ' . count($this->existing_attachments) . ' attachments');
        } else {
            $this->start_date = date('Y-m-d');
            $this->end_date = date('Y-m-d');
        }
        $this->calculateTotalDays();
    }

    public function openPreview($attachmentId)
    {
        $attachment = LeaveAttachment::findOrFail($attachmentId);
        $this->fileUrl = Storage::url($attachment->file_path);
        $this->dispatchBrowserEvent('openPreviewModal');
    }

    public function updatedStartDate()
    {
        $this->calculateTotalDays();
    }

    public function updatedEndDate()
    {
        $this->calculateTotalDays();
        
    }

    public function updatedIsFullDay()
    {
        if ($this->is_full_day === 'yes') {
            $this->leave_half = null;
        } else {
            $this->total_days = 0.5;
        }
    }

    private function calculateTotalDays()
    {
        if ($this->start_date && $this->end_date) {
            $start = Carbon::parse($this->start_date);
            $end = Carbon::parse($this->end_date);
            Log::info('Start Date: ' . $start);
            Log::info('End Date: ' . $end);
            
            $days = $start->diffInDays($end) + 1;
            Log::info('Days: ' . $days);
            if ($this->is_full_day === 'no') {
                $this->total_days = 0.5;
            } else {
                $this->total_days = $days;
            }
            Log::info("Total days calculated: {$this->total_days}");
        }
    }
    // private function calculateTotalDays()
    // {
    //     if ($this->start_date && $this->end_date) {
    //         $start = Carbon::parse($this->start_date);
    //         $end = Carbon::parse($this->end_date);
    //         Log::info('Start Date: ' . $start);
    //         Log::info('End Date: ' . $end);
            
    //         $days = $start->diffInDays($end) + 1;
    //         Log::info('Days: ' . $days);
            
    //         if ($this->is_full_day === 'no') {
    //             $this->total_days = $days - 0.5;
    //         } else {
    //             $this->total_days = $days;
    //         }
            
    //         Log::info("Total days calculated: {$this->total_days}");
    //     }
    // }

    public function removeFile($index)
    {
        if (isset($this->temp_files[$index])) {
            unset($this->temp_files[$index]);
            $this->temp_files = array_values($this->temp_files); 
        }
    }

    public function downloadAttachment($uuid)
    {
        try {
            $attachment = LeaveAttachment::where('uuid', $uuid)->firstOrFail();
            // $filePath = 'leave_attachments/' . $attachment->leave_id . '/' . $attachment->file_name;
            return Storage::disk('public')->download($attachment->file_path, $attachment->original_file_name);
        } catch (Exception $e) {
            $this->dispatch('banner-message', [
                'style' => 'error',
                'message' => 'Error downloading attachment: ' . $e->getMessage()
            ]);
        }
    }

    public function deleteAttachment($uuid)
    {
        try {
            $attachment = LeaveAttachment::where('uuid', $uuid)->firstOrFail();
            
            if (Storage::disk('public')->exists($attachment->file_path)) {
                Storage::disk('public')->delete($attachment->file_path);
            }
            
            $attachment->delete();
            
            $this->existing_attachments = $this->existing_attachments->filter(function($item) use ($uuid) {
                return $item->uuid !== $uuid;
            });

            $this->dispatch('banner-message', [
                'style' => 'success',
                'message' => $this->commonDeleteSuccess
            ]);
        } catch (Exception $e) {
            $this->dispatch('banner-message', [
                'style' => 'error',
                'message' => 'Error deleting attachment: ' . $e->getMessage()
            ]);
        }
    }

    public function saveLeave()
    {
        try {
            $this->validate();
            
            if ($this->isEditing) {
                $leave = Leave::where('uuid', $this->uuid)->firstOrFail();
            } else {
                $leave = new Leave();
                $leave->id=rand('100', '999') . time();
                $leave->uuid = (string) Str::uuid();
                $leave->user_id = Auth::id();
                $leave->status = 'pending';
            }
            
            $leave->start_date = $this->start_date;
            $leave->end_date = $this->end_date;
            $leave->total_days = $this->total_days;
            $leave->is_full_day = $this->is_full_day;
            $leave->leave_half = $this->is_full_day === 'no' ? $this->leave_half : null;
            $leave->reason = $this->reason;
            $leave->team_id = $this->team_id;
            
            $leave->save();
            
            // Handle file uploads
            foreach ($this->temp_files as $file) {
                $originalName = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $fileName = time() . '_' . Str::random(10) . '.' . $extension;
                $filePath = $file->storeAs("leave_attachments/{$leave->id}", $fileName, 'public');
                
                $attachment = new LeaveAttachment();
                $attachment->uuid = (string) Str::uuid();
                $attachment->leave_id = $leave->id;
                $attachment->file_name = $fileName;
                $attachment->original_file_name = $originalName;
                // $attachment->file_path = $filePath;
                $attachment->file_path = $fileName;
                $attachment->file_type = $file->getClientOriginalExtension();
                $attachment->file_size = $file->getSize();
                $attachment->save();
            }
            // Clear temp files
            $this->temp_files = [];
            $this->dispatch('notify-success', $this->commonCreateSuccess);
            $this->resetForm();
            // return redirect()->route('leave.index');
        } catch (Exception $e) {
            $this->dispatch('banner-message', [
                'style' => 'error',
                'message' => 'Error saving leave request: ' . $e->getMessage()
            ]);
        }
    }

    

    public function resetForm()
    {
        // Reset all form fields to initial state
        $this->reset([
            'start_date',
            'end_date',
            'total_days',
            'is_full_day',
            'leave_half',
            // 'approved_by',
            // 'approved_at',
            // 'rejected_by',
            // 'rejection_reason',
            // 'status',
            'reason'
        ]);

        // Reset default values
        $this->start_date = date('Y-m-d');
        $this->end_date = date('Y-m-d');
        $this->total_days = 0;
        $this->is_full_day = 'yes';
        
        // Reset file uploads
        $this->temp_files = [];
        $this->existing_attachments = [];
        
        // Reset validation errors
        $this->resetValidation();
        
        // Reset any error messages
        $this->resetErrorBag();
        
        // Reset editing state
        $this->isEditing = false;
        $this->uuid = null;

        //Reset default values section:
        // $this->status = 'pending';
        
        // Dispatch event for JavaScript listeners
        $this->dispatch('form-reset');
    }

    public function cancelForm()
    {
        $this->resetForm();
        return $this->redirect(route('leave.index'), navigate: true);
    }


    public function render()
    {
        $leaves = Leave::with('attachments')->get();
        return view('livewire.Leave.leave-manager',[
            'leaves' => $leaves,
            'fileUrl' => $this->fileUrl,
        ])->layout('layouts.app');
    }
}