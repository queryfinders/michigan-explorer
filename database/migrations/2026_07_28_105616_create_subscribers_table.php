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
        Schema::create('subscribers', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique()->index();
            $table->string('source', 50); // explorer_club, footer
            $table->string('ip_address', 45);
            $table->text('user_agent')->nullable();
            $table->string('verification_token')->nullable()->unique();
            $table->timestamp('verification_token_expires_at')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->boolean('is_verified')->default(0);
            $table->boolean('is_active')->default(1);
            $table->timestamp('unsubscribed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscribers');
    }
};
