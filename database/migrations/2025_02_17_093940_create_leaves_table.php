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
        Schema::create('leaves', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('total_days');
            $table->enum('is_full_day', ['yes','no'])->default('yes');
            $table->enum('leave_half', ['first_half', 'second_half'])->nullable();
            $table->text('reason');
            $table->enum('status', ['pending', 'approved', 'rejected', 'cancelled'])->default('pending');
            $table->bigInteger('approved_by')->nullable()->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->bigInteger('rejected_by')->nullable()->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leaves');
    }
};