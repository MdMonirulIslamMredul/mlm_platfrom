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
        Schema::table('about_settings', function (Blueprint $table) {
            $table->string('about_image_1')->nullable();
            $table->string('about_image_2')->nullable();
            $table->string('about_image_3')->nullable();
            $table->string('about_image_4')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('about_settings', function (Blueprint $table) {
            $table->dropColumn(['about_image_1', 'about_image_2', 'about_image_3', 'about_image_4']);
        });
    }
};
