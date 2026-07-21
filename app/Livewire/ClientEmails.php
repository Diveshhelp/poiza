<?php

namespace App\Livewire;

use App\Models\ClientEmailTemplate;
use App\Models\ClientEmailLog;
use App\Jobs\SendClientBulkEmailJob;
use App\Models\Clients;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Str;

class ClientEmails extends Component
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

    public $showAddClientModal = false;
    public $team_id;
    protected function rules()
    {
        return [
            'subject' => 'required|min:3|max:255',
            'emailContent' => 'required|min:10',
        ];
    }
    public $newClient = [
        'client_name' => '',
        'client_email' => '',
        'mobile_number' => '',
        'address' => '',
    ];

    // Listen for events that require page refresh
    protected $listeners = ['refreshCustomers' => '$refresh'];
    public function mount()
    {
        // Initialize dates to current month
        $this->filterStartDate = now()->startOfMonth()->format('Y-m-d');
        $this->filterEndDate = now()->endOfMonth()->format('Y-m-d');

        $this->team_id=Auth::user()->currentTeam->id;
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
        
        ClientEmailTemplate::create([
            'uuid' => Str::uuid(),
            'name' => $this->templateName,
            'subject' => $this->subject,
            'content' => $this->emailContent,
            'user_id' => Auth::id(),
            'team_id' => Auth::user()->currentTeam->id,
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
            $template = ClientEmailTemplate::find($this->selectedTemplate);
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
        $customers = Clients::where("team_id",$this->team_id)->whereIn('id', $this->selectedCustomers)->get();
        
        // Dispatch job to queue
        SendClientBulkEmailJob::dispatch(
            $customers,
            $this->subject,
            $this->emailContent,
            Auth::id()
        );
        
        // Create email log entry
        ClientEmailLog::create([
            'uuid' => Str::uuid(),
            'subject' => $this->subject??'',
            'content' => $this->emailContent??'',
            'recipients_count' => count($this->selectedCustomers),
            'status' => 'queued',
            'user_id' => Auth::id(),
            'team_id'=>$this->team_id
        ]);

        
        // Reset form
        $this->reset(['subject', 'emailContent', 'selectedTemplate', 'selectedCustomers', 'selectAll']);
        
        session()->flash('message', 'Emails have been queued for sending in the background');
        
        // Switch to logs tab to see progress
        $this->activeTab = 'logs';
    }
    
    private function getFilteredCustomers()
    {
        return Clients::where("team_id",$this->team_id)->when($this->filterEmail, function ($query) {
            return $query->where('client_email', 'like', '%' . $this->filterEmail . '%');
        });
    }
    
    public function getEmailLogs()
    {
        return ClientEmailLog::where("team_id",$this->team_id)->when($this->filterStartDate, function ($query) {
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
    
    
    // Create client method with return value for Alpine.js
    public function createClient()
    {
        $this->validate([
            'newClient.client_name' => 'required|string|max:255',
            'newClient.client_email' => 'required|email|max:255|unique:clients,client_email',
            'newClient.mobile_number' => 'nullable|string|max:20',
            'newClient.address' => 'nullable|string',
        ]);
        
        
        try {
            $client = Clients::create([
                'uuid' => Str::uuid(),
                'client_name' => $this->newClient['client_name'],
                'client_email' => $this->newClient['client_email'],
                'mobile_number' => $this->newClient['mobile_number'],
                'address' => $this->newClient['address'],
                'team_id' => auth()->user()->currentTeam->id,
                'email_count' => 0,
                'sms_count' => 0,
                'whatsapp_count' => 0,
                'is_active' => true,
            ]);
            
            // Add the newly created client to selected customers
            $this->selectedCustomers[] = (string) $client->id;
            
            // Reset the form
            $this->resetNewClientForm();
            
            // Show notification
            $this->dispatch('notify-success', 'Client added successfully!');
              // Dispatch an event to close the modal via Alpine.js
            $this->dispatch('client-added');
            
            return true;

        } catch (\Exception $e) {
            $this->dispatch('notify-error',  $e->getMessage());
        }
    }
    
    public function resetNewClientForm()
    {
        $this->newClient = [
            'client_name' => '',
            'client_email' => '',
            'mobile_number' => '',
            'address' => '',
        ];
        $this->resetValidation();
    }
    
    public function getPreviewContentProperty()
    {
        if (empty($this->emailContent)) {
            return null;
        }
        
        // Replace placeholders with sample data
        $content = $this->emailContent;
        $content = str_replace('{name}', 'John Doe', $content);
        $content = str_replace('{email}', 'john.doe@example.com', $content);
        $content = str_replace('{id}', '12345', $content);
        
        // For security, parse the HTML with a library like HTML Purifier
        // For simplicity in this example, we'll use nl2br and htmlspecialchars
        if (!str_contains($content, '<')) {
            $content = nl2br(htmlspecialchars($content));
        }
        
        return $content;
    }
    
    // Add a hook to refresh preview when content changes
    public function updatedEmailContent()
    {
        // The preview will be automatically updated
    }
    
    public function render()
    {
        return view('livewire.group-email.client-email-collection', [
            'emailLogs' => $this->getEmailLogs(),
            'customers' => $this->getFilteredCustomers()->paginate(10),
            'templates' => ClientEmailTemplate::all(),
            'previewContent' => $this->previewContent,
        ])->layout('layouts.app');
    }
}
