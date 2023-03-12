<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table): void {
            $table->after('order_id', function (Blueprint $table): void {
                $table->nullableMorphs('payable');
            });
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table): void {
            \Illuminate\Support\Facades\DB::table('payments')
                ->update([
                    'order_id' => \Illuminate\Support\Facades\DB::raw('`payable_id`'),
                ]);

            $table->dropMorphs('payable');
        });
    }
};
