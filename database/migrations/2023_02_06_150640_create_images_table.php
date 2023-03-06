<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('images', function (Blueprint $table): void {
            $table->id();
            $table->string('filename');
            $table->string('path');
            $table->string('hash');
            $table->string('mimetype');
            $table->unsignedInteger('size');
            $table->unsignedInteger('height');
            $table->unsignedInteger('width');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('images');
    }
};
