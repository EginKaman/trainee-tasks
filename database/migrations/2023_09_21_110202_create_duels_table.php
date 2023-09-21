<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('duels', function (Blueprint $table): void {
            $table->uuid('id');
            $table->foreignUuid('tournament_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('status')->index();
            $table->foreignId('winner_id')->nullable()
                ->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamp('finished_at')->nullable();
            $table->timestamps();
        });

        Schema::create('duel_user', function (Blueprint $table): void {
            $table->foreignUuid('duel_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->bigInteger('result_score')->default(0);

            $table->primary(['duel_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('duels');
    }
};
