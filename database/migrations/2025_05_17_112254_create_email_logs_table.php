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
        Schema::create('customer_email_logs', function (Blueprint $table) {
            $table->id();
            $table->string('subject');
            $table->text('content');
            $table->integer('recipients_count')->default(0);
            $table->enum('status', ['queued', 'processing', 'completed', 'failed'])->default('queued');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('attachment')->nullable();
            $table->integer('success_count')->default(0);
            $table->integer('failure_count')->default(0);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_email_logs');
    }
};
