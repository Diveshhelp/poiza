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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('client_name')->nullable();
            $table->string('client_email')->nullable();
            $table->string('mobile_number')->nullable();
            $table->string('address')->nullable();
            $table->integer('team_id')->nullable();
            $table->integer('email_count')->nullable();
            $table->integer('sms_count')->nullable();
            $table->integer('whatsapp_count')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
