<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('smtp_settings', function (Blueprint $table) {
            $table->id();
            $table->string('mail_host');
            $table->integer('mail_port');
            $table->string('mail_username');
            $table->string('mail_password');
            $table->string('mail_encryption')->default('tls');
            $table->string('mail_from_address');
            $table->string('mail_from_name');
            $table->integer('team_id')->nullable();
            $table->string('uuid');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('smtp_settings');
    }
};
