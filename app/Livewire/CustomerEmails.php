<?php

namespace App\Livewire;

use App\Models\EmailTemplate;
use App\Models\CustomerEmailLogs;
use App\Jobs\SendBulkEmailJob;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class CustomerEmails extends Component
{
    use WithPagination;

    // Email form properties
    public $subject = '';
    public $emailContent = '';
    public $selectedTemplate = '';
    
    // Filter properties
    public $filterStatus = '';
    public $filterStartDate;
    public $filterEndDate;
    
    // Customer selection
    public $selectAll = false;
    public $selectedCustomers = [];
    public $filterEmail = '';
    
    // Tab management
    public $activeTab = 'compose';

    
    // Template saving properties
    public $showTemplateModal = false;
    public $templateName = '';
    
    protected $rules = [
        'subject' => 'required|min:3|max:255',
        'emailContent' => 'required|min:10',
    ];

    public function mount()
    {
        // Initialize dates to current month
        $this->filterStartDate = now()->startOfMonth()->format('Y-m-d');
        $this->filterEndDate = now()->endOfMonth()->format('Y-m-d');
    }

    public function openTemplateModal()
    {
        $this->validateOnly('subject', ['subject' => 'required|min:3']);
        $this->validateOnly('emailContent', ['emailContent' => 'required|min:10']);
        
        $this->showTemplateModal = true;
    }
    
    public function closeTemplateModal()
    {
        $this->showTemplateModal = false;
        $this->templateName = '';
        $this->resetErrorBag('templateName');
    }
    public function saveAsTemplate()
    {
        // Dispatch event to sync TinyMCE content before saving
        $this->dispatch('syncEditor');
        
        $this->validate([
            'templateName' => 'required|min:3|max:255',
            'subject' => 'required|min:3|max:255',
            'emailContent' => 'required|min:10',
        ]);
        
        EmailTemplate::create([
            'name' => $this->templateName,
            'subject' => $this->subject,
            'content' => $this->emailContent,
            'user_id' => Auth::id(),
            'is_active' => true,
        ]);
        
        $this->closeTemplateModal();
        session()->flash('message', 'Email template saved successfully!');
    }
   public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
        
        // If template is selected, update the content
        if ($propertyName === 'selectedTemplate' && !empty($this->selectedTemplate)) {
            $template = EmailTemplate::find($this->selectedTemplate);
            if ($template) {
                $this->subject = $template->subject;
                $this->emailContent = $template->content;
                
                // This explicit dispatch ensures TinyMCE updates with the new template content
                $this->dispatch('refreshEditor');
            }
        }
        
        // Handle select all customers
        if ($propertyName === 'selectAll') {
            if ($this->selectAll) {
                $this->selectedCustomers = $this->getFilteredCustomers()->pluck('id')->toArray();
            } else {
                $this->selectedCustomers = [];
            }
        }
    }
    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function resetFilters()
    {
        $this->filterStartDate = now()->startOfMonth()->format('Y-m-d');
        $this->filterEndDate = now()->endOfMonth()->format('Y-m-d');
        $this->filterStatus = '';
        $this->filterEmail = '';
    }

    public function sendEmails()
    {
        $this->validate();
        
        // Ensure at least one customer is selected
        if (empty($this->selectedCustomers)) {
            session()->flash('error', 'Please select at least one customer to send emails');
            return;
        }
        
        // Create a job batch to send emails
        $customers = User::whereIn('id', $this->selectedCustomers)->get();
        
        // Dispatch job to queue
        SendBulkEmailJob::dispatch(
            $customers,
            $this->subject,
            $this->emailContent,
            Auth::id()
        );
        
        // Create email log entry
        CustomerEmailLogs::create([
            'subject' => $this->subject,
            'content' => $this->emailContent,
            'recipients_count' => count($this->selectedCustomers),
            'status' => 'queued',
            'user_id' => Auth::id()
        ]);
        
        // Reset form
        $this->reset(['subject', 'emailContent', 'selectedTemplate', 'selectedCustomers', 'selectAll']);
        
        session()->flash('message', 'Emails have been queued for sending in the background');
        
        // Switch to logs tab to see progress
        $this->activeTab = 'logs';
    }
    
    private function getFilteredCustomers()
    {
        return User::when($this->filterEmail, function ($query) {
            return $query->where('email', 'like', '%' . $this->filterEmail . '%');
        });
    }
    
    public function getEmailLogs()
    {
        return CustomerEmailLogs::when($this->filterStartDate, function ($query) {
            return $query->whereDate('created_at', '>=', $this->filterStartDate);
        })
        ->when($this->filterEndDate, function ($query) {
            return $query->whereDate('created_at', '<=', $this->filterEndDate);
        })
        ->when($this->filterStatus, function ($query) {
            return $query->where('status', $this->filterStatus);
        })
        ->orderBy('created_at', 'desc')
        ->paginate(10);
    }
    
    public function render()
    {
        return view('livewire.group-email.email-collection', [
            'emailLogs' => $this->getEmailLogs(),
            'customers' => $this->getFilteredCustomers()->paginate(10),
            'templates' => EmailTemplate::all(),
        ])->layout('layouts.app');
    }
}
