<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('sms_messages', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('phone')->index();
            $table->string('text');
            $table->boolean('is_sent')->default(false);
            $table->string('channel');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sms_messages');
    }
};
