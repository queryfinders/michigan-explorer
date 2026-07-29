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
        Schema::create('affiliate_promotions', function (Blueprint $table) {
            $table->id();
            $table->string('badge_text');
            $table->string('title');
            $table->text('subtitle');
            $table->string('cta_text');
            $table->foreignId('affiliate_link_id')->nullable()->constrained('affiliate_links')->onDelete('cascade');
            $table->string('desktop_image');
            $table->string('mobile_image')->nullable();
            $table->integer('priority')->default(1);
            $table->string('placement')->default('homepage_banner');
            $table->dateTime('starts_at')->nullable();
            $table->dateTime('ends_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affiliate_promotions');
    }
};
