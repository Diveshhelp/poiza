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
        Schema::create('client_email_logs', function (Blueprint $table) {
            $table->id();
            $table->string('subject');
            $table->text('content');
            $table->integer('recipients_count')->default(0);
            $table->enum('status', ['queued', 'processing', 'completed', 'failed'])->default('queued');
            $table->integer('user_id')->nullable();
            $table->string('attachment')->nullable();
            $table->integer('success_count')->default(0);
            $table->integer('failure_count')->default(0);
            $table->timestamp('completed_at')->nullable();
            $table->integer('team_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_email_logs');
    }
};
