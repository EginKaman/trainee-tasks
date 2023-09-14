<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\{DB, Schema};

return new class() extends Migration {
    public function up(): void
    {
        Schema::table('webhook_events', function (Blueprint $table): void {
            $table->after('order_id', function (Blueprint $table): void {
                $table->morphs('eventable');
            });
        });
        DB::table('webhook_events')->update([
            'eventable_id' => DB::raw('order_id'),
            'eventable_type' => 'App\Models\Order',
        ]);
        Schema::table('webhook_events', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('order_id');
        });
    }

    public function down(): void
    {
        Schema::table('webhook_events', function (Blueprint $table): void {
            $table->after('eventable_id', function (Blueprint $table): void {
                $table->foreignId('order_id')->nullable()->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            });
        });
        DB::table('webhook_events')->update([
            'order_id' => DB::raw('eventable_id'),
        ]);
        Schema::table('webhook_events', function (Blueprint $table): void {
            $table->dropMorphs('eventable');
        });
    }
};
