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
        Schema::create('bug_masters', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('title');
            $table->longText('explanation');
            $table->enum('status', [
                'Draft', 
                'Ready for work', 
                'In progress', 
                'Attention required', 
                'Deployed', 
                'Done'
            ])->default('Draft');
            $table->enum('client_status', [
                'Created', 
                'In Review', 
                'In Development', 
                'In Testing', 
                'Done', 
                'Ready for check'
            ])->default('Created');
            $table->enum('type', ['Bug', 'Enhancement', 'Justification'])->default('Bug');
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('team_id')->constrained()->cascadeOnDelete();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->tinyInteger('priority')->default(1); // 1=Low, 2=Medium, 3=High, 4=Critical
            $table->timestamp('due_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            // Indexes for performance
            $table->index(['team_id', 'created_at']);
            $table->index(['team_id', 'status']);
            $table->index(['team_id', 'client_status']);
            $table->index(['team_id', 'type']);
            $table->index(['team_id', 'user_id']);
            $table->index(['team_id', 'assigned_to']);
            $table->index(['team_id', 'priority']);
            $table->index(['team_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bug_masters');
    }
};
