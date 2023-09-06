<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('serials', function (Blueprint $table): void {
            $table->id();
            $table->boolean('adult');
            $table->unsignedBigInteger('budget')->nullable();
            $table->dateTime('first_air_date')->nullable();
            $table->string('homepage', 2000)->nullable();
            $table->boolean('in_production');
            $table->dateTime('last_air_date')->nullable();
            $table->string('name')->nullable();
            $table->integer('number_of_episodes');
            $table->string('number_of_seasons');
            $table->string('original_language');
            $table->string('original_name')->nullable();
            $table->text('overview')->nullable();
            $table->float('popularity')->nullable();
            $table->bigInteger('revenue')->nullable();
            $table->integer('runtime')->nullable();
            $table->string('status')->nullable();
            $table->string('tagline')->nullable();
            $table->string('type')->nullable();
            $table->float('vote_average');
            $table->unsignedBigInteger('vote_count');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('genre_serial', function (Blueprint $table): void {
            $table->foreignId('serial_id')->index()->constrained()->cascadeOnDelete();
            $table->foreignId('genre_id')->index()->constrained()->cascadeOnDelete();
            $table->primary(['serial_id', 'genre_id']);
        });

        Schema::create('country_serial', function (Blueprint $table): void {
            $table->foreignId('serial_id')->index()->constrained()->cascadeOnDelete();
            $table->foreignId('country_id')->index()->constrained()->cascadeOnDelete();
            $table->primary(['serial_id', 'country_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('genre_serial');
        Schema::dropIfExists('country_serial');
        Schema::dropIfExists('serials');
    }
};
