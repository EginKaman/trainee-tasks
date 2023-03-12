<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table): void {
            \Illuminate\Support\Facades\DB::table('payments')
                ->update([
                    'payable_id' => \Illuminate\Support\Facades\DB::raw('`order_id`'),
                    'payable_type' => 'App\Models\Order',
                ]);
            $table->dropConstrainedForeignId('order_id');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table): void {
            $table->after('payable_id', function (Blueprint $table): void {
                $table->foreignId('order_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            });
        });
    }
};
