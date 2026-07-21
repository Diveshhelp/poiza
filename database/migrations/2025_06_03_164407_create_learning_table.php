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
        Schema::create('learning', function (Blueprint $table) {
            $table->id();
            $table->text('uuid')->nullable();
            $table->text('learning_title');
            $table->text('learning_description')->nullable();
            $table->boolean('status')->default(0);
            $table->integer('sort_order')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('team_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Add indexes for better performance
            $table->index('status');
            $table->index('sort_order');
            $table->index('created_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('learning');
    }
};
