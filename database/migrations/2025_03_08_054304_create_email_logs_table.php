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
        Schema::create('email_logs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('documents_id')->nullable();
            $table->string('recipient_email');
            $table->string('subject');
            $table->text('message')->nullable();
            $table->datetime('sent_at');
            $table->bigInteger('sent_by')->nullable();
            $table->integer('attachments_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_logs');
    }
};
