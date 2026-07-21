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
       Schema::create('resumes', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid')->nullable()->unique();
            $table->string('candidate_name', 512)->nullable();
            $table->string('email')->nullable();
            $table->integer('candidate_code')->nullable();
            $table->string('mobile', 20)->nullable();
            $table->unsignedInteger('referance_id')->nullable();
            $table->string('interview_time')->nullable();
            $table->string('current_ctc')->nullable();
            $table->string('expected_ctc')->nullable();
            $table->string('notice_period')->nullable();
            $table->string('domain')->nullable();
            $table->text('other_info')->nullable();
            $table->integer('year_of_exp')->nullable();
            $table->enum('status', ['0', '1', '2', '3', '4'])->default('0')
                ->comment('0:Draft, 1:Ready, 2:Hold, 3:Rejected, 4:Other');
            $table->dateTime('created_at')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->unsignedInteger('followup_id')->nullable();
            $table->unsignedInteger('resume_attachment_id')->nullable();

            $table->index('created_by');
            $table->index('deleted_at');
        });


        Schema::create('resume_attachments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('resume_id');
            $table->string('file_path');
            $table->string('file_name')->nullable();
            $table->string('file_type')->nullable(); // pdf, docx, etc.
            $table->timestamps();

            $table->foreign('resume_id')->references('id')->on('resumes')->onDelete('cascade');
        });

        Schema::create('resume_followups', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('resume_id');
            $table->text('note')->nullable();
            $table->dateTime('followup_date')->nullable();
            $table->unsignedInteger('followed_by')->nullable(); // user_id
            $table->timestamps();

            $table->foreign('resume_id')->references('id')->on('resumes')->onDelete('cascade');
        });

        Schema::create('resume_references', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('resume_id');
            $table->text('ref_name')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resumes');
        Schema::dropIfExists('resume_attachments');
        Schema::dropIfExists('resume_followups');
        Schema::dropIfExists('resume_references');
    }
};
