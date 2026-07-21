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
        Schema::table('billing_masters', function (Blueprint $table) {
            $table->string('invoice_matter')->nullable()->after('billing_details_id');
            $table->unsignedBigInteger('selected_team_id')->after('team_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('billing_masters', function (Blueprint $table) {
            $table->dropColumn(['invoice_matter', 'selected_team_id']);
        });
    }
};