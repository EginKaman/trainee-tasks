<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('people', function (Blueprint $table): void {
            $table->id();
            $table->boolean('adult');
            $table->text('also_known_as')->nullable();
            $table->text('biography')->nullable();
            $table->dateTime('birthday')->nullable();
            $table->dateTime('deathday')->nullable();
            $table->smallInteger('gender');
            $table->string('homepage')->nullable();
            $table->string('imdb_id')->nullable();
            $table->string('known_for_department')->nullable();
            $table->string('name');
            $table->string('place_of_birth')->nullable();
            $table->float('popularity');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('people');
    }
};
