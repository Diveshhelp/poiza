<?php

namespace App\Livewire;

use App\Models\DioraCustomer;
use Livewire\Component;
use Livewire\WithPagination;

class DioraCustomerManager extends Component
{
    use WithPagination;

    public $search = '';
    public $isModalOpen = false;
    public $isEditMode = false;
    public $isViewModalOpen = false;
    public $customerId;

    // Form & View Fields
    public $customer_name, $company_name, $customer_phone, $customer_email;
    public $billing_address, $shipping_address, $gstin, $city, $state;
    public $viewCustomer = null;

    protected function rules()
    {
        return [
            'customer_name'  => 'required|string|max:255',
            'company_name'   => 'nullable|string|max:255',
            'customer_phone' => 'nullable|string|max:50',
            'customer_email' => 'nullable|email|max:255',
            'gstin'          => 'nullable|string|max:50',
            'city'           => 'nullable|string|max:100',
            'state'          => 'nullable|string|max:100',
            'billing_address'  => 'nullable|string',
            'shipping_address' => 'nullable|string',
        ];
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openModal()
    {
        $this->resetForm();
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetForm();
    }

    public function closeViewModal()
    {
        $this->isViewModalOpen = false;
        $this->viewCustomer = null;
    }

    private function resetForm()
    {
        $this->customerId = null;
        $this->customer_name = '';
        $this->company_name = '';
        $this->customer_phone = '';
        $this->customer_email = '';
        $this->billing_address = '';
        $this->shipping_address = '';
        $this->gstin = '';
        $this->city = '';
        $this->state = '';
        $this->isEditMode = false;
    }

    public function store()
    {
        $this->validate();

        DioraCustomer::updateOrCreate(
            ['id' => $this->customerId],
            [
                'customer_name'    => $this->customer_name,
                'company_name'     => $this->company_name,
                'customer_phone'   => $this->customer_phone,
                'customer_email'   => $this->customer_email,
                'billing_address'  => $this->billing_address,
                'shipping_address' => $this->shipping_address,
                'gstin'            => $this->gstin,
                'city'             => $this->city,
                'state'            => $this->state,
            ]
        );

        session()->flash('message', $this->customerId ? 'Customer updated successfully.' : 'Customer created successfully.');
        $this->closeModal();
    }

    public function edit($id)
    {
        $customer = DioraCustomer::findOrFail($id);
        $this->customerId = $customer->id;
        $this->customer_name = $customer->customer_name;
        $this->company_name = $customer->company_name;
        $this->customer_phone = $customer->customer_phone;
        $this->customer_email = $customer->customer_email;
        $this->billing_address = $customer->billing_address;
        $this->shipping_address = $customer->shipping_address;
        $this->gstin = $customer->gstin;
        $this->city = $customer->city;
        $this->state = $customer->state;

        $this->isEditMode = true;
        $this->isModalOpen = true;
    }

    public function view($id)
    {
        $this->viewCustomer = DioraCustomer::findOrFail($id);
        $this->isViewModalOpen = true;
    }

    public function delete($id)
    {
        $customer = DioraCustomer::findOrFail($id);
        $customer->delete();
        session()->flash('message', 'Customer deleted successfully.');
    }

    public function render()
    {
        $customers = DioraCustomer::query()
            ->when($this->search, function($q) {
                $q->where('customer_name', 'like', '%' . $this->search . '%')
                  ->orWhere('company_name', 'like', '%' . $this->search . '%')
                  ->orWhere('customer_phone', 'like', '%' . $this->search . '%')
                  ->orWhere('city', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(10);

        return view('livewire.diora-customer-manager', [
            'customers' => $customers,
        ])->layout('layouts.app');
    }
}