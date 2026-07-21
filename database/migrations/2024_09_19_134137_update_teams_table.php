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
            $table->string('uuid')->nullable();

        });
        
        $allTeams = DB::table('teams')->whereNUll("uuid")->get();
        foreach($allTeams as $team) {
            DB::table('teams')
            ->where('id', $team->id)
            ->update(['uuid' => Str::uuid()->toString()]);
        }

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teams', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
};
