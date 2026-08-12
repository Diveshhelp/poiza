<?php

namespace App\Livewire;

use App\Models\MyProduct;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Customer;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;

class StorefrontOrderForm extends Component
{
    use WithPagination;

    // Mobile Verification Access Control
    public $isAuthorized = false;
    public $auth_phone = '';

    public $step = 1; 
    
    // Search and Catalog filters
    public $search = '';
    public $selectedCategory = '';

    // Cart storage: [my_product_id => quantity]
    public $cart = [];
    public $productQuantities = [];

    // Customer & Checkout Details
    public $customer_name, $customer_email, $customer_phone, $shipping_address, $payment_method = 'cod', $notes;

    // Honeypot Spam Protection
    public $website = '';

    public $placedOrder;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSelectedCategory()
    {
        $this->resetPage();
    }

    public function mount()
    {
        if (session()->has('verified_customer_phone')) {
            $this->isAuthorized = true;
            $this->customer_phone = session('verified_customer_phone');
            $this->loadExistingCustomerData($this->customer_phone);
        }
    }

    public function verifyMobile()
    {
        $this->validate([
            'auth_phone' => 'required|string|min:10|max:15',
        ], [
            'auth_phone.required' => 'Please enter a valid mobile number.',
        ]);

        $cleanPhone = trim($this->auth_phone);
        
        session(['verified_customer_phone' => $cleanPhone]);
        $this->customer_phone = $cleanPhone;
        $this->isAuthorized = true;

        $this->loadExistingCustomerData($cleanPhone);
    }

    private function loadExistingCustomerData($phone)
    {
        $customer = Customer::where('phone', 'like', '%' . $phone . '%')->first();
        if ($customer) {
            $this->customer_name = $customer->name;
            $this->customer_email = $customer->email;
            $this->shipping_address = $customer->address; 
        }
    }

    public function render()
    {
        // Only fetch products if the user has typed a search or picked a category
        if (empty(trim($this->search)) && empty($this->selectedCategory)) {
            $products = collect(); // Returns an empty collection
        } else {
            $query = MyProduct::query();

            if (!empty($this->search)) {
                $query->where(function($q) {
                    $q->where('product_name', 'like', '%' . $this->search . '%')
                      ->orWhere('product_code', 'like', '%' . $this->search . '%')
                      ->orWhere('product_alias', 'like', '%' . $this->search . '%')
                      ->orWhere('finish', 'like', '%' . $this->search . '%');
                });
            }

            if (!empty($this->selectedCategory)) {
                $query->where('product_category', $this->selectedCategory);
            }

            $products = $query->paginate(20);
        }

        $categories = MyProduct::whereNotNull('product_category')->distinct()->pluck('product_category');

        return view('livewire.storefront-order-form', [
            'products' => $products,
            'categories' => $categories
        ])->layout('layouts.guest');
    }

    public function addToCart($productId)
    {
        $qty = max(1, (int)($this->productQuantities[$productId] ?? 1));
        $this->cart[$productId] = ($this->cart[$productId] ?? 0) + $qty;
        
        $this->productQuantities[$productId] = 1;
        
        session()->flash('message', 'Product added to your order summary.');
    }

    public function removeFromCart($productId)
    {
        unset($this->cart[$productId]);
    }

    public function proceedToCheckout()
    {
        if (empty($this->cart)) {
            session()->flash('error', 'Please select at least one product before proceeding.');
            return;
        }
        $this->step = 2;
    }

    public function getCartItemsProperty()
    {
        if (empty($this->cart)) {
            return collect();
        }

        $productIds = array_keys($this->cart);
        $products = MyProduct::whereIn('id', $productIds)->get();

        return $products->map(function($product) {
            $qty = $this->cart[$product->id];
            $unitPrice = $product->selling_price ?? 0; 

            return [
                'product' => $product,
                'quantity' => $qty,
                'subtotal' => $unitPrice * $qty
            ];
        });
    }

    public function getSubtotalProperty()
    {
        return $this->cartItems->sum('subtotal');
    }

    public function getTaxAmountProperty()
    {
        return $this->subtotal * 0.18; 
    }

    public function getTotalAmountProperty()
    {
        return $this->subtotal + $this->taxAmount;
    }

    public function placeOrder()
    {
        if (!empty($this->website)) {
            session()->flash('error', 'Spam activity detected.');
            return;
        }

        $this->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string',
            'payment_method' => 'required|in:cod,bank_transfer,online',
        ]);

        if (empty($this->cart)) {
            return;
        }

        Customer::updateOrCreate(
            ['phone' => $this->customer_phone],
            [
                'name' => $this->customer_name,
                'email' => $this->customer_email,
                'address' => $this->shipping_address,
            ]
        );

        $orderNumber = 'ORD-' . date('Y') . '-' . strtoupper(Str::random(6));

        $order = Order::create([
            'uuid' => (string) Str::uuid(),
            'order_number' => $orderNumber,
            'customer_name' => $this->customer_name,
            'customer_email' => $this->customer_email,
            'customer_phone' => $this->customer_phone,
            'shipping_address' => $this->shipping_address,
            'subtotal' => $this->subtotal,
            'tax_amount' => $this->taxAmount,
            'total_amount' => $this->totalAmount,
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'payment_method' => $this->payment_method,
            'notes' => $this->notes,
        ]);

        foreach ($this->cart as $productId => $qty) {
            $product = MyProduct::find($productId);
            if ($product) {
                $unitPrice = $product->selling_price ?? 0;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->product_name,
                    'sku' => $product->product_code,
                    'unit_price' => $unitPrice,
                    'quantity' => $qty,
                    'subtotal' => $unitPrice * $qty,
                ]);

                if (isset($product->stock_quantity)) {
                    $product->decrement('stock_quantity', $qty);
                }
            }
        }

        $this->placedOrder = $order;
        $this->cart = [];
        $this->step = 3; 
    }

    public function resetOrder()
    {
        $this->step = 1;
        $this->placedOrder = null;
        $this->cart = [];
        $this->search = '';
        $this->selectedCategory = '';
    }
}