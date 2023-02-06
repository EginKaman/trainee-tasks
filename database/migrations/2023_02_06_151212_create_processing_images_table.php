<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('processing_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('image_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('kraken_id');
            $table->string('name');
            $table->string('original_size');
            $table->string('kraked_size');
            $table->string('saved_bytes');
            $table->boolean('success');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('processing_images');
    }
};
