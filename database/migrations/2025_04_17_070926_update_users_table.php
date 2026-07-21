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
        Schema::table('teams', function (Blueprint $table) {
            $table->text('is_trial_mode')->nullable();
            $table->date('trial_start_date')->nullable();
            $table->date('trial_end_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    { 
        Schema::table('teams', function (Blueprint $table) {
            $table->dropColumn('is_trial_mode');
            $table->dropColumn('trial_start_date');
            $table->dropColumn('trial_end_date');
        });
    }
};
