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
        Schema::create('hotel_booking_feature', function (Blueprint $table) {
            $table->foreignId('hotel_id')->constrained()->onDelete('cascade');
            $table->foreignId('booking_feature_id')->constrained('booking_features')->onDelete('cascade');
            $table->primary(['hotel_id', 'booking_feature_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotel_booking_feature');
    }
};
