<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('buff_prices', function (Blueprint $table) {
            $table->id();
            // Links to your my_product table
            $table->foreignId('product_id');
            $table->integer('piece_number'); // Part 1, Part 2, etc.
            $table->decimal('price_per_piece', 10, 2)->default(0); // Price amount
            $table->string('pricing_type')->default('piece'); // 'piece' or 'inch'
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('buff_prices');
    }
};