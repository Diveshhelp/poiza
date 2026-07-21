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
        Schema::table('users', function (Blueprint $table) {
            // Add missing columns from source database if they don't exist
            if (!Schema::hasColumn('users', 'mobile')) {
                $table->string('mobile')->nullable();
            }
            
            if (!Schema::hasColumn('users', 'status')) {
                $table->enum('status', ['0', '1', '2', '3'])->nullable()->comment('0 = pending 1 = active 2 = block 3 = reject');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Only drop the columns we added in this migration
            $table->dropColumn([
                'mobile',
                'status'
            ]);
        });
    }
};