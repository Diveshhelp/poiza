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
        Schema::table('my_referrals', function (Blueprint $table) {
            $table->boolean('bonus_applied')->default(false)->after('is_join');
            $table->timestamp('bonus_applied_at')->nullable()->after('bonus_applied');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('my_referrals', function (Blueprint $table) {
            $table->dropColumn('bonus_applied');
            $table->dropColumn('bonus_applied_at');
        });
    }
};
