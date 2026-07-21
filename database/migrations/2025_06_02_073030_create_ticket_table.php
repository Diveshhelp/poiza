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
        Schema::create('ticket', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uuid')->nullable();
            $table->string('establish_name', 512)->nullable();
            $table->integer('nature_of_work_id')->nullable();
            $table->integer('branch_id')->nullable();
            $table->string('ticket_unique_no')->nullable();
            $table->integer('generated_by')->nullable();
            $table->integer('approve_by')->nullable();
            $table->dateTime('ticket_approve_date')->nullable();
            $table->integer('work_alloted_to')->nullable();
            $table->integer('ticket_close_by')->nullable();
            $table->integer('tocket_close_approve_by')->nullable();
            $table->dateTime('ticket_close_approve_date')->nullable();
            $table->integer('ticket_transfered_to')->nullable();
            $table->dateTime('ticket_transfered_date')->nullable();
            $table->enum('status', ['0', '1'])->default('0')->comment('1: Active 0:Inactive');
            $table->longText('json_data')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->integer('created_by')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->integer('team_id')->nullable(); // int(11) DEFAULT NULL
            $table->integer('user_id')->nullable(); // int(11) DEFAULT NULL
         
            
            $table->index('created_by');
            $table->index('deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket');
    }
};
