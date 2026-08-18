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

    public $step = 1; // 1: Select Items, 2: Customer Details, 3: Order Confirmation
    
    // Search and Catalog filters
    public $search = '';
    public $selectedCategory = '';

    // Cart storage: [my_product_id => ['qty' => quantity, 'type' => unit_type]]
    public $cart = [];
    public $productQuantities = [];
    public $productUnitTypes = []; // Tracks 'box' or 'piece' per product

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
            'auth_phone' => 'required|string|exists:customers,phone',
        ], [
            'auth_phone.required' => 'Please enter a mobile number.',
            'auth_phone.exists' => 'This mobile number is not registered in our system. Please contact support.',
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
        if (empty(trim($this->search)) && empty($this->selectedCategory)) {
            $products = collect(); 
        } else {
            $query = MyProduct::query();

            if (!empty($this->search)) {
                $query->where(function($q) {
                    $q->where('product_name', 'like', '%' . $this->search . '%')
                      ->orWhere('product_code', 'like', '%' . $this->search . '%')
                      ->orWhere('product_alias', 'like', '%' . $this->search . '%')
                      ->orWhere('finish', 'like', '%' . $this->search . '%')
                      ->orWhere('material', 'like', '%' . $this->search . '%')
                      ->orWhere('model_key', 'like', '%' . $this->search . '%');
                });
            }

            if (!empty($this->selectedCategory)) {
                $query->where('category_id', $this->selectedCategory);
            }

            $products = $query->paginate(20);
        }

        $categories = \App\Models\Category::where('status', 'active')->orderBy('name')->get();

        return view('livewire.storefront-order-form', [
            'products' => $products,
            'categories' => $categories
        ])->layout('layouts.guest');
    }

    public function addToCart($productId)
    {
        $qty = max(1, (int)($this->productQuantities[$productId] ?? 1));
        $unitType = $this->productUnitTypes[$productId] ?? 'piece'; // Default to piece

        // Store unique item or aggregate based on product + type combination
        $cartKey = $productId . '_' . $unitType;

        if (isset($this->cart[$cartKey])) {
            $this->cart[$cartKey]['qty'] += $qty;
        } else {
            $this->cart[$cartKey] = [
                'product_id' => $productId,
                'qty' => $qty,
                'type' => $unitType,
            ];
        }
        
        $this->productQuantities[$productId] = 1;
        $this->productUnitTypes[$productId] = 'piece';
        
        session()->flash('message', 'Product added to your selection summary.');
    }

    public function removeFromCart($cartKey)
    {
        unset($this->cart[$cartKey]);
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

        return collect($this->cart)->map(function($item, $cartKey) {
            $product = MyProduct::find($item['product_id']);
            if (!$product) return null;

            $qty = $item['qty'];
            $unitType = $item['type'];
            $unitPrice = $product->price ?? 0;

            // If ordered by box, you can multiply by items-per-box if applicable, otherwise keep unit price base
            $subtotal = $unitPrice * $qty;

            return [
                'cart_key' => $cartKey,
                'product' => $product,
                'quantity' => $qty,
                'unit_type' => $unitType,
                'unit_price' => $unitPrice,
                'subtotal' => $subtotal,
            ];
        })->filter();
    }

    public function getCartSubtotalProperty()
    {
        return $this->cartItems->sum('subtotal');
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

        $subtotal = $this->cartSubtotal;
        $taxAmount = 0;
        $totalAmount = $subtotal + $taxAmount;

        $orderNumber = 'AM-' . date('Y') . '-' . strtoupper(Str::random(6));

        $order = Order::create([
            'uuid' => (string) Str::uuid(),
            'order_number' => $orderNumber,
            'customer_name' => $this->customer_name,
            'customer_email' => $this->customer_email,
            'customer_phone' => $this->customer_phone,
            'shipping_address' => $this->shipping_address,
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'total_amount' => $totalAmount,
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'payment_method' => $this->payment_method,
            'notes' => $this->notes,
        ]);

        foreach ($this->cartItems as $item) {
            $product = $item['product'];
            $unitLabel = ucfirst($item['unit_type']); // 'Box' or 'Piece'
            
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'product_name' => $product->product_name . ' (' . $item['quantity'] . ' ' . $unitLabel . 's)',
                'sku' => $product->product_code,
                'unit_price' => $item['unit_price'],
                'quantity' => $item['quantity'],
                'subtotal' => $item['subtotal'],
            ]);
        }

        
        
        // Record creation activity
        \App\Models\OrderActivity::create([
            'order_id' => $order->id,
            'activity_type' => 'created',
            'old_value' => null, // Fixed: Use PHP null instead of string 'NULL'
            'new_value' => $order->status,
            'description' => 'Order created with initial status: ' . ucfirst($order->status),
        ]);

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

    public function logout()
    {
        session()->forget('verified_customer_phone');
        
        $this->isAuthorized = false;
        $this->auth_phone = '';
        $this->customer_phone = '';
        $this->customer_name = '';
        $this->customer_email = '';
        $this->shipping_address = '';
        $this->cart = [];
        $this->step = 1;

        session()->flash('message', 'Successfully logged out. Enter a mobile number to continue.');
    }
}