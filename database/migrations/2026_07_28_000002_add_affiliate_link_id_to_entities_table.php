<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add affiliate_link_id column to hotels
        Schema::table('hotels', function (Blueprint $table) {
            $table->unsignedBigInteger('affiliate_link_id')->nullable()->after('affiliate_url');
        });

        // 2. Add affiliate_link_id column to restaurants
        Schema::table('restaurants', function (Blueprint $table) {
            $table->unsignedBigInteger('affiliate_link_id')->nullable()->after('affiliate_url');
        });

        // 3. Data Migration Logic
        // Hotels
        $hotels = DB::table('hotels')->whereNotNull('affiliate_url')->where('affiliate_url', '<>', '')->get();
        foreach ($hotels as $hotel) {
            // Find or create affiliate link for this URL
            $linkId = DB::table('affiliate_links')->where('link', $hotel->affiliate_url)->value('id');
            if (!$linkId) {
                $linkId = DB::table('affiliate_links')->insertGetId([
                    'name' => 'Hotel: ' . $hotel->name,
                    'provider' => $this->guessProvider($hotel->affiliate_url),
                    'link' => $hotel->affiliate_url,
                    'is_active' => true,
                    'total_clicks' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            DB::table('hotels')->where('id', $hotel->id)->update(['affiliate_link_id' => $linkId]);
        }

        // Restaurants
        $restaurants = DB::table('restaurants')->whereNotNull('affiliate_url')->where('affiliate_url', '<>', '')->get();
        foreach ($restaurants as $restaurant) {
            // Find or create affiliate link for this URL
            $linkId = DB::table('affiliate_links')->where('link', $restaurant->affiliate_url)->value('id');
            if (!$linkId) {
                $linkId = DB::table('affiliate_links')->insertGetId([
                    'name' => 'Restaurant: ' . $restaurant->name,
                    'provider' => $this->guessProvider($restaurant->affiliate_url),
                    'link' => $restaurant->affiliate_url,
                    'is_active' => true,
                    'total_clicks' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            DB::table('restaurants')->where('id', $restaurant->id)->update(['affiliate_link_id' => $linkId]);
        }

        // 4. Add foreign keys after populating data
        Schema::table('hotels', function (Blueprint $table) {
            $table->foreign('affiliate_link_id')->references('id')->on('affiliate_links')->onDelete('set null');
        });

        Schema::table('restaurants', function (Blueprint $table) {
            $table->foreign('affiliate_link_id')->references('id')->on('affiliate_links')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hotels', function (Blueprint $table) {
            $table->dropForeign(['affiliate_link_id']);
            $table->dropColumn('affiliate_link_id');
        });

        Schema::table('restaurants', function (Blueprint $table) {
            $table->dropForeign(['affiliate_link_id']);
            $table->dropColumn('affiliate_link_id');
        });
    }

    /**
     * Guess provider from URL helper
     */
    private function guessProvider(string $url): string
    {
        $host = parse_url($url, PHP_URL_HOST);
        if (!$host) return 'Other';
        
        $host = strtolower($host);
        if (str_contains($host, 'booking.com')) return 'Booking.com';
        if (str_contains($host, 'expedia.com')) return 'Expedia';
        if (str_contains($host, 'opentable.com')) return 'OpenTable';
        if (str_contains($host, 'agoda.com')) return 'Agoda';
        if (str_contains($host, 'tripadvisor.com')) return 'TripAdvisor';
        
        return ucwords(str_replace('www.', '', $host));
    }
};
