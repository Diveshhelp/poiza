<?php

namespace App\Livewire;

use App\Models\MyProduct;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use App\Models\Category;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MyProducts extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $myProductId;

    // Product Fields mapped to spreadsheet headers
    public $product_name, $product_code, $product_alias, $category_id, $finish, $size, $image, $piece = 1;
    public $price, $packing, $type_of_model, $material, $category_name, $model_key;

    // View Modal Properties
    public $isViewModalOpen = false;
    public $viewingMyProduct;
    public $isOpen = false;
    public $isEditMode = false;

    // CSV Import Property
    public $csvFile;
    public $previewData = [];
    public $showPreview = false;
    public $isCsvModalOpen = false;

    // Category Modal Properties
    public $isCategoryModalOpen = false;
    public $newCategoryName;
    public $newCategoryDescription;
    public $existingImage;  // Holds current database image string path during edit
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
            'category_id' => 'nullable|exists:categories,id',
            'finish' => 'nullable|string|max:255',
            'size' => 'nullable|string|max:100',
            'price' => 'nullable|numeric|min:0',
            'packing' => 'nullable|string|max:255',
            'type_of_model' => 'nullable|string|max:255',
            'material' => 'nullable|string|max:255',
            'category_name' => 'nullable|string|max:255',
            'model_key' => 'nullable|string|max:255|unique:my_products,model_key,' . $this->myProductId,
            'image' => 'nullable|image',
            'piece' => 'required|numeric|min:1',
        ];
    }

    public function render()
    {
        $myProducts = MyProduct::with('category')
            ->when($this->search, function ($query) {
                $query->where('product_name', 'like', '%' . $this->search . '%')
                    ->orWhere('product_code', 'like', '%' . $this->search . '%')
                    ->orWhere('product_alias', 'like', '%' . $this->search . '%')
                    ->orWhere('model_key', 'like', '%' . $this->search . '%')
                    ->orWhere('material', 'like', '%' . $this->search . '%')
                    ->orWhere('type_of_model', 'like', '%' . $this->search . '%')
                    ->orWhereHas('category', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    })
                    ->orWhere('uuid', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(10);

        $categories = Category::where('status', 'active')->orderBy('name')->get();

        return view('livewire.my-products', [
            'myProducts' => $myProducts,
            'categories' => $categories
        ])->layout('layouts.app');
    }

    public function view($id)
    {
        $this->viewingMyProduct = MyProduct::with('category')->findOrFail($id);
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
        // Validation rule for image should be nullable/sometimes: 'image' => 'nullable|image|max:2048'
        $this->validate();

        $data = [
            'product_name' => $this->product_name,
            'product_code' => $this->product_code,
            'product_alias' => $this->product_alias,
            'category_id' => $this->category_id ?: null,
            'finish' => $this->finish,
            'size' => $this->size,
            'price' => $this->price ?? 0,
            'packing' => $this->packing,
            'type_of_model' => $this->type_of_model,
            'material' => $this->material,
            'category_name' => $this->category_name,
            'model_key' => $this->model_key,
            'piece' => $this->piece,
        ];

        // If a new image file object is uploaded, store it and update the path
        if ($this->image && is_object($this->image)) {
            // Delete old image if it exists
            if ($this->existingImage) {
                \Storage::disk('public')->delete($this->existingImage);
            }
            $data['image'] = $this->image->store('my-products', 'public');
        } else {
            // Keep existing image if no new file was uploaded
            $data['image'] = $this->existingImage;
        }

        if (!$this->myProductId) {
            $data['uuid'] = (string) Str::uuid();
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
        $this->category_id = $product->category_id;
        $this->finish = $product->finish;
        $this->size = $product->size;
        $this->price = $product->price;
        $this->packing = $product->packing;
        $this->type_of_model = $product->type_of_model;
        $this->material = $product->material;
        $this->category_name = $product->category_name;
        $this->model_key = $product->model_key;
        $this->piece = $product->piece ?? 1;

        $this->isEditMode = true;
        $this->isOpen = true;

        // Assign existing image path separately so file input doesn't bind to string
        $this->existingImage = $product->image;
        $this->image = null; // Clear new upload field
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


    public function exportSampleCsv(): StreamedResponse
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="my_products_sample.csv"',
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');

            // CSV Header Row in camelCase with bold text styling if opened in markdown/spreadsheet headers
            fputcsv($file, [
                'Product Name',
                'Product Code',
                'Size',
                'Finish',
                'Price',
                'Packing',
                'Type Of Model',
                'Material',
                'Product Category',
                'Model Key',
                'Product Alias',
                'Piece'

            ]);

            // Sample Data Row matching the updated camelCase columns
            fputcsv($file, [
                'Glossy Mortise Handle',
                'PH0001',
                '200mm',
                'Glossy',
                '450.00',
                'Box',
                'Mortise Handle',
                'Steel',
                'Mortise Handles',
                'PH0001-200-GL',
                'Mortise Lock Handle',
                '1'
            ]);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
    // 1. Re-write or add this method to handle the initial CSV parsing and trigger preview mode
    public function previewCsv()
{
    $this->validate([
        'csvFile' => 'required|file|mimes:csv,txt|max:2048',
    ]);

    // Ensure the file exists and is uploaded properly before getting path
    if (!$this->csvFile) {
        session()->flash('error', 'Please wait for the file upload to finish before clicking preview.');
        return;
    }

    $path = $this->csvFile->getRealPath();
    
    // Fallback safety if temporary path is empty during rapid click
    if (!$path || !file_exists($path)) {
        session()->flash('error', 'File upload is still processing. Please try clicking preview again.');
        return;
    }

    $file = fopen($path, 'r');

    // Skip the header row
    fgetcsv($file);
    $this->previewData = [];

    while (($row = fgetcsv($file)) !== false) {
        if (count($row) >= 2) {
            $this->previewData[] = [
                'product_name'  => $row[0] ?? '',
                'product_code'  => $row[1] ?? '',
                'size'          => $row[2] ?? null,
                'finish'        => $row[3] ?? null,
                'price'         => is_numeric($row[4] ?? null) ? $row[4] : 1,
                'packing'       => $row[5] ?? null,
                'type_of_model' => $row[6] ?? null,
                'material'      => $row[7] ?? null,
                'category_name' => $row[8] ?? null,
                'model_key'     => $row[9] ?? null,
                'product_alias' => $row[10] ?? null,
                'piece'         => is_numeric($row[11] ?? null) ? $row[11] : 1,
            ];
        }
    }

    fclose($file);

    if (empty($this->previewData)) {
        session()->flash('error', 'The uploaded CSV file is empty or formatted incorrectly.');
        return;
    }

    // Switch the modal view to show the table preview
    $this->showPreview = true;
}
    // 2. Add this method to go back from preview to upload form
    public function cancelPreview()
    {
        $this->showPreview = false;
        $this->previewData = [];
        $this->csvFile = null;
    }

    // 3. Add this method to handle the final database import after confirmation
    public function confirmImport()
    {
        $rowCount = 0;

        foreach ($this->previewData as $row) {
            $categoryName = $row['category_name'];
            $categoryId = null;

            if (!empty($categoryName)) {
                $category = Category::firstOrCreate(
                    ['name' => trim($categoryName)],
                    [
                        'uuid' => (string) Str::uuid(),
                        'slug' => Str::slug($categoryName),
                        'status' => 'active'
                    ]
                );
                $categoryId = $category->id;
            }

            $data = array_merge($row, [
                'category_id' => $categoryId,
                'image' => null,
            ]);

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

        // Reset state and close modal
        $this->showPreview = false;
        $this->previewData = [];
        $this->csvFile = null;

        session()->flash('message', "Successfully imported {$rowCount} product records from CSV.");
        $this->closeCsvModal();
    }

    // Ensure your closeCsvModal also clears the preview state when closed manually
    public function closeCsvModal()
    {
        $this->isCsvModalOpen = false;
        $this->showPreview = false;
        $this->previewData = [];
        $this->csvFile = null;
    }
    public function closeModal()
    {
        $this->isOpen = false;
    }

    public function mount()
    {
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
        if ($this->myProductId) {
            $product = MyProduct::find($this->myProductId);
            if ($product && $product->image) {
                if (Storage::disk('public')->exists($product->image)) {
                    Storage::disk('public')->delete($product->image);
                }
                $product->update(['image' => null]);
            }
        }

        $this->image = null;
        session()->flash('message', 'Product image removed successfully.');
    }

    // Quick Add Category Actions
    public function openCategoryModal()
    {
        $this->newCategoryName = '';
        $this->newCategoryDescription = '';
        $this->isCategoryModalOpen = true;
    }

    public function closeCategoryModal()
    {
        $this->isCategoryModalOpen = false;
        $this->newCategoryName = '';
        $this->newCategoryDescription = '';
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

        $this->category_id = $category->id;
        $this->category_name = $category->name;

        session()->flash('message', 'New category created successfully.');
        $this->closeCategoryModal();
    }

    private function resetInputFields()
    {
        $this->myProductId = null;
        $this->product_name = '';
        $this->product_code = $this->generateProductCode();
        $this->product_alias = '';
        $this->category_id = '';
        $this->finish = '';
        $this->size = '';
        $this->price = '';
        $this->packing = '';
        $this->type_of_model = '';
        $this->material = '';
        $this->category_name = '';
        $this->model_key = '';
        $this->piece = 1;
        $this->image = null;
        $this->existingImage = null;
    }
}