<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('diora_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('diora_order_id')->constrained('diora_orders')->cascadeOnDelete();
            $table->foreignId('diora_product_id')->constrained('diora_products')->cascadeOnDelete();
            $table->integer('quantity')->default(1);
            $table->decimal('price', 10, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('diora_order_items');
    }
};