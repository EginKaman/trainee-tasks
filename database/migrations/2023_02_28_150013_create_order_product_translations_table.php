<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('order_product_translations', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('product_id')->constrained('order_products')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('locale')->index();
            $table->string('title');
            $table->text('description');

            $table->unique(['product_id', 'locale']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_product_translations');
    }
};
