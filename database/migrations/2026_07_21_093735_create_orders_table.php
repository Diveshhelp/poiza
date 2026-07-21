<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('order_number')->unique(); // e.g., ORD-2026-0001
            
            // Customer Info (Guest checkout supported)
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->string('customer_name');
            $table->string('customer_email')->nullable();
            $table->string('customer_phone');
            $table->text('shipping_address');
            
            // Financials
            $table->decimal('subtotal', 12, 2)->default(0.00);
            $table->decimal('tax_amount', 12, 2)->default(0.00);
            $table->decimal('total_amount', 12, 2)->default(0.00);
            
            // Workflow States
            $table->string('status')->default('pending'); // pending, confirmed, processing, shipped, completed, cancelled
            $table->string('payment_status')->default('unpaid'); // unpaid, paid, partial
            $table->string('payment_method')->default('cod'); // cod, bank_transfer, online
            
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};