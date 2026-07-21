<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class Products extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $productId;
    public $name, $sku, $category_id, $cost_price = 0, $selling_price = 0, $stock_quantity = 0, $alert_quantity = 5, $unit = 'pcs', $description, $status = 'active';

    public $isOpen = false;
    public $isEditMode = false;
    
    public $isViewModalOpen = false;
    public $viewingProduct;

    public $csvFile;
    public $isCsvModalOpen = false;

    // Quick Add Category Modal Properties
    public $isCategoryModalOpen = false;
    public $newCategoryName;
    public $newCategoryDescription;

    protected $queryString = ['search' => ['except' => '']];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:100|unique:products,sku,' . $this->productId,
            'category_id' => 'nullable|exists:categories,id',
            'cost_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'alert_quantity' => 'required|integer|min:0',
            'unit' => 'required|string|max:20',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ];
    }

    public function render()
    {
        $products = Product::with('category')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('sku', 'like', '%' . $this->search . '%')
                      ->orWhere('uuid', 'like', '%' . $this->search . '%')
                      ->orWhereHas('category', function ($q) {
                          $q->where('name', 'like', '%' . $this->search . '%');
                      });
            })
            ->latest()
            ->paginate(10);

        $categories = Category::where('status', 'active')->orderBy('name')->get();

        return view('livewire.products', [
            'products' => $products,
            'categories' => $categories
        ])->layout('layouts.app');
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

        $data = [
            'name' => $this->name,
            'sku' => strtoupper($this->sku),
            'category_id' => $this->category_id ?: null,
            'cost_price' => $this->cost_price,
            'selling_price' => $this->selling_price,
            'stock_quantity' => $this->stock_quantity,
            'alert_quantity' => $this->alert_quantity,
            'unit' => $this->unit,
            'description' => $this->description,
            'status' => $this->status,
        ];

        if (!$this->productId) {
            $data['uuid'] = (string) Str::uuid();
        }

        Product::updateOrCreate(['id' => $this->productId], $data);

        session()->flash('message', $this->productId ? 'Product updated successfully.' : 'Product created successfully.');

        $this->closeModal();
        $this->resetInputFields();
    }

    // Quick Add Category Actions
    public function openCategoryModal()
    {
        $this->newCategoryName = '';
        $this->newCategoryDescription = '';
        $this->isCategoryModalOpen = true;
        $this->isOpen = false;
    }

    public function closeCategoryModal()
    {
        $this->isCategoryModalOpen = false;
        $this->newCategoryName = '';
        $this->newCategoryDescription = '';
        $this->isOpen = true;
    }

    public function storeCategory()
    {
        $this->validate([
            'newCategoryName' => 'required|string|max:255|unique:categories,name',
            'newCategoryDescription' => 'nullable|string',
        ]);

        $category = Category::create([
            'uuid' => (string) Str::uuid(),
            'name' => trim($this->newCategoryName),
            'slug' => Str::slug($this->newCategoryName),
            'description' => $this->newCategoryDescription,
            'status' => 'active',
        ]);

        // Automatically assign and focus the newly created category in the product form dropdown
        $this->category_id = $category->id;

        session()->flash('message', 'New category created successfully.');
        $this->closeCategoryModal();
    }

    public function view($id)
    {
        $this->viewingProduct = Product::with('category')->findOrFail($id);
        $this->isViewModalOpen = true;
    }

    public function closeViewModal()
    {
        $this->isViewModalOpen = false;
        $this->viewingProduct = null;
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $this->productId = $product->id;
        $this->name = $product->name;
        $this->sku = $product->sku;
        $this->category_id = $product->category_id;
        $this->cost_price = $product->cost_price;
        $this->selling_price = $product->selling_price;
        $this->stock_quantity = $product->stock_quantity;
        $this->alert_quantity = $product->alert_quantity;
        $this->unit = $product->unit;
        $this->description = $product->description;
        $this->status = $product->status;

        $this->isEditMode = true;
        $this->isOpen = true;
    }

    public function toggleStatus($id)
    {
        $product = Product::findOrFail($id);
        $product->status = $product->status === 'active' ? 'inactive' : 'active';
        $product->save();

        session()->flash('message', "Product status updated to {$product->status}.");
    }

    public function delete($id)
    {
        Product::find($id)?->delete();
        session()->flash('message', 'Product deleted successfully.');
    }

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
            'Content-Disposition' => 'attachment; filename="products_sample.csv"',
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, ['name', 'sku', 'category_name', 'cost_price', 'selling_price', 'stock_quantity', 'alert_quantity', 'unit', 'description', 'status']);
            fputcsv($file, ['SS Mortise Handle 8Inch', 'DIORA-MH-08', 'Mortise Handles', '450.00', '850.00', '120', '10', 'pcs', 'Premium grade stainless steel finish.', 'active']);
            fputcsv($file, ['Heavy Duty Door Hinge 4x3', 'DIORA-HNG-43', 'Hinges', '80.00', '160.00', '350', '25', 'pair', 'Ball bearing heavy hinge.', 'active']);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function importCsv()
    {
        $this->validate([
            'csvFile' => 'required|mimes:csv,txt|max:2048',
        ]);

        $file = fopen($this->csvFile->getRealPath(), 'r');
        $header = fgetcsv($file);
        $rowCount = 0;

        while (($row = fgetcsv($file)) !== false) {
            if (count($row) >= 2) {
                $catId = null;
                $catName = trim($row[2] ?? '');
                if (!empty($catName)) {
                    $category = Category::firstOrCreate(
                        ['slug' => Str::slug($catName)],
                        ['name' => $catName, 'uuid' => (string) Str::uuid(), 'status' => 'active']
                    );
                    $catId = $category->id;
                }

                $data = [
                    'name'           => $row[0] ?? '',
                    'sku'            => strtoupper($row[1] ?? ''),
                    'category_id'    => $catId,
                    'cost_price'     => $row[3] ?? 0, // safe fallback
                    'selling_price'  => $row[4] ?? 0,
                    'stock_quantity' => $row[5] ?? 0,
                    'alert_quantity' => $row[6] ?? 5,
                    'unit'           => $row[7] ?? 'pcs',
                    'description'    => $row[8] ?? null,
                    'status'         => strtolower($row[9] ?? 'active'),
                ];

                // corrected variable assignment inline
                $data['cost_price'] = $row[3] ?? 0;

                $validator = Validator::make($data, [
                    'name' => 'required|string|max:255',
                    'sku' => 'required|string|max:100',
                    'selling_price' => 'required|numeric|min:0',
                    'stock_quantity' => 'required|integer|min:0',
                ]);

                if (!$validator->fails()) {
                    Product::updateOrCreate(
                        ['sku' => $data['sku']],
                        array_merge($data, [
                            'uuid' => Product::where('sku', $data['sku'])->value('uuid') ?? (string) Str::uuid()
                        ])
                    );
                    $rowCount++;
                }
            }
        }

        fclose($file);

        session()->flash('message', "Successfully imported {$rowCount} product records from CSV.");
        $this->closeCsvModal();
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    private function resetInputFields()
    {
        $this->productId = null;
        $this->name = '';
        $this->sku = '';
        $this->category_id = null;
        $this->cost_price = 0;
        $this->selling_price = 0;
        $this->stock_quantity = 0;
        $this->alert_quantity = 5;
        $this->unit = 'pcs';
        $this->description = '';
        $this->status = 'active';
    }
}