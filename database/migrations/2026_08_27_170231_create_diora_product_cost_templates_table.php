<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('diora_product_cost_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('diora_product_id')->unique()->constrained('diora_products')->cascadeOnDelete();
            
            // Cost heads
            $table->decimal('mortise_set', 10, 2)->default(320);
            $table->decimal('pinjaru_dabi', 10, 2)->default(80);
            $table->decimal('box', 10, 2)->default(25);
            $table->decimal('packaging', 10, 2)->default(10);
            $table->decimal('masalo', 10, 2)->default(25);
            $table->decimal('profit_margin_percent', 5, 2)->default(15);

            $table->decimal('buff_matt', 10, 2)->default(42);
            $table->decimal('buff_glossy', 10, 2)->default(82);
            $table->decimal('pvd_cost', 10, 2)->default(96);

            $table->decimal('black_color', 10, 2)->default(40);
            $table->decimal('antique_color', 10, 2)->default(60);

            $table->decimal('two_pc_matt_black', 10, 2)->default(26);
            $table->decimal('two_pc_matt_buff', 10, 2)->default(20);
            $table->decimal('two_pc_matt_bolt', 10, 2)->default(2);
            $table->decimal('two_pc_pvd_color', 10, 2)->default(30);
            $table->decimal('two_pc_pvd_buff', 10, 2)->default(26);
            $table->decimal('two_pc_pvd_bolt', 10, 2)->default(2);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('diora_product_cost_templates');
    }
};