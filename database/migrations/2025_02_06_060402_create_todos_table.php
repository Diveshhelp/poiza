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
        Schema::create('todos', function (Blueprint $table) {
            // Primary key
            $table->id();

            // User relationship - owner of the todo
            $table->bigInteger('user_id')->nullable();
            // Basic todo information
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('due_date');

            // Priority and status enums
            $table->enum('priority', ['low', 'medium', 'high'])
                ->default('medium');
            $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled'])
                ->default('pending');

            // Assignment information
            $table->bigInteger('assigned_by')->nullable();

            // Completion tracking
            $table->timestamp('completed_at')->nullable();

            // Standard timestamps
            $table->timestamps();
            
            // Soft deletes for data retention
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('todos');
    }
};