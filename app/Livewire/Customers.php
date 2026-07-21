<?php

namespace App\Livewire;

use App\Models\Customer;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class Customers extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $customerId;
    public $name, $company_name, $email, $phone, $gstin, $customer_type = 'wholesaler', $credit_limit = 0, $website, $address, $status = 'active';

    // View Modal Properties
    public $isViewModalOpen = false;
    public $viewingCustomer;
    public $isOpen = false;
    public $isEditMode = false;

    // CSV Import Property
    public $csvFile;
    public $isCsvModalOpen = false;

    protected $queryString = ['search' => ['except' => '']];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:customers,email,' . $this->customerId,
            'phone' => 'nullable|string|max:20',
            'gstin' => 'nullable|string|max:20',
            'customer_type' => 'required|in:wholesaler,retailer,distributor,manufacturer',
            'credit_limit' => 'nullable|numeric|min:0',
            'website' => 'nullable|url|max:255',
            'address' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ];
    }

    public function render()
    {
        $customers = Customer::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('company_name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%')
                      ->orWhere('phone', 'like', '%' . $this->search . '%')
                      ->orWhere('gstin', 'like', '%' . $this->search . '%')
                      ->orWhere('uuid', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(10);

        return view('livewire.customers', [
            'customers' => $customers
        ])->layout('layouts.app');
    }
    public function view($id)
    {
        $this->viewingCustomer = Customer::findOrFail($id);
        $this->isViewModalOpen = true;
    }

    public function closeViewModal()
    {
        $this->isViewModalOpen = false;
        $this->viewingCustomer = null;
    }public function toggleStatus($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->status = $customer->status === 'active' ? 'inactive' : 'active';
        $customer->save();

        session()->flash('message', "Customer status updated to {$customer->status}.");
    }

    public function create()
    {
        $this->resetInputFields();
        $this->isOpen = true;
        $this->isEditMode = false;
    }

    public function store()
    {
        $this->validate();

        // Prepare data array with conditional uuid generation for new records
        $data = [
            'name' => $this->name,
            'company_name' => $this->company_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'gstin' => $this->gstin,
            'customer_type' => $this->customer_type,
            'credit_limit' => $this->credit_limit ?: 0,
            'website' => $this->website,
            'address' => $this->address,
            'status' => $this->status,
        ];

        if (!$this->customerId) {
            $data['uuid'] = (string) Str::uuid();
        }

        Customer::updateOrCreate(['id' => $this->customerId], $data);

        session()->flash('message', $this->customerId ? 'Customer updated successfully.' : 'Customer created successfully.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $customer = Customer::findOrFail($id);
        $this->customerId = $customer->id;
        $this->name = $customer->name;
        $this->company_name = $customer->company_name;
        $this->email = $customer->email;
        $this->phone = $customer->phone;
        $this->gstin = $customer->gstin;
        $this->customer_type = $customer->customer_type;
        $this->credit_limit = $customer->credit_limit;
        $this->website = $customer->website;
        $this->address = $customer->address;
        $this->status = $customer->status;

        $this->isEditMode = true;
        $this->isOpen = true;
    }

    public function delete($id)
    {
        Customer::find($id)?->delete();
        session()->flash('message', 'Customer deleted successfully.');
    }

    // CSV Modal Actions
    public function openCsvModal()
    {
        $this->csvFile = null;
        $this->isCsvModalOpen = true;
    }

    public function closeCsvModal()
    {
        $this->isCsvModalOpen = false;
        $this->csvFile = null;
    }

    public function exportSampleCsv(): StreamedResponse
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="customers_sample.csv"',
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');
            
            // Header Row
            fputcsv($file, [
                'name', 
                'company_name', 
                'email', 
                'phone', 
                'gstin', 
                'customer_type', 
                'credit_limit', 
                'website', 
                'address', 
                'status'
            ]);

            // Dummy Row 1
            fputcsv($file, [
                'Rajesh Patel', 
                'Diora Hardware Store', 
                'rajesh@dioratouch.com', 
                '+919876543210', 
                '24AAAAA0000A1Z5', 
                'wholesaler', 
                '50000.00', 
                'https://example.com', 
                'Rajkot, Gujarat', 
                'active'
            ]);

            // Dummy Row 2
            fputcsv($file, [
                'Amit Sharma', 
                'Sharma Interio', 
                'amit@sharmainterio.com', 
                '+919123456789', 
                '24BBBBB1111B2Z6', 
                'distributor', 
                '100000.00', 
                'https://sharmainterio.com', 
                'Ahmedabad, Gujarat', 
                'active'
            ]);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function importCsv()
    {
        $this->validate([
            'csvFile' => 'required|mimes:csv,txt|max:2048',
        ]);

        $path = $this->csvFile->getRealPath();
        $file = fopen($path, 'r');
        
        $header = fgetcsv($file);
        $rowCount = 0;

        while (($row = fgetcsv($file)) !== false) {
            if (count($row) >= 1) {
                $data = [
                    'name'          => $row[0] ?? '',
                    'company_name'  => $row[1] ?? null,
                    'email'         => $row[2] ?? null,
                    'phone'         => $row[3] ?? null,
                    'gstin'         => $row[4] ?? null,
                    'customer_type' => strtolower($row[5] ?? 'wholesaler'),
                    'credit_limit'  => $row[6] ?? 0,
                    'website'       => $row[7] ?? null,
                    'address'       => $row[8] ?? null,
                    'status'        => strtolower($row[9] ?? 'active'),
                ];

                $validator = Validator::make($data, [
                    'name' => 'required|string|max:255',
                    'email' => 'nullable|email',
                    'customer_type' => 'in:wholesaler,retailer,distributor,manufacturer',
                    'status' => 'in:active,inactive',
                ]);

                if (!$validator->fails()) {
                    // Generate uuid if creating or if it doesn't exist
                    Customer::updateOrCreate(
                        ['email' => $data['email']],
                        array_merge($data, [
                            'uuid' => Customer::where('email', $data['email'])->value('uuid') ?? (string) Str::uuid()
                        ])
                    );
                    $rowCount++;
                }
            }
        }

        fclose($file);

        session()->flash('message', "Successfully imported {$rowCount} customer records from CSV.");
        $this->closeCsvModal();
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    private function resetInputFields()
    {
        $this->customerId = null;
        $this->name = '';
        $this->company_name = '';
        $this->email = '';
        $this->phone = '';
        $this->gstin = '';
        $this->customer_type = 'wholesaler';
        $this->credit_limit = 0;
        $this->website = '';
        $this->address = '';
        $this->status = 'active';
    }
}