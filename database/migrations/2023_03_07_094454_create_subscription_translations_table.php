<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('subscription_translations', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('subscription_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('locale');
            $table->string('title');
            $table->string('description');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_translations');
    }
};
