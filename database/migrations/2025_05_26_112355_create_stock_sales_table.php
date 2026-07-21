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
        Schema::create('stock_sales', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('customer_id');
            $table->decimal('price', 10, 2);
            $table->integer('quantity');
            $table->decimal('total_amount', 10, 2);
            $table->string('invoice_number')->nullable();
            $table->enum('payment_status', ['pending', 'paid', 'partial'])->default('pending');
            $table->decimal('paid_amount', 10, 2)->default(0);
            $table->date('sale_date');
            $table->text('notes')->nullable();
            $table->enum('status', ['draft', 'completed', 'cancelled'])->default('draft');
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
        Schema::dropIfExists('stock_sales');
    }
};