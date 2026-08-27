<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('diora_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_no')->unique();
            $table->foreignId('diora_customer_id')->constrained('diora_customers')->cascadeOnDelete();
            $table->string('status')->default('pending'); // pending, confirm, process, ready_for_dispatch, dispatched, done
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->date('order_date');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('diora_orders');
    }
};