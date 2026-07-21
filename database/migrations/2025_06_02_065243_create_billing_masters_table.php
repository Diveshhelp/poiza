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
        Schema::create('billing_masters', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->unsignedBigInteger('team_id');
            $table->unsignedBigInteger('billing_details_id');
            $table->enum('status', ['raised', 'in_progress', 'paid', 'cancelled'])->default('raised');
            $table->decimal('amount', 10, 2);
            $table->date('billing_start_date');
            $table->date('billing_end_date');
            $table->text('cancelled_reason')->nullable();
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
        Schema::dropIfExists('billing_masters');
    }
};