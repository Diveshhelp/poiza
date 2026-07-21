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
        Schema::create('my_referrals', function (Blueprint $table) {
            $table->id();
            $table->integer('refer_by')->nullable();
            $table->integer('refer_to')->nullable();
            $table->integer('is_join')->comment('0:pending 1:join')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('my_referrals');
    }
};
