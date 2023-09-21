<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('activities', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('name')->nullable()->index();
            $table->text('description')->nullable();
            $table->nullableUuidMorphs('subject');
            $table->json('properties')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
