<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('buff_pieces', function (Blueprint $table) {
            $table->id();
               $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_item_id')->constrained('order_items')->cascadeOnDelete();
            $table->foreignId('product_id');
            $table->integer('piece_number'); // Part 1, Part 2, etc.
            $table->integer('order_qty')->default(0);
            $table->integer('received_qty')->default(0);
            $table->decimal('price_per_unit', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->string('pricing_type')->default('piece'); // 'piece' or 'inch'
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('buff_pieces');
    }
};