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
        Schema::table('blogs', function (Blueprint $table) {
            $table->integer('views')->default(0)->after('status');
        });

        Schema::table('blog_categories', function (Blueprint $table) {
            $table->string('icon')->nullable()->after('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->dropColumn('views');
        });

        Schema::table('blog_categories', function (Blueprint $table) {
            $table->dropColumn('icon');
        });
    }
};
