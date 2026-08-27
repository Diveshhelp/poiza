<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('diora_stocks', function (Blueprint $table) {
            $table->id();
            // Links to your diora_products table
            $table->foreignId('diora_product_id')->constrained('diora_products')->cascadeOnDelete();
            $table->integer('quantity')->default(0); // Can be positive (in) or negative (out/adjustment)
            $table->string('type')->default('addition'); // 'addition', 'deduction', 'opening'
            $table->string('reference_no')->nullable(); // Invoice or batch reference
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('diora_stocks');
    }
};