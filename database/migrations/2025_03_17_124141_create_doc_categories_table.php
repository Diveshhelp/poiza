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
        Schema::create('doc_categories', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->nullable();
            $table->string('category_title')->nullable();
            $table->integer('row_status')->comment('0:Inactive 1:Active')->nullable();
            $table->integer('created_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doc_categories');
    }
};