<?php

namespace App\Livewire;

use App\Models\BillingDetail;
use App\Models\BillingMaster;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Team;
use Illuminate\Support\Facades\Auth;
use Str;


use Http;
use Livewire\Attributes\On;
use Log;


class BillingCollection extends Component
{
    use WithPagination;

    public $moduleTitle = BILLING_MASTER_TITLE;
    public $perPage = PER_PAGE;
    public $isBillingFormOpen = false;
    public $isDetailsFormOpen = false;
    public $viewMode = false;
    public $selected_team_id;
    public $invoice_matter;
    public $teams = [];

    // Billing Details Form
    public $detailsUuid;
    public $gst_number;
    public $billing_address;
    public $joining_date;
    public $detail_status = 'active';
    public $currentBillingDetail = null;
    public $isNewRecord = true;

    // Billing Master Form
    public $uuid;
    public $billing_details_id;
    public $amount;
    public $billing_start_date;
    public $billing_end_date;
    public $status = 'raised';
    public $cancelled_reason;
    public $filteredBillingDetails = [];

    // Filters
    public $searchQuery = '';
    public $filterStatus = '';
    public $filterDateFrom = '';
    public $filterDateTo = '';

    public $commonCreateSuccess;
    public $commonUpdateSuccess;
    public $commonDeleteSuccess;
    public $commonStatusUpdateSuccess;
    public $commonNotDeleteSuccess;

    public function isAdmin()
    {
        return auth()->user()->email === env('ADMIN_EMAIL');
    }

    protected $rules = [
        'gst_number' => 'required|string|max:15',
        'billing_address' => 'required|string',
        'selected_team_id' => 'required|exists:teams,id',
        'invoice_matter' => 'required|string|max:255',
        'joining_date' => 'required|date',
        'detail_status' => 'required|in:active,inactive,decline',
        'billing_details_id' => 'required|exists:billing_details,id',
        'amount' => 'required|numeric|min:0',
        'billing_start_date' => 'required|date',
        'billing_end_date' => 'required|date|after:billing_start_date',
        'status' => 'required|in:raised,in_progress,paid,cancelled',
        'cancelled_reason' => 'required_if:status,cancelled'
    ];

    public function mount()
    {
        $this->joining_date = date('Y-m-d');
        $this->billing_start_date = date('Y-m-d');
        $this->billing_end_date = date('Y-m-d', strtotime('+30 days'));

        $this->teams = Team::all();

        $this->currentBillingDetail = BillingDetail::where('team_id', Auth::user()->currentTeam->id)->first();
        if ($this->currentBillingDetail) {
            $this->isNewRecord = false;
            $this->loadBillingDetails();
        }
        
        $this->commonCreateSuccess = str_replace('{module-name}', $this->moduleTitle, COMMON_CREATE_SUCCESS);
        $this->commonUpdateSuccess = str_replace('{module-name}', $this->moduleTitle, COMMON_UPDATE_SUCCESS);
        $this->commonDeleteSuccess = str_replace('{module-name}', $this->moduleTitle, COMMON_DELETE_SUCCESS);
        $this->commonStatusUpdateSuccess = str_replace('{module-name}', $this->moduleTitle, COMMON_STATUS_SUCCESS);
        $this->commonNotDeleteSuccess = str_replace('{module-name}', $this->moduleTitle, COMMON_DELETE_FAILURE);
    }

    public function toggleBillingForm($mode = 'add')
    {
        if (!$this->isAdmin()) {
            return;
        }
        
        $this->isBillingFormOpen = !$this->isBillingFormOpen;
        $this->viewMode = $mode === 'view';
        if (!$this->isBillingFormOpen) {
            $this->resetForm();
        }
    }


    public function toggleDetailsForm()
    {
        $this->isDetailsFormOpen = !$this->isDetailsFormOpen;
        if ($this->isDetailsFormOpen && !$this->isNewRecord) {
            $this->loadBillingDetails();
        }
    }

    public function resetForm()
    {
        $this->reset([
            'uuid', 'billing_details_id', 'amount', 
            'status', 'cancelled_reason', 'viewMode',
            'selected_team_id', 'invoice_matter','filteredBillingDetails'
        ]);
        $this->billing_start_date = date('Y-m-d');
        $this->billing_end_date = date('Y-m-d', strtotime('+30 days'));
    }

    public function resetDetailsForm()
    {
        if ($this->isNewRecord) {
            $this->reset(['gst_number', 'billing_address', 'detail_status']);
            $this->joining_date = date('Y-m-d');
        }
    }

    public function resetFilters()
    {
        $this->reset(['searchQuery', 'filterStatus', 'filterDateFrom', 'filterDateTo']);
    }

    protected function loadBillingDetails()
    {
        if ($this->currentBillingDetail) {
            $this->gst_number = $this->currentBillingDetail->gst_number;
            $this->billing_address = $this->currentBillingDetail->billing_address;
            $this->joining_date = $this->currentBillingDetail->joining_date->format('Y-m-d');
            $this->detail_status = $this->currentBillingDetail->status;
        }
    }

    public function saveBillingDetails()
    {
        $this->validate([
            'gst_number' => 'required|string|max:15',
            'billing_address' => 'required|string',
            'joining_date' => 'required|date',
            'detail_status' => 'required|in:active,inactive,decline'
        ]);

        try {
            if ($this->isNewRecord) {
                // First time save
                $this->currentBillingDetail = new BillingDetail();
                $this->currentBillingDetail->uuid = Str::uuid();
                $this->currentBillingDetail->team_id = Auth::user()->currentTeam->id;
            }

            // Update or create the record
            $this->currentBillingDetail->fill([
                'gst_number' => $this->gst_number,
                'billing_address' => $this->billing_address,
                'joining_date' => $this->joining_date,
                'status' => $this->detail_status
            ]);

            $this->currentBillingDetail->save();
            
            $this->isNewRecord = false;
            $this->dispatch('notify-success', $this->isNewRecord ? 
                'Billing details saved successfully. You can now update them.' : 
                'Billing details updated successfully.');
        } catch (\Exception $e) {
            \Log::error('Error handling billing details: ' . $e->getMessage());
            $this->dispatch('notify-error', 'An error occurred. Please try again.');
        }
    }

    public function editBillingDetails($uuid)
    {
        $detail = BillingDetail::where('uuid', $uuid)->first();
        if ($detail) {
            $this->detailsUuid = $detail->uuid;
            $this->gst_number = $detail->gst_number;
            $this->billing_address = $detail->billing_address;
            $this->joining_date = $detail->joining_date->format('Y-m-d');
            $this->detail_status = $detail->status;
            $this->isDetailsFormOpen = true;
        }
    }

    public function updateBillingDetails()
    {
        $this->validate([
            'gst_number' => 'required|string|max:15',
            'billing_address' => 'required|string',
            'joining_date' => 'required|date',
            'detail_status' => 'required|in:active,inactive,decline'
        ]);

        try {
            $detail = BillingDetail::where('uuid', $this->detailsUuid)->first();
            if ($detail) {
                $detail->fill([
                    'gst_number' => $this->gst_number,
                    'billing_address' => $this->billing_address,
                    'joining_date' => $this->joining_date,
                    'status' => $this->detail_status
                ]);

                $detail->save();
                $this->resetDetailsForm();
                $this->isDetailsFormOpen = false;
                $this->dispatch('notify-success', $this->commonUpdateSuccess);
            }
        } catch (\Exception $e) {
            \Log::error('Error updating billing details: ' . $e->getMessage());
            $this->dispatch('notify-error', 'Failed to update billing details. Please try again.');
        }
    }

    public function saveBilling()
    {
        if (!$this->isAdmin()) {
            $this->dispatch('notify-error', 'You are not authorized to perform this action.');
            return;
        }
        
        \Log::info('Saving billing...', [
            'selected_team_id' => $this->selected_team_id,
            'billing_details_id' => $this->billing_details_id,
            'invoice_matter' => $this->invoice_matter,
            'amount' => $this->amount,
            'billing_start_date' => $this->billing_start_date,
            'billing_end_date' => $this->billing_end_date,
            'status' => $this->status,
            'cancelled_reason' => $this->cancelled_reason
        ]);
        
        $this->validate([
            'selected_team_id' => 'required|exists:teams,id',
            'billing_details_id' => 'required|exists:billing_details,id',
            'amount' => 'required|numeric|min:0',
            'invoice_matter' => 'required|string|max:255',
            'billing_start_date' => 'required|date',
            'billing_end_date' => 'required|date|after:billing_start_date',
            'status' => 'required|in:raised,in_progress,paid,cancelled',
            'cancelled_reason' => 'required_if:status,cancelled'
        ]);
        
         try {
            $billing = new BillingMaster();
            $billing->uuid = Str::uuid();
            $billing->fill([
                'team_id' => Auth::user()->currentTeam->id,
                'selected_team_id' => $this->selected_team_id,
                'billing_details_id' => $this->billing_details_id,
                'invoice_matter' => $this->invoice_matter,
                'amount' => $this->amount,
                'billing_start_date' => $this->billing_start_date,
                'billing_end_date' => $this->billing_end_date,
                'status' => $this->status,
                'cancelled_reason' => $this->cancelled_reason,
                'created_by' => Auth::id()
            ]);

            $billing->save();
            $this->resetForm();
            $this->isBillingFormOpen = false;
            $this->dispatch('notify-success', $this->commonCreateSuccess);
            
            \Log::info('Billing saved successfully', ['billing_id' => $billing->id]);
        } catch (\Exception $e) {
            \Log::error('Error saving billing: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            $this->dispatch('notify-error', 'Failed to save billing. Please try again.');
        }
    }

    public function editBilling($uuid)
    {
        $billing = BillingMaster::where('uuid', $uuid)->first();
        
        if ($billing && $billing->status === 'paid') {
            $this->dispatch('notify-error', 'Paid invoices cannot be edited');
            return;
        }
        
        if ($billing) {
                        
            $this->uuid = $billing->uuid;
            
            $this->selected_team_id = $billing->selected_team_id;
            
            $this->filteredBillingDetails = BillingDetail::where('status', 'active')
            ->where('team_id', $this->selected_team_id)
            ->get();
            
            $this->billing_details_id = $billing->billing_details_id;
            $this->invoice_matter = $billing->invoice_matter;
            $this->amount = $billing->amount;
            $this->billing_start_date = $billing->billing_start_date->format('Y-m-d');
            $this->billing_end_date = $billing->billing_end_date->format('Y-m-d');
            $this->status = $billing->status;
            $this->cancelled_reason = $billing->cancelled_reason;
            $this->isBillingFormOpen = true;
        }
    }

    // this method update billing details when team is selected
    public function updatedSelectedTeamId($value)
    {
        if ($value) {
            $this->filteredBillingDetails = BillingDetail::where('status', 'active')
                ->where('team_id', $value)
                ->get();
        } else {
            $this->filteredBillingDetails = collect();
        }

        // Reset billing details selection when team changes
        $this->billing_details_id = '';
    }

    public function viewBilling($uuid)
    {
        $this->editBilling($uuid);
        $this->viewMode = true;
    }

    public function updateBilling()
    {

        if (!$this->isAdmin()) {
            $this->dispatch('notify-error', 'You are not authorized to perform this action.');
            return;
        }
        
        $this->validate([
            'selected_team_id' => 'required|exists:teams,id',
            'billing_details_id' => 'required|exists:billing_details,id',
            'invoice_matter' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'billing_start_date' => 'required|date',
            'billing_end_date' => 'required|date|after:billing_start_date',
            'status' => 'required|in:raised,in_progress,paid,cancelled',
            'cancelled_reason' => 'required_if:status,cancelled'
        ]);

        try {
            $billing = BillingMaster::where('uuid', $this->uuid)->first();
            if ($billing) {
                $billing->fill([
                    'selected_team_id' => $this->selected_team_id,
                    'billing_details_id' => $this->billing_details_id,
                    'invoice_matter' => $this->invoice_matter,
                    'amount' => $this->amount,
                    'billing_start_date' => $this->billing_start_date,
                    'billing_end_date' => $this->billing_end_date,
                    'status' => $this->status,
                    'cancelled_reason' => $this->cancelled_reason
                ]);

                $billing->save();
                $this->resetForm();
                $this->isBillingFormOpen = false;
                $this->dispatch('notify-success', $this->commonUpdateSuccess);
            }
        } catch (\Exception $e) {
            \Log::error('Error updating billing: ' . $e->getMessage());
            $this->dispatch('notify-error', 'Failed to update billing. Please try again.');
        }
    }

    public function deleteBilling($uuid)
    {
        try {
            $billing = BillingMaster::where('uuid', $uuid)->first();
            // if ($billing) {
            //     $billing->delete();
            //     $this->dispatch('notify-success', $this->commonDeleteSuccess);
            // }
            if (!$billing) {
                $this->dispatch('notify-error', 'Billing record not found');
                return;
            }

            if ($billing->status === 'paid') {
                $this->dispatch('notify-error', 'Paid invoices cannot be deleted');
                return;
            }

            $billing->delete();
            $this->dispatch('notify-success', $this->commonDeleteSuccess);
        
        } catch (\Exception $e) {
            \Log::error('Error deleting billing: ' . $e->getMessage());
            $this->dispatch('notify-error', 'Failed to delete billing. Please try again.');
        }
    }

    public function downloadInvoice($uuid)
    {
        try {
            $billing = BillingMaster::with(['billingDetail', 'creator', 'selectedTeam'])
                ->where('uuid', $uuid)
                ->first();

            if (!$billing) {
                \Log::error('Invoice not found', ['uuid' => $uuid]);
                $this->dispatch('notify-error', 'Invoice not found');
                return;
            }

            \Log::info('Generating PDF for invoice', [
                'uuid' => $uuid,
                'billing_details' => $billing->toArray()
            ]);

            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.billing-invoice', [
                'billing' => $billing,
                'generated_at' => now()->format('M d, Y H:i'),
                'company_name' => config('app.name')
            ]);

            usleep(500000);

            return response()->streamDownload(
                function() use ($pdf) {
                    echo $pdf->output();
                }, 
                'Docmey-invoice-' . substr($billing->uuid, 0, 8) . '.pdf'
            );
        } catch (\Exception $e) {
            \Log::error('PDF generation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            $this->dispatch('notify-error', 'Failed to generate invoice PDF: ' . $e->getMessage());
        }
    }

    public function updateStatus($uuid, $newStatus)
    {
        if (!$this->isAdmin()) {
            $this->dispatch('notify-error', 'You are not authorized to change the status');
            return false;
        }

        try {
            $billing = BillingMaster::where('uuid', $uuid)->first();
            
            if (!$billing) {
                $this->dispatch('notify-error', 'Billing record not found');
                return false;
            }

            // paid status cannot be changed
            if ($billing->status === 'paid') {
                $this->dispatch('notify-error', 'Paid invoices cannot have their status changed');
                return false;
            }

            if (!in_array($newStatus, ['raised', 'in_progress', 'paid', 'cancelled'])) {
                $this->dispatch('notify-error', 'Invalid status');
                return false;
            }

            $billing->status = $newStatus;
            $billing->save();
            
            $this->dispatch('notify-success', 'Status updated successfully');
            
            // Force component refresh to update the UI
            $this->render();

            $this->dispatch('status-updated-' . $uuid, [
                'status' => $newStatus
            ]);
            
            return true;

        } catch (\Exception $e) {
            \Log::error('Error updating billing status: ' . $e->getMessage());
            $this->dispatch('notify-error', 'Failed to update status');
            return false;
        }
    }

    public function render()
    {
        $currentTeamId = Auth::user()->currentTeam->id;

        $query = BillingMaster::with(['billingDetail', 'creator', 'selectedTeam'])
        ->where(function($q) use ($currentTeamId) {
            $q->where('team_id', $currentTeamId)
              ->orWhere('selected_team_id', $currentTeamId);
        });

        // Search filter
        if ($this->searchQuery) {
            $query->where(function($q) {
                $q->whereHas('billingDetail', function($subQ) {
                    $subQ->where('gst_number', 'like', '%' . $this->searchQuery . '%')
                        ->orWhere('billing_address', 'like', '%' . $this->searchQuery . '%');
                })
                ->orWhere('invoice_matter', 'like', '%' . $this->searchQuery . '%');
            });
        }

        // Status filter
        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        // Date filters
         if (!empty($this->filterDateFrom)) {
            $query->where('billing_start_date', '>=', $this->filterDateFrom);
        }

        if (!empty($this->filterDateTo)) {
            $query->where('billing_end_date', '<=', $this->filterDateTo);
        }
        
        $billings = $query->latest()->paginate($this->perPage);
        
        return view('livewire.billing.billing-collection', [
            'billings' => $billings,
            'billingDetails' => $this->filteredBillingDetails, 
            'teams' => Team::all()
        ])->layout('layouts.app');
    }
}