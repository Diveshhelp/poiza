<?php
namespace App\Livewire;

use App\Models\MyProduct;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MyProducts extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $myProductId;
    public $product_name, $product_code, $product_alias, $product_category, $finish, $size, $image;

    // View Modal Properties
    public $isViewModalOpen = false;
    public $viewingMyProduct;
    public $isOpen = false;
    public $isEditMode = false;

    // CSV Import Property
    public $csvFile;
    public $isCsvModalOpen = false;

    protected $queryString = ['search' => ['except' => '']];

    // Image Preview Modal Properties
    public $isImageModalOpen = false;
    public $previewingImage = null;

    public function openImageModal($imageUrl)
    {
        $this->previewingImage = $imageUrl;
        $this->isImageModalOpen = true;
    }

    public function closeImageModal()
    {
        $this->isImageModalOpen = false;
        $this->previewingImage = null;
    }
    public function updatingSearch()
    {
        $this->resetPage();
    }

    protected function rules()
    {
        return [
            'product_name' => 'required|string|max:255',
            'product_code' => 'required|string|max:100|unique:my_products,product_code,' . $this->myProductId,
            'product_alias' => 'nullable|string|max:255',
            'product_category' => 'nullable|string|max:255',
            'finish' => 'nullable|string|max:255',
            'size' => 'nullable|string|max:100',
            'image' => 'nullable|image|max:2048',
        ];
    }

    public function render()
    {
        $myProducts = MyProduct::query()
            ->when($this->search, function ($query) {
                $query->where('product_name', 'like', '%' . $this->search . '%')
                      ->orWhere('product_code', 'like', '%' . $this->search . '%')
                      ->orWhere('product_alias', 'like', '%' . $this->search . '%')
                      ->orWhere('product_category', 'like', '%' . $this->search . '%')
                      ->orWhere('uuid', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(10);

        return view('livewire.my-products', [
            'myProducts' => $myProducts
        ])->layout('layouts.app');
    }

    public function view($id)
    {
        $this->viewingMyProduct = MyProduct::findOrFail($id);
        $this->isViewModalOpen = true;
    }

    public function closeViewModal()
    {
        $this->isViewModalOpen = false;
        $this->viewingMyProduct = null;
    }

    public function create()
    {
        $this->resetInputFields();
        $this->product_code = $this->generateProductCode();
        $this->isOpen = true;
        $this->isEditMode = false;
    }

    public function store()
    {
        $this->validate();

        $imagePath = $this->image && is_object($this->image) 
            ? $this->image->store('my-products', 'public') 
            : $this->image;

        $data = [
            'product_name' => $this->product_name,
            'product_code' => $this->product_code,
            'product_alias' => $this->product_alias,
            'product_category' => $this->product_category,
            'finish' => $this->finish,
            'size' => $this->size,
            'image' => $imagePath,
        ];

        if (!$this->myProductId) {
            $data['uuid'] = (string) Str::uuid();
        } else {
            if (!$this->image || !is_object($this->image)) {
                unset($data['image']);
            }
        }

        MyProduct::updateOrCreate(['id' => $this->myProductId], $data);

        session()->flash('message', $this->myProductId ? 'Product updated successfully.' : 'Product created successfully.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $product = MyProduct::findOrFail($id);
        $this->myProductId = $product->id;
        $this->product_name = $product->product_name;
        $this->product_code = $product->product_code;
        $this->product_alias = $product->product_alias;
        $this->product_category = $product->product_category;
        $this->finish = $product->finish;
        $this->size = $product->size;
        $this->image = $product->image;

        $this->isEditMode = true;
        $this->isOpen = true;
    }

    public function delete($id)
    {
        $product = MyProduct::find($id);
        if ($product) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $product->delete();
        }
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
            'Content-Disposition' => 'attachment; filename="my_products_sample.csv"',
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, [
                'product_name', 'product_code', 'product_alias', 'product_category', 'finish', 'size', 'image'
            ]);

            fputcsv($file, [
                'Glossy Ceramic Tile', 'TILE-GLS-001', 'Glossy Tile', 'Tiles', 'Glossy', '600x600mm', 'my-products/sample.jpg'
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
        
        fgetcsv($file); 
        $rowCount = 0;

        while (($row = fgetcsv($file)) !== false) {
            if (count($row) >= 1) {
                $data = [
                    'product_name' => $row[0] ?? '',
                    'product_code' => $row[1] ?? '',
                    'product_alias' => $row[2] ?? null,
                    'product_category' => $row[3] ?? null,
                    'finish' => $row[4] ?? null,
                    'size' => $row[5] ?? null,
                    'image' => $row[6] ?? null,
                ];

                $validator = Validator::make($data, [
                    'product_name' => 'required|string|max:255',
                    'product_code' => 'required|string|max:100',
                ]);

                if (!$validator->fails()) {
                    MyProduct::updateOrCreate(
                        ['product_code' => $data['product_code']],
                        array_merge($data, [
                            'uuid' => MyProduct::where('product_code', $data['product_code'])->value('uuid') ?? (string) Str::uuid()
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

    public function mount()
    {
        // Auto-fill product code when the page loads
        $this->product_code = $this->generateProductCode();
    }


    private function generateProductCode()
    {
        $lastProduct = MyProduct::orderBy('id', 'desc')->first();
        
        if ($lastProduct && preg_match('/PH(\d+)/', $lastProduct->product_code, $matches)) {
            $nextNumber = intval($matches[1]) + 1;
            return 'PH' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        }
        
        return 'PH0001';
    }
    public function removeImage()
    {
        // If editing an existing record and it has a stored image file
        if ($this->myProductId) {
            $product = MyProduct::find($this->myProductId);
            if ($product && $product->image) {
                if (Storage::disk('public')->exists($product->image)) {
                    Storage::disk('public')->delete($product->image);
                }
                $product->update(['image' => null]);
            }
        }

        // Reset the current live wire image property
        $this->image = null;
        session()->flash('message', 'Product image removed successfully.');
    }

    private function resetInputFields()
    {
        $this->myProductId = null;
        $this->product_name = '';
        $this->product_code = $this->generateProductCode(); // Keep it auto-filled on reset
        $this->product_alias = '';
        $this->product_category = '';
        $this->finish = '';
        $this->size = '';
        $this->image = null;
    }
}