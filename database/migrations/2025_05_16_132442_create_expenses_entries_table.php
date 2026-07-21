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
        Schema::create('expenses_entries', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->text('note')->nullable();
            $table->bigInteger('team_id');
            $table->bigInteger('user_id');
            $table->decimal('amount', 10, 2);
            $table->date('expense_date');
            $table->enum('status', ['paid', 'pending', 'cancelled'])->default('pending');
            $table->bigInteger('created_by');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses_entries');
    }
};