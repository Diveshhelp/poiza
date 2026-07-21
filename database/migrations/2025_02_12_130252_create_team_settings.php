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
        Schema::create('team_settings', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->nullable();
            $table->text('app_name')->nullable();
            $table->tinyInteger('setting_status')->comment('0: False, 1: True')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_settings');
    }
};