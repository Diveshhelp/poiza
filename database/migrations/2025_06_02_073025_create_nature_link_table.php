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
        Schema::create('nature_link', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->integer('nature_id')->nullable()->comment('Ref: nature_of_work->id'); // int(11) DEFAULT NULL
            $table->tinyInteger('status')->default(0); // tinyint(1) NOT NULL DEFAULT 0
            $table->timestamp('created_at')->nullable(); // timestamp NULL DEFAULT NULL
            $table->timestamp('updated_at')->nullable(); // timestamp NULL DEFAULT NULL
            $table->integer('created_by')->nullable(); // int(11) DEFAULT NULL
            $table->integer('updated_by')->nullable(); // int(11) DEFAULT NULL
            $table->integer('team_id')->nullable(); // int(11) DEFAULT NULL
            $table->integer('user_id')->nullable(); // int(11) DEFAULT NULL
            $table->timestamp('deleted_at')->nullable(); // timestamp NULL DEFAULT NULL for soft deletes
            
            // Add indexes for better performance
            $table->index('nature_id');
            $table->index('status');
            $table->index('created_by');
            $table->index('updated_by');
            $table->index('deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nature_link');
    }
};
