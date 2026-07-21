<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('notification_actions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('notification_id')->nullable();
            $table->bigInteger('read_by')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_actions');
    }
};