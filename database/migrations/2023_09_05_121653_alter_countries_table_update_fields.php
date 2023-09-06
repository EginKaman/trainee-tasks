<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::table('countries', function (Blueprint $table): void {
            $table->dropColumn(['iso_2_code', 'title']);
            $table->string('iso_3166_1')->nullable();
            $table->string('english_name')->nullable();
            $table->string('native_name')->nullable();
        });

        Schema::create('country_movie', function (Blueprint $table): void {
            $table->foreignId('movie_id')->index()->constrained()->cascadeOnDelete();
            $table->foreignId('country_id')->index()->constrained()->cascadeOnDelete();
            $table->primary(['movie_id', 'country_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('country_movie');
        Schema::table('countries', function (Blueprint $table): void {
            $table->dropColumn(['iso_3166_1', 'english_name', 'native_name']);
            $table->string('iso_2_code')->nullable();
            $table->string('title')->nullable();
        });
    }
};
