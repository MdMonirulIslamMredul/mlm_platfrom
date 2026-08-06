<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('investments', function (Blueprint $table) {
            $table->integer('days_received')->default(0)->after('daily_return');
            $table->decimal('total_earned', 15, 2)->default(0.00)->after('days_received');
            $table->timestamp('last_payout_at')->nullable()->after('total_earned');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('investments', function (Blueprint $table) {
            $table->dropColumn(['days_received', 'total_earned', 'last_payout_at']);
        });
    }
};
