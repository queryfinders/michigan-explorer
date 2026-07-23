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
        Schema::table('hotels', function (Blueprint $table) {
            $table->string('video')->nullable()->after('featured_image');
        });
        Schema::table('attractions', function (Blueprint $table) {
            $table->string('video')->nullable()->after('featured_image');
        });
        Schema::table('events', function (Blueprint $table) {
            $table->string('video')->nullable()->after('featured_image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hotels', function (Blueprint $table) {
            $table->dropColumn('video');
        });
        Schema::table('attractions', function (Blueprint $table) {
            $table->dropColumn('video');
        });
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('video');
        });
    }
};
