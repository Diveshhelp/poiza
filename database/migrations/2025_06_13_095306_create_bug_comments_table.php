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
       Schema::create('bug_comments', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('bug_master_id')->constrained()->cascadeOnDelete();
            $table->longText('comment');
            $table->enum('comment_type', [
                'General', 
                'Status Update', 
                'Internal Note', 
                'Client Response'
            ])->default('General');
            $table->boolean('is_internal')->default(false);
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            // Indexes
            $table->index(['bug_master_id', 'created_at']);
            $table->index(['bug_master_id', 'is_internal']);
            $table->index(['bug_master_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bug_comments');
    }
};
