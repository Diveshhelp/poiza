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
        Schema::table('tasks', function (Blueprint $table) {
            $table->integer('team_id')->nullable();
        });
        Schema::table('todos', function (Blueprint $table) {
            $table->integer('team_id')->nullable();
        });
        Schema::table('departments', function (Blueprint $table) {
            $table->integer('team_id')->nullable();
        });
        Schema::table('documents', function (Blueprint $table) {
            $table->integer('team_id')->nullable();
        });
        Schema::table('email_logs', function (Blueprint $table) {
            $table->integer('team_id')->nullable();
        });
        
        Schema::table('leaves', function (Blueprint $table) {
            $table->integer('team_id')->nullable();
        });
        Schema::table('sub_departments', function (Blueprint $table) {
            $table->integer('team_id')->nullable();
        }); 
        Schema::table('type_of_works', function (Blueprint $table) {
            $table->integer('team_id')->nullable();
        });

    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn('team_id');
        });
        Schema::table('todos', function (Blueprint $table) {
            $table->dropColumn('team_id');
        });
        Schema::table('departments', function (Blueprint $table) {
            $table->dropColumn('team_id');
        });
        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn('team_id');
        });
        Schema::table('email_logs', function (Blueprint $table) {
            $table->dropColumn('team_id');
        });
        
        Schema::table('leaves', function (Blueprint $table) {
            $table->dropColumn('team_id');
        });
        Schema::table('sub_departments', function (Blueprint $table) {
            $table->dropColumn('team_id');
        }); 
        Schema::table('type_of_works', function (Blueprint $table) {
            $table->dropColumn('team_id');
        });
    }
};
