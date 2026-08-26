<?php
namespace App\Livewire;

use App\Models\MyProduct;
use App\Models\BuffPrice;
use Livewire\Component;
use Livewire\WithPagination;

class BuffPriceManager extends Component
{
    use WithPagination;

    public $search = '';
    public $prices = []; // Holds inputs: $prices[product_id][piece_number] = [price, pricing_type]

    protected $queryString = ['search'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    // Save all prices currently visible/submitted across products
    public function saveAllPrices()
    {
        foreach ($this->prices as $productId => $pieces) {
            foreach ($pieces as $pieceNumber => $data) {
                BuffPrice::updateOrCreate(
                    [
                        'product_id'   => $productId,
                        'piece_number' => $pieceNumber,
                    ],
                    [
                        'price_per_piece' => $data['price_per_piece'] ?? 0,
                        'pricing_type'    => $data['pricing_type'] ?? 'piece',
                    ]
                );
            }
        }

        session()->flash('message', 'All buff prices updated successfully.');
    }

    public function render()
    {
        // 1. Fetch paginated products matching search query
        $products = MyProduct::with('buffPrices')
            ->when($this->search, function($query) {
                $query->where('product_name', 'like', '%' . $this->search . '%')
                      ->orWhere('product_code', 'like', '%' . $this->search . '%')
                      ->orWhere('model_key', 'like', '%' . $this->search . '%');
            })
            ->paginate(10);

        // 2. Map existing saved database values into the input array for current page items
        foreach ($products as $product) {
            $totalPieces = (int) ($product->piece ?? $product->packing ?? 1);
            $totalPieces = $totalPieces > 0 ? $totalPieces : 1;

            for ($i = 1; $i <= $totalPieces; $i++) {
                // Only populate from database if it hasn't been set or modified in state yet
                if (!isset($this->prices[$product->id][$i])) {
                    $existing = $product->buffPrices->where('piece_number', $i)->first();

                    $this->prices[$product->id][$i] = [
                        'price_per_piece' => $existing ? $existing->price_per_piece : 0,
                        'pricing_type'    => $existing ? $existing->pricing_type : 'piece',
                    ];
                }
            }
        }

        return view('livewire.buff-price-manager', [
            'products' => $products,
        ])->layout('layouts.app');
    }
}