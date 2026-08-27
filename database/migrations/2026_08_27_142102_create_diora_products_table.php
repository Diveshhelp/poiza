<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('diora_products', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->nullable();
            $table->string('product_name');
            $table->string('product_code')->unique();
            $table->string('product_alias')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->string('category_name')->nullable();
            $table->string('model_key')->nullable();
            $table->string('finish')->nullable();
            $table->string('size')->nullable();
            $table->json('images')->nullable(); // Stores multiple image paths
            $table->integer('piece')->default(1);
            $table->integer('packing')->default(1);
            $table->decimal('price', 10, 2)->default(0);
            $table->string('type_of_model')->nullable();
            $table->string('material')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('diora_products');
    }
};