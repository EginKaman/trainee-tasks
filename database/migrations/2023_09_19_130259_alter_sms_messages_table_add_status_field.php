<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::table('sms_messages', function (Blueprint $table): void {
            $table->dropColumn(['is_sent']);
            $table->string('status')->default('pending');
        });
    }

    public function down(): void
    {
        Schema::table('sms_messages', function (Blueprint $table): void {
            $table->dropColumn(['status']);
            $table->boolean('is_sent')->default(false);
        });
    }
};
