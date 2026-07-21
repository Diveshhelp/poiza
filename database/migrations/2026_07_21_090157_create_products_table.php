<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('name');
            $table->string('sku')->unique();
            $table->string('category')->nullable(); // e.g., Mortise Handles, Hinges, Locks
            $table->decimal('cost_price', 12, 2)->default(0.00);
            $table->decimal('selling_price', 12, 2)->default(0.00);
            $table->integer('stock_quantity')->default(0);
            $table->integer('alert_quantity')->default(5); // Low stock warning threshold
            $table->string('unit')->default('pcs'); // pcs, box, pair, kg
            $table->text('description')->nullable();
            $table->string('status')->default('active'); // active, inactive
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};