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
    {Schema::create('bug_images', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('bug_master_id')->constrained()->cascadeOnDelete();
            $table->string('image_path');
            $table->string('original_name');
            $table->string('mime_type');
            $table->unsignedBigInteger('file_size');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->foreignId('uploaded_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            // Indexes
            $table->index(['bug_master_id', 'sort_order']);
            $table->index(['bug_master_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bug_images');
    }
};
