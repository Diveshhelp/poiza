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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 50);
            $table->string('last_name', 50);
            $table->string('email', 100);
            $table->string('company', 100)->nullable();
            $table->string('inquiry_type', 50); // support, billing, sales, partnership, feedback, other
            $table->text('message');
            $table->string('reference_id', 20)->unique();
            $table->string('status', 20)->default('pending'); // pending, read, responded, closed
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};