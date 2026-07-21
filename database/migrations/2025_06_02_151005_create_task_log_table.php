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
        
        Schema::create('custom_task_log', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('custom_task_id')->nullable();
            $table->integer('assign_to')->nullable();
            $table->integer('created_by')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->integer('updated_by')->nullable();
            $table->datetime('deleted_at')->nullable();
            
            $table->index('custom_task_id');
            $table->index('assign_to');
            $table->index('created_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_task_log');
    }
};
