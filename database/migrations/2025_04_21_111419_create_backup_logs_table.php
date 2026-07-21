<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBackupLogsTable extends Migration
{
    public function up()
    {
        Schema::create('backup_logs', function (Blueprint $table) {
            $table->id();
            $table->string('file_path', 1000);
            $table->string('destination', 1000);
            $table->string('status', 20); // success, failed
            $table->unsignedBigInteger('size')->default(0);
            $table->text('error')->nullable();
            $table->timestamps();
            
            $table->index('status');
            $table->index('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('backup_logs');
    }
}