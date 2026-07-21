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
        Schema::create('expenses_logs', function (Blueprint $table) {
            $table->id();
            $table->json('old_content')->nullable();
            $table->json('new_content')->nullable();
            $table->unsignedBigInteger('team_id')->index(); // Team ID without foreign key constraint
            $table->unsignedBigInteger('user_id')->index(); // User ID without foreign key constraint
            $table->unsignedBigInteger('created_by')->index(); // Created by without foreign key constraint
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses_logs');
    }
};