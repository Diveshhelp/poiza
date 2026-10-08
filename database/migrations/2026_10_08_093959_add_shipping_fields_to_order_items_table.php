<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->unsignedInteger('ready_qty')->default(0)->after('quantity');
            $table->string('item_status')->default('in_production')->after('ready_qty'); // 'in_production', 'ready_to_ship', 'shipped'
        });

        // Also ensure shipping_status exists in your process_details / order_processes table
        if (Schema::hasTable('process_details') && !Schema::hasColumn('process_details', 'shipping_status')) {
            Schema::table('process_details', function (Blueprint $table) {
                $table->string('shipping_status')->default('pending')->after('color_status');
            });
        }
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn(['ready_qty', 'item_status']);
        });

        if (Schema::hasTable('process_details') && Schema::hasColumn('process_details', 'shipping_status')) {
            Schema::table('process_details', function (Blueprint $table) {
                $table->dropColumn('shipping_status');
            });
        }
    }
};