<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AffiliatePromotion;

class AffiliatePromotionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Creates one test promotion for every supported placement.
     */
    public function run(): void
    {
        // Wipe existing test data before re-seeding
        AffiliatePromotion::truncate();

        $promotions = [
            [
                'placement'         => 'homepage_banner',
                'badge_text'        => 'Special Promotion',
                'title'             => 'Save 20% on Romantic Lakefront Escapes',
                'subtitle'          => 'Book your next getaway through our exclusive affiliate partners and enjoy premium upgrades on Michigan\'s finest lakefront hotels.',
                'cta_text'          => 'Claim Offer',
                'affiliate_link_id' => 1,
                'desktop_image'     => 'images/promo_banner_1783508311655.png',
                'mobile_image'      => null,
                'priority'          => 1,
                'starts_at'         => null,
                'ends_at'           => null,
                'is_active'         => true,
            ],
            [
                'placement'         => 'homepage_sidebar',
                'badge_text'        => 'Limited Time Deal',
                'title'             => 'Exclusive Hotel Deals This Weekend',
                'subtitle'          => 'Up to 30% off select Michigan hotels when you book through our official affiliate partners.',
                'cta_text'          => 'View Deals',
                'affiliate_link_id' => 2,
                'desktop_image'     => 'images/promo_banner_1783508311655.png',
                'mobile_image'      => null,
                'priority'          => 1,
                'starts_at'         => null,
                'ends_at'           => now()->addDays(7),
                'is_active'         => true,
            ],
            [
                'placement'         => 'hotel_detail',
                'badge_text'        => 'Best Rate Guarantee',
                'title'             => 'Get the Lowest Price — Guaranteed',
                'subtitle'          => 'Book directly through our trusted partner and receive exclusive member discounts not available elsewhere.',
                'cta_text'          => 'Check Availability',
                'affiliate_link_id' => 1,
                'desktop_image'     => 'images/promo_banner_1783508311655.png',
                'mobile_image'      => null,
                'priority'          => 1,
                'starts_at'         => null,
                'ends_at'           => null,
                'is_active'         => true,
            ],
            [
                'placement'         => 'restaurant_detail',
                'badge_text'        => 'Reserve & Save',
                'title'             => 'Reserve Your Table — Free Cancellation',
                'subtitle'          => 'Book your dining experience hassle-free with free cancellation on most reservations through our partner platform.',
                'cta_text'          => 'Reserve Now',
                'affiliate_link_id' => 3,
                'desktop_image'     => 'images/promo_banner_1783508311655.png',
                'mobile_image'      => null,
                'priority'          => 1,
                'starts_at'         => null,
                'ends_at'           => null,
                'is_active'         => true,
            ],
            [
                'placement'         => 'attraction_detail',
                'badge_text'        => 'Skip the Line',
                'title'             => 'Book Attraction Tickets in Advance',
                'subtitle'          => 'Secure your entry tickets online through Viator and skip the queues — ideal for families and groups.',
                'cta_text'          => 'Book Tickets',
                'affiliate_link_id' => 2,
                'desktop_image'     => 'images/promo_banner_1783508311655.png',
                'mobile_image'      => null,
                'priority'          => 1,
                'starts_at'         => now()->subHour(),
                'ends_at'           => now()->addDays(30),
                'is_active'         => true,
            ],
            [
                'placement'         => 'blog_detail',
                'badge_text'        => 'Travel Smarter',
                'title'             => 'Plan Your Michigan Getaway Today',
                'subtitle'          => 'Browse curated packages and accommodation deals handpicked by our Michigan travel experts.',
                'cta_text'          => 'Explore Packages',
                'affiliate_link_id' => 3,
                'desktop_image'     => 'images/promo_banner_1783508311655.png',
                'mobile_image'      => null,
                'priority'          => 1,
                'starts_at'         => null,
                'ends_at'           => null,
                'is_active'         => true,
            ],
            [
                'placement'         => 'footer_banner',
                'badge_text'        => 'Partner Offer',
                'title'             => 'Stay More. Pay Less.',
                'subtitle'          => 'Unlock exclusive savings on extended stays across Michigan\'s top destinations with our Booking.com partnership.',
                'cta_text'          => 'Find My Deal',
                'affiliate_link_id' => 1,
                'desktop_image'     => 'images/promo_banner_1783508311655.png',
                'mobile_image'      => null,
                'priority'          => 1,
                'starts_at'         => null,
                'ends_at'           => null,
                'is_active'         => true,
            ],
        ];

        foreach ($promotions as $data) {
            AffiliatePromotion::create($data);
        }

        $this->command->info('✅ Seeded ' . count($promotions) . ' affiliate promotions across all placements.');
    }
}
