<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->integer('is_master_task')->default(0)->nullable();
            $table->integer('create_before_days')->default(1)->nullable();
        });
    }
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn('is_master_task');
            $table->dropColumn('create_before_days');
        });
    }
};
