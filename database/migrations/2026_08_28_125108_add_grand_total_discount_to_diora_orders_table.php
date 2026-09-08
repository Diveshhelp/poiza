<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('diora_orders', function (Blueprint $table) {
            $table->decimal('subtotal', 12, 2)->default(0)->after('status');
            $table->string('discount_type')->default('percentage')->after('subtotal'); // 'percentage' or 'flat'
            $table->decimal('discount_value', 10, 2)->default(0)->after('discount_type'); // e.g. 5% or 500 INR
            $table->decimal('discount_amount', 12, 2)->default(0)->after('discount_value'); // Calculated flat discount in INR
        });
    }

    public function down(): void
    {
        Schema::table('diora_orders', function (Blueprint $table) {
            $table->dropColumn(['subtotal', 'discount_type', 'discount_value', 'discount_amount']);
        });
    }
};