<?php

namespace App\Livewire;

use App\Models\StockCustomer;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Str;

class StockCustomerCollection extends Component
{
    use WithPagination;

    public $moduleTitle = STOCK_CUSTOMERS_TITLE;
    public $perPage = PER_PAGE;
    public $isFormOpen = false;

    // Form fields
    public $uuid;
    public $name;
    public $email;
    public $phone;
    public $address;
    public $city;
    public $state;
    public $postal_code;
    public $country;
    public $notes;
    public $status = 'active';

    // Filters
    public $searchQuery = '';
    public $filterStatus = '';
    public $filterCity = '';
    public $filterState = '';


    public $commonCreateSuccess;
    public $commonUpdateSuccess;
    public $commonDeleteSuccess;
    public $commonStatusUpdateSuccess;
    public $commonNotDeleteSuccess;
    
    protected $rules = [
        'name' => 'required|string|min:3|max:255',
        'email' => 'nullable|email|max:255',
        'phone' => 'nullable|string|max:20',
        'address' => 'nullable|string|max:1000',
        'city' => 'nullable|string|max:100',
        'state' => 'nullable|string|max:100',
        'postal_code' => 'nullable|string|max:20',
        'country' => 'nullable|string|max:100',
        'notes' => 'nullable|string|max:1000',
        'status' => 'required|in:active,inactive'
    ];

    public function mount()
    {
        $this->resetForm();
        $this->commonCreateSuccess = str_replace('{module-name}', $this->moduleTitle, COMMON_CREATE_SUCCESS);
        $this->commonUpdateSuccess = str_replace('{module-name}', $this->moduleTitle, COMMON_UPDATE_SUCCESS);
        $this->commonDeleteSuccess = str_replace('{module-name}', $this->moduleTitle, COMMON_DELETE_SUCCESS);
        $this->commonStatusUpdateSuccess = str_replace('{module-name}', $this->moduleTitle, COMMON_STATUS_SUCCESS);
        $this->commonNotDeleteSuccess = str_replace('{module-name}', $this->moduleTitle, COMMON_DELETE_FAILURE);
    }

    public function toggleForm()
    {
        $this->isFormOpen = !$this->isFormOpen;
        if (!$this->isFormOpen) {
            $this->resetForm();
        }
    }

    public function resetForm()
    {
        $this->reset([
            'uuid', 'name', 'email', 'phone', 'address',
            'city', 'state', 'postal_code', 'country',
            'notes', 'status'
        ]);
    }

    public function resetFilters()
    {
        $this->reset(['searchQuery', 'filterStatus', 'filterCity', 'filterState']);
    }

    public function saveCustomer()
    {
        $this->validate();

        $customer = new StockCustomer();
        $customer->uuid = Str::uuid();
        $customer->fill([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'city' => $this->city,
            'state' => $this->state,
            'postal_code' => $this->postal_code,
            'country' => $this->country,
            'notes' => $this->notes,
            'status' => $this->status,
            'team_id' => Auth::user()->currentTeam->id,
            'user_id' => Auth::id(),
            'created_by' => Auth::id()
        ]);

        $customer->save();
        $this->resetForm();
        $this->isFormOpen = false;
        $this->dispatch('notify-success', $this->commonCreateSuccess);
    }

    public function editCustomer($uuid)
    {
        $customer = StockCustomer::where('uuid', $uuid)->first();
        if ($customer) {
            foreach ($customer->toArray() as $key => $value) {
                if (property_exists($this, $key)) {
                    $this->$key = $value;
                }
            }
            $this->isFormOpen = true;
        }
    }

    public function updateCustomer()
    {
        $this->validate();

        $customer = StockCustomer::where('uuid', $this->uuid)->first();
        if ($customer) {
            $customer->fill([
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'address' => $this->address,
                'city' => $this->city,
                'state' => $this->state,
                'postal_code' => $this->postal_code,
                'country' => $this->country,
                'notes' => $this->notes,
                'status' => $this->status
            ]);

            $customer->save();
            $this->resetForm();
            $this->isFormOpen = false;
            $this->dispatch('notify-success', $this->commonUpdateSuccess);
        }
    }

    public function deleteCustomer($uuid)
    {
        $customer = StockCustomer::where('uuid', $uuid)->first();
        if ($customer) {
            $customer->delete();
            $this->dispatch('notify-success', $this->commonDeleteSuccess);
        }
    }

    public function render()
    {
        $query = StockCustomer::query()
            ->where('team_id', Auth::user()->currentTeam->id);

        if ($this->searchQuery) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->searchQuery . '%')
                  ->orWhere('email', 'like', '%' . $this->searchQuery . '%')
                  ->orWhere('phone', 'like', '%' . $this->searchQuery . '%');
            });
        }

        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        if ($this->filterCity) {
            $query->where('city', $this->filterCity);
        }

        if ($this->filterState) {
            $query->where('state', $this->filterState);
        }

        $customers = $query->latest()->paginate($this->perPage);

        return view('livewire.stock-management.stock-customer-collection', [
            'customers' => $customers
        ])->layout('layouts.app');
    }
}