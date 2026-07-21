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
        Schema::create('nature_of_work', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('title', 512)->nullable(); // varchar(512) DEFAULT NULL
            $table->enum('status', ['0', '1'])->default('0')->comment('1: Active 0:Inactive'); // enum('0','1') DEFAULT '0'
            $table->datetime('created_at')->nullable(); // datetime DEFAULT NULL
            $table->integer('created_by')->nullable(); // int(11) DEFAULT NULL
            $table->integer('team_id')->nullable(); // int(11) DEFAULT NULL
            $table->integer('user_id')->nullable(); // int(11) DEFAULT NULL
            $table->timestamp('updated_at')->nullable(); // timestamp NULL DEFAULT NULL
            $table->datetime('deleted_at')->nullable(); // datetime DEFAULT NULL for soft deletes
            
            // Add indexes if needed
            $table->index('status');
            $table->index('created_by');
            $table->index('deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nature_of_work');
    }
};
