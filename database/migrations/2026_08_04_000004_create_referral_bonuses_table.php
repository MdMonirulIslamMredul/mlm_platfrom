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
        Schema::create('referral_bonuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // The referrer earning the bonus
            $table->foreignId('from_user_id')->constrained('users')->onDelete('cascade'); // The referred team member who bought package
            $table->foreignId('package_id')->nullable()->constrained('packages')->onDelete('set null');
            $table->string('package_name');
            $table->decimal('package_price', 15, 2);
            $table->decimal('bonus_percentage', 5, 2)->default(10.00);
            $table->decimal('bonus_amount', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referral_bonuses');
    }
};
