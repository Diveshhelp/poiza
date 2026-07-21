<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Livewire\Component;
use Illuminate\Support\Str;

class StorefrontOrderForm extends Component
{
    // Mobile Verification Access Control
    public $isAuthorized = false;
    public $auth_phone = '';

    public $step = 1; // 1: Select Items, 2: Customer Details, 3: Order Confirmation
    
    // Cart storage: [product_id => quantity]
    public $cart = [];
    public $productQuantities = [];

    // Customer & Checkout Details
    public $customer_name, $customer_email, $customer_phone, $shipping_address, $payment_method = 'cod', $notes;

    // Honeypot Spam Protection (Bots fill this, humans don't see it)
    public $website = '';

    public $placedOrder;
    public $customer;

    public function mount()
    {
        // Check if mobile number is already verified in this session
        if (session()->has('verified_customer_phone')) {
            $this->isAuthorized = true;
            $this->customer_phone = session('verified_customer_phone');
            $this->loadExistingCustomerData($this->customer_phone);
        }

        $products = Product::where('status', 'active')->get();
        foreach ($products as $product) {
            $this->productQuantities[$product->id] = 1;
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
        
        // Save to session and set phone number
        session(['verified_customer_phone' => $cleanPhone]);
        $this->customer_phone = $cleanPhone;
        $this->isAuthorized = true;

        // Automatically fetch and fill user information if they ordered before
        $this->loadExistingCustomerData($cleanPhone);
    }

    private function loadExistingCustomerData($phone)
    {
        
        // Search directly in the customers table by phone number
        $customer = \App\Models\Customer::where('phone', 'like', '%' . $phone . '%')->first();
        if ($customer) {
            $this->customer_name = $customer->name;
            $this->customer_email = $customer->email;
            $this->shipping_address = $customer->address; // Update column name if it differs in your customers table
        }
    }
    public function render()
    {
        $products = Product::where('status', 'active')->with('category')->get();
        return view('livewire.storefront-order-form', [
            'products' => $products
        ])->layout('layouts.guest');
    }

    public function addToCart($productId)
    {
        $qty = max(1, (int)($this->productQuantities[$productId] ?? 1));
        $this->cart[$productId] = ($this->cart[$productId] ?? 0) + $qty;
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
        $products = Product::whereIn('id', $productIds)->get();

        return $products->map(function($product) {
            $qty = $this->cart[$product->id];
            return [
                'product' => $product,
                'quantity' => $qty,
                'subtotal' => $product->selling_price * $qty
            ];
        });
    }

    public function getSubtotalProperty()
    {
        return $this->cartItems->sum('subtotal');
    }

    public function getTaxAmountProperty()
    {
        return $this->subtotal * 0.18; // 18% GST calculation
    }

    public function getTotalAmountProperty()
    {
        return $this->subtotal + $this->taxAmount;
    }

    public function placeOrder()
    {
        // Anti-spam Honeypot Check: If a bot fills this hidden field, stop immediately
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
            $product = Product::find($productId);
            if ($product) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'sku' => $product->sku,
                    'unit_price' => $product->selling_price,
                    'quantity' => $qty,
                    'subtotal' => $product->selling_price * $qty,
                ]);

                // Reduce inventory stock automatically
                $product->decrement('stock_quantity', $qty);
            }
        }

        $this->placedOrder = $order;
        $this->cart = [];
        $this->step = 3; // Confirmation Step
    }
}