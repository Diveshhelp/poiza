<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stock_products', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('product_name');
            $table->unsignedBigInteger('category_id');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2)->default(0);
            $table->integer('quantity')->default(0);
            $table->string('sku')->nullable();
            $table->json('images')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->unsignedBigInteger('team_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('created_by');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_products');
    }
};