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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->nullable();
            $table->bigInteger('department_id')->nullable();
            $table->bigInteger('assigned_to')->nullable();
            $table->bigInteger('created_by')->nullable();
            $table->string('title');
            $table->text('work_detail');
            $table->date('deadline');
            $table->enum('priority', ['highest', 'high', 'low', 'very_low']);
            $table->enum('work_type', ['routine', 'easy', 'medium', 'hard']);
            $table->enum('status', ['not_started', 'in_progress', 'done', 'delayed'])->default('not_started');
            $table->enum('repetition', ['no', 'daily', 'weekly', 'monthly', 'quarterly', 'half_yearly', 'yearly']);
            $table->date('repeat_until')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
