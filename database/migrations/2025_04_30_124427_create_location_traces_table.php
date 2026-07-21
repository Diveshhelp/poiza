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
        Schema::create('location_traces', function (Blueprint $table) {
            $table->id();
            $table->string('user_id', 20)->nullable();
            // IP and location tracking
            $table->string('ip_address', 45)->nullable(); // IPv6 can be up to 45 chars
            $table->string('user_agent')->nullable();
            $table->string('country', 2)->nullable(); // Country code
            $table->string('city', 100)->nullable();
            $table->string('region', 100)->nullable(); // State/Province
            $table->json('location_data')->nullable(); // For storing additional location data
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('location_traces');
    }
};