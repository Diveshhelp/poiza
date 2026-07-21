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
        Schema::create('learning_user', function (Blueprint $table) {
            $table->id();
            $table->integer('learning_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('admin_id')->nullable();
            $table->text('comment')->nullable();
            $table->boolean('status')->default(0);
            $table->dateTime('complete_on')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Add indexes for better performance
            $table->index(['learning_id', 'user_id']);
            $table->index('status');
            $table->index('complete_on');
            $table->index('created_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('learning_user');
    }
};
