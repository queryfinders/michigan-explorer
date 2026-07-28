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
        Schema::table('affiliate_click_logs', function (Blueprint $table) {
            $table->string('country_code', 10)->nullable()->after('referer');
            $table->string('country_name')->nullable()->after('country_code');
            $table->string('state')->nullable()->after('country_name');
            $table->string('city')->nullable()->after('state');
            
            // Drop old country column if it exists
            if (Schema::hasColumn('affiliate_click_logs', 'country')) {
                $table->dropColumn('country');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('affiliate_click_logs', function (Blueprint $table) {
            $table->string('country')->nullable()->after('referer');
            $table->dropColumn(['country_code', 'country_name', 'state', 'city']);
        });
    }
};
