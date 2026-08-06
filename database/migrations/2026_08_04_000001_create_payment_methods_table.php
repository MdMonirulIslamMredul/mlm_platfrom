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
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., bKash, Nagad, Rocket, City Bank
            $table->string('type'); // 'MFS' or 'Bank'
            $table->string('number'); // Account number or Mobile Banking number
            $table->string('image')->nullable(); // Logo / QR code image path
            $table->text('instruction')->nullable(); // Additional note/instruction
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
