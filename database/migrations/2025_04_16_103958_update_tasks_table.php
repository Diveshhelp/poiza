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
        Schema::table('ownerships', function (Blueprint $table) {
            $table->integer('team_id')->nullable();
        });
        Schema::table('doc_categories', function (Blueprint $table) {
            $table->integer('team_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ownerships', function (Blueprint $table) {
            $table->dropColumn('team_id');
        });
        Schema::table('doc_categories', function (Blueprint $table) {
            $table->dropColumn('team_id');
        });
    }
    
};
