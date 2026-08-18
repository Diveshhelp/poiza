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
        Schema::table('my_products', function (Blueprint $table) {
            $table->decimal('price', 10, 2)->nullable();
            $table->string('packing')->nullable()->after('price');
            $table->string('type_of_model')->nullable()->after('packing');
            $table->string('material')->nullable()->after('type_of_model');
            $table->string('category_name')->nullable()->after('category_id');
            $table->string('model_key')->nullable()->unique()->after('category_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('my_products', function (Blueprint $table) {
            $table->dropColumn([
                'price',
                'packing',
                'type_of_model',
                'material',
                'category_name',
                'model_key'
            ]);
        });
    }
};
