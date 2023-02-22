<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('processing_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('image_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('name');
            $table->string('path');
            $table->string('mimetype');
            $table->unsignedInteger('original_size');
            $table->unsignedInteger('original_width');
            $table->unsignedInteger('original_height');
            $table->unsignedInteger('kraked_size')->nullable();
            $table->unsignedInteger('kraked_width')->nullable();
            $table->unsignedInteger('kraked_height')->nullable();
            $table->unsignedInteger('saved_bytes')->nullable();
            $table->enum('status', ['pending', 'success', 'failed', 'skipped'])->default('pending');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('processing_images');
    }
};
