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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->nullable();
            $table->string('ownership_name')->nullable();
            $table->integer('department_id')->nullable();
            $table->string('doc_title')->nullable();
            $table->string('doc_name')->nullable();
            $table->integer('doc_validity')->comment('Refer constant for all key and values');
            $table->string('doc_renewal_dt')->nullable();
            $table->datetime('doc_update_date')->nullable();
            $table->string('doc_file')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
