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
        Schema::create('password_managers', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('url');
            $table->text('password'); // Encrypted password
            $table->text('note')->nullable();
            $table->string('category');
            $table->enum('status', ['active', 'archived'])->default('active');
            $table->bigInteger('team_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->bigInteger('created_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('password_managers');
    }
};
