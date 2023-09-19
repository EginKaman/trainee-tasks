<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::table('serials', function (Blueprint $table): void {
            $table->boolean('adult')->default(false)->change();
            $table->boolean('in_production')->default(false)->change();
            $table->integer('number_of_episodes')->default(0)->change();
            $table->string('number_of_seasons')->default(0)->change();
            $table->string('original_language')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('serials', function (Blueprint $table): void {
            $table->boolean('adult')->change();
            $table->boolean('in_production')->change();
            $table->integer('number_of_episodes')->change();
            $table->string('number_of_seasons')->change();
            $table->string('original_language')->change();
        });
    }
};
