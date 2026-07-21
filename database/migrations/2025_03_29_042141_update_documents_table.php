<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn('doc_update_date');
            $table->date('doc_update_date')->nullable();
        });
    }
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
        });
    }
};
