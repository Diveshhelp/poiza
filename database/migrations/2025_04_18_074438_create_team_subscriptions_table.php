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
        Schema::create('team_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('team_id')->nullable();
            $table->string('plan_id')->nullable();
            $table->string('status'); // active, canceled, expired, etc.
            $table->timestamp('starts_at');
            $table->timestamp('ends_at')->nullable();
            $table->timestamp('trial_ends_at')->nullable();
            $table->boolean('auto_renew')->default(false);
            $table->string('razorpay_subscription_id')->nullable()->unique();
            $table->json('razorpay_subscription_data')->nullable(); // Store full subscription data
            $table->text('cancellation_reason')->nullable();
            $table->timestamp('canceled_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_subscriptions');
    }
};