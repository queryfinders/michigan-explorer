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
        Schema::create('affiliate_click_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('affiliate_link_id')->index();
            $table->string('entity_type')->index();
            $table->unsignedBigInteger('entity_id')->index();
            $table->uuid('visitor_id')->index();
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->text('referer')->nullable();
            $table->string('country')->nullable();
            $table->timestamp('clicked_at')->index();
            $table->timestamps();

            $table->foreign('affiliate_link_id')->references('id')->on('affiliate_links')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affiliate_click_logs');
    }
};
