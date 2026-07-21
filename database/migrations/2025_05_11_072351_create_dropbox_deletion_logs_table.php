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
        Schema::create('dropbox_deletion_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained(); // Links to the user who performed the deletion
            $table->string('item_path');
            $table->string('item_name');
            $table->string('item_type'); // 'file' or 'folder'
            $table->string('item_size')->nullable(); // Size (for files only)
            $table->text('metadata')->nullable(); // Additional metadata as JSON
            $table->ipAddress('ip_address')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dropbox_deletion_logs');
    }
};
