<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table): void {
            $table->id();
            $table->string('image')->nullable();
            $table->unsignedInteger('price');
            $table->string('period');
            $table->string('stripe_id');
            $table->string('paypal_id');
            $table->timestamps();
        });

        Schema::create('subscription_user', function (Blueprint $table): void {
            $table->foreignId('user_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('subscription_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('method_id')->nullable();
            $table->string('method')->nullable();
            $table->string('status')->nullable();
            $table->timestamp('canceled_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->timestamps();

            $table->primary(['user_id', 'subscription_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_user');
        Schema::dropIfExists('subscriptions');
    }
};
