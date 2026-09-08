<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('diora_order_items', function (Blueprint $table) {
            $table->string('set_type')->default('Squere Dabi')->after('diora_product_id'); // Round Dabi, Choras Dabi, Plate
        });
    }

    public function down(): void
    {
        Schema::table('diora_order_items', function (Blueprint $table) {
            $table->dropColumn('set_type');
        });
    }
};