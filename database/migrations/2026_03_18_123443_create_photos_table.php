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
        Schema::create('photos', function (Blueprint $table) {
            // Primary key
            $table->id();
            $table->uuid('uuid')->unique();

            // User relationship - owner of the todo
            $table->bigInteger('user_id')->nullable();
            // Basic todo information
            $table->string('album_title');

            // Priority and status enums
            $table->enum('priority', ['low', 'medium', 'high'])->default('low');


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
        Schema::dropIfExists('photos');
    }
};
