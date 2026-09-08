<?php
namespace App\Livewire;

use App\Models\DioraProduct;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;

class DioraProductCatalog extends Component
{
    use WithPagination;

    public string $search = '';
    public string $selectedFinish = '';
    public string $stockFilter = 'all'; // 'all', 'in_stock', 'out_of_stock'

    // Selected product state for quick modal view
    public ?DioraProduct $selectedProduct = null;
    public ?string $activeModalImage = null;

    protected $queryString = [
        'search'         => ['except' => ''],
        'selectedFinish' => ['except' => ''],
        'stockFilter'    => ['except' => 'all'],
        'page'           => ['except' => 1],
    ];

    public function updatingSearch(): void         { $this->resetPage(); }
    public function updatingSelectedFinish(): void { $this->resetPage(); }
    public function updatingStockFilter(): void    { $this->resetPage(); }

    public function openProductModal(int $productId): void
    {
        $this->selectedProduct = DioraProduct::withSum('stocks as total_stock', 'quantity')->find($productId);
        if ($this->selectedProduct) {
            $images = $this->resolveImages($this->selectedProduct);
            $this->activeModalImage = $images[0] ?? null;
        }
    }

    public function closeModal(): void
    {
        $this->selectedProduct = null;
        $this->activeModalImage = null;
    }

    public function selectModalImage(string $url): void
    {
        $this->activeModalImage = $url;
    }

    public function resolveImages(DioraProduct $product): array
    {
        $images = [];

        if (!empty($product->image)) {
            $images[] = Storage::url($product->image);
        }

        if (!empty($product->images)) {
            $gallery = is_array($product->images) ? $product->images : json_decode($product->images, true);
            if (is_array($gallery)) {
                foreach ($gallery as $path) {
                    $images[] = Storage::url($path);
                }
            }
        }

        return array_values(array_unique($images));
    }

    public function render()
    {
        $products = DioraProduct::withSum('stocks as total_stock', 'quantity')
            ->when($this->search, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('product_name', 'like', '%' . $this->search . '%')
                        ->orWhere('product_code', 'like', '%' . $this->search . '%')
                        ->orWhere('finish', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->selectedFinish, fn($q) => $q->where('finish', $this->selectedFinish))
            ->when($this->stockFilter === 'in_stock', fn($q) => $q->having('total_stock', '>', 0))
            ->when($this->stockFilter === 'out_of_stock', fn($q) => $q->havingRaw('COALESCE(total_stock, 0) <= 0'))
            ->latest()
            ->paginate(16);

        $finishes = DioraProduct::whereNotNull('finish')
            ->where('finish', '!=', '')
            ->distinct()
            ->pluck('finish');

        return view('livewire.diora-product-catalog', [
            'products' => $products,
            'finishes' => $finishes,
        ])->layout('layouts.guest');
    }
}