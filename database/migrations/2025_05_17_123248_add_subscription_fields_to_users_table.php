<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Step 1: Add the columns without default values for complex fields
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('subscribed_to_emails')->default(true);
            $table->string('unsubscribe_token')->nullable(); // First add as nullable
            $table->timestamp('unsubscribed_at')->nullable();
        });
        
        // Step 2: Generate and assign tokens for all existing users
        $users = DB::table('users')->whereNull('unsubscribe_token')->get();
        foreach ($users as $user) {
            DB::table('users')
                ->where('id', $user->id)
                ->update(['unsubscribe_token' => Str::random(32)]);
        }
        
        // Step 3: Now make the field required after populating values
        Schema::table('users', function (Blueprint $table) {
            $table->string('unsubscribe_token')->nullable(false)->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['subscribed_to_emails', 'unsubscribe_token', 'unsubscribed_at']);
        });
    }
};