<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('jobs', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('bot_id')->index()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('status')->index();
            $table->string('type')->index();
            $table->string('name');
            $table->integer('count_workers');
            $table->string('cron')->nullable();
            $table->boolean('is_loop');
            $table->integer('pause')->nullable();
            $table->integer('repetitions')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
