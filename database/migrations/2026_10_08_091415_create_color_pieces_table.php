<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('color_pieces', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_item_id')->nullable()->constrained('order_items')->nullOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('my_products')->nullOnDelete();
            $table->unsignedInteger('piece_number')->default(1);
            $table->unsignedInteger('order_qty')->default(0);
            $table->unsignedInteger('received_qty')->default(0);
            $table->decimal('price_per_unit', 10, 2)->default(0.00);
            $table->string('pricing_type')->default('piece'); // 'piece' or 'inch'
            $table->timestamps();

            // Unique index per order, product, and piece
            $table->unique(['order_id', 'product_id', 'piece_number'], 'color_pieces_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('color_pieces');
    }
};