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
        Schema::create('order_processes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            
            // Step 1: Casting Status & Info
            $table->string('casting_status')->default('pending'); // pending, in_progress, completed
            $table->timestamp('casting_completed_at')->nullable();
            
            // Step 2: Turning Status & Info
            $table->string('turning_status')->default('pending'); // pending, in_progress, completed
            $table->timestamp('turning_completed_at')->nullable();
            
            // Step 3: Buff Status & Info
            $table->string('buff_status')->default('pending'); // pending, in_progress, completed
            $table->timestamp('buff_completed_at')->nullable();
            
            // Overall process status
            $table->string('overall_status')->default('casting_pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_processes');
    }
};
