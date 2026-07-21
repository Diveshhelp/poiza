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
        Schema::create('custom_tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uuid')->nullable();
            $table->text('title')->nullable();
            $table->integer('team_id')->nullable();
            $table->enum('task_type', ['onetime', 'repeat'])->default('onetime');
            $table->integer('repeat_on_day');
            $table->date('task_trigger_date')->nullable();
            $table->integer('task_due_day')->default(0);
            $table->enum('status', ['0', '1'])->default('0')->comment('1: Active 0:Inactive');
            $table->datetime('created_at')->nullable();
            $table->integer('created_by')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->integer('updated_by')->nullable();
            $table->datetime('deleted_at')->nullable();
            
            $table->index(['task_type', 'status']);
            $table->index('created_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_tasks');
    }
};
