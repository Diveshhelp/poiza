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
        Schema::create('custom_task_master', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uuid')->nullable();
            $table->integer('custom_task_id')->nullable();
            $table->integer('team_id')->nullable();
            $table->date('task_date');
            $table->datetime('task_done_on')->nullable();
            $table->integer('assign_to');
            $table->enum('status', ['0', '1'])->default('0')->comment('1: Active 0:Inactive');
            $table->datetime('created_at')->nullable();
            $table->integer('created_by')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->integer('updated_by')->nullable();
            $table->datetime('deleted_at')->nullable();
            $table->text('comment')->nullable();
            $table->enum('is_transfer', ['0', '1'])->default('0')->comment('1: Transfer 0:Owner');
            
            $table->index('custom_task_id');
            $table->index('assign_to');
            $table->index(['task_date', 'status']);
            $table->index('created_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_task_master');
    }
};
