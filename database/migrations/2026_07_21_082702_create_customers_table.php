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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('name');
            $table->string('company_name')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('phone')->nullable();
            $table->string('gstin')->nullable(); // Tax / GST Number
            $table->enum('customer_type', ['wholesaler', 'retailer', 'distributor', 'manufacturer'])->default('wholesaler');
            $table->decimal('credit_limit', 12, 2)->default(0.00);
            $table->string('website')->nullable();
            $table->text('address')->nullable();
            $table->string('status')->default('active'); // active, inactive
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};