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
        Schema::create('document_notes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('documents_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->bigInteger('team_id')->nullable();
            $table->text('content');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_notes');
    }
};
