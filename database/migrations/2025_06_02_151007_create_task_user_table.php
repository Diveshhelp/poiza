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
        Schema::create('custom_task_user', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uuid')->nullable();
            $table->integer('custom_task_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->datetime('created_at')->nullable();
            $table->integer('created_by')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->datetime('deleted_at')->nullable();
            $table->integer('team_id')->nullable();
            $table->index('custom_task_id');
            $table->index('user_id');
            $table->index('created_by');
            $table->unique(['custom_task_id', 'user_id'], 'task_user_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_task_user');
    }
};
