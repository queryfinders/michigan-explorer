<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\EventCategory;
use App\Models\EventFaq;
use Illuminate\Support\Str;

class EventSeeder extends Seeder
{
    public function run()
    {
        // 0. Clear existing event data to prevent duplication
        EventFaq::query()->delete();
        \App\Models\Seo::where('seoable_type', 'App\Models\Event')->delete();
        Event::query()->delete();
        EventCategory::query()->delete();

        // 1. Create categories
        $categoriesData = [
            ['name' => 'Festivals & Fairs', 'icon' => 'fas fa-otter'],
            ['name' => 'Music & Concerts', 'icon' => 'fas fa-music'],
            ['name' => 'Art & Culture', 'icon' => 'fas fa-palette'],
            ['name' => 'Sports & Outdoors', 'icon' => 'fas fa-running'],
            ['name' => 'Food & Drink', 'icon' => 'fas fa-utensils'],
            ['name' => 'Community & Business', 'icon' => 'fas fa-users'],
        ];

        $categories = [];
        foreach ($categoriesData as $cat) {
            $categories[] = EventCategory::create([
                'name' => $cat['name'],
                'slug' => Str::slug($cat['name']),
                'icon' => $cat['icon'],
                'status' => 1,
                'is_featured' => 1
            ]);
        }

        // 2. Events data with dynamic relative dates
        // Event 1: Starts today, ends in 2 days (This Week, This Month)
        // Event 2: Starts in 3 days, ends in 4 days (This Week, This Month)
        // Event 3: Starts in 10 days, ends in 12 days (This Month, but NOT This Week)
        // Event 4: Starts in 15 days, ends in 17 days (This Month, but NOT This Week)
        // Event 5: Starts in 45 days, ends in 47 days (Future Event, NOT This Month)
        // Event 6: Starts 10 days ago, ended 8 days ago (Past Event)
        $events = [
            [
                'name' => 'Traverse City National Cherry Festival',
                'category_index' => 0,
                'description' => '<p>The National Cherry Festival is Traverse City\'s signature event, drawing over 500,000 visitors annually. Enjoy cherry pie eating contests, parades, live concerts, and air shows over the beautiful Grand Traverse Bay.</p><p>Featuring local food vendors, arts and crafts, and family activities, it\'s the ultimate celebration of Michigan\'s cherry harvest.</p>',
                'short_description' => 'Celebrate Traverse City\'s cherry harvest with parades, contests, and family fun.',
                'start_date' => now()->format('Y-m-d H:i:s'),
                'end_date' => now()->addDays(2)->format('Y-m-d H:i:s'),
                'venue_name' => 'Open Space Park',
                'address' => '300 E Grandview Pkwy',
                'city' => 'Traverse City',
                'phone' => '231-947-4230',
                'website' => 'https://www.cherryfestival.org',
                'featured_image' => 'https://images.unsplash.com/photo-1528825871115-3581a5387919?w=800&auto=format&fit=crop',
                'map_iframe' => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m13!1d2839.8735232870425!2d-85.62319082343888!3d44.76295557107198!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x881e32bcd021adbf%3A0xe5a3bb4c878f8c!2sOpen%20Space%20Park!5e0!3m2!1sen!2sus!4v1700000000000!5m2!1sen!2sus" width="100%" height="350" style="border:0;" allowfullscreen="" loading="lazy"></iframe>',
                'faqs' => [
                    ['question' => 'Is there an admission fee?', 'answer' => '<p>Most events at the National Cherry Festival are free to the public, though select concerts and air show viewing zones require ticket purchases.</p>'],
                    ['question' => 'Where should I park?', 'answer' => '<p>Public parking decks are available in downtown Traverse City. Park and ride shuttle services are also offered during peak weekend events.</p>']
                ]
            ],
            [
                'name' => 'Detroit International Jazz Festival',
                'category_index' => 1,
                'description' => '<p>The Detroit Jazz Festival is the world\'s largest free jazz festival. Spanning several city blocks in downtown Detroit, it features world-class jazz musicians, local talent, educational workshops, and jam sessions.</p><p>Join us at Hart Plaza and Campus Martius for a soulful weekend of music, food, and culture.</p>',
                'short_description' => 'Experience the world\'s largest free jazz festival across downtown Detroit.',
                'start_date' => now()->addDays(3)->format('Y-m-d H:i:s'),
                'end_date' => now()->addDays(4)->format('Y-m-d H:i:s'),
                'venue_name' => 'Hart Plaza',
                'address' => '1 Hart Plaza',
                'city' => 'Detroit',
                'phone' => '313-469-6564',
                'website' => 'https://www.detroitjazzfest.org',
                'featured_image' => 'https://images.unsplash.com/photo-1511192336575-5a79af67a629?w=800&auto=format&fit=crop',
                'map_iframe' => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m13!1d2948.749557973719!2d-83.04642452355416!3d42.327618271195655!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x883b2d3126f56c6b%3A0x6b7a2d8e434cd6d7!2sHart%20Plaza!5e0!3m2!1sen!2sus!4v1700000000001!5m2!1sen!2sus" width="100%" height="350" style="border:0;" allowfullscreen="" loading="lazy"></iframe>',
                'faqs' => [
                    ['question' => 'Are food and drinks available?', 'answer' => '<p>Yes, numerous food trucks and beverage booths serving local Michigan crafts and classic festival snacks are spread throughout Hart Plaza.</p>'],
                    ['question' => 'Can I bring lawn chairs?', 'answer' => '<p>Lawn chairs are highly recommended! You can set them up in designated viewing areas in front of the main stages.</p>']
                ]
            ],
            [
                'name' => 'Grand Rapids ArtPrize 2026',
                'category_index' => 2,
                'description' => '<p>ArtPrize is an open, international art competition that turns downtown Grand Rapids into a massive public art gallery. Artworks are exhibited in museums, parks, restaurants, bridges, and shop windows.</p><p>Visitors vote to decide the winners of cash prizes, making it a highly interactive and engaging community celebration of creativity.</p>',
                'short_description' => 'Explore public art installations spanning downtown Grand Rapids in this open competition.',
                'start_date' => now()->addDays(10)->format('Y-m-d H:i:s'),
                'end_date' => now()->addDays(12)->format('Y-m-d H:i:s'),
                'venue_name' => 'Downtown Grand Rapids',
                'address' => '171 Monroe Ave NW',
                'city' => 'Grand Rapids',
                'phone' => '616-214-7967',
                'website' => 'https://www.artprize.org',
                'featured_image' => 'https://images.unsplash.com/photo-1513364776144-60967b0f800f?w=800&auto=format&fit=crop',
                'map_iframe' => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m13!1d2930.3475960010996!2d-85.67268862353457!3d42.964648771171855!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8819ad77d4c7b2a9%3A0xc3f34bd8c0e2d312!2sGrand%20Rapids%20Art%20Museum!5e0!3m2!1sen!2sus!4v1700000000002!5m2!1sen!2sus" width="100%" height="350" style="border:0;" allowfullscreen="" loading="lazy"></iframe>',
                'faqs' => [
                    ['question' => 'How do I vote for art?', 'answer' => '<p>You can vote using the official ArtPrize mobile app. Simply register, verify your location in Grand Rapids, and enter the artist codes shown next to the exhibits.</p>']
                ]
            ],
            [
                'name' => 'Mackinac Island Lilac Festival',
                'category_index' => 3,
                'description' => '<p>Celebrate the arrival of spring on car-free Mackinac Island during the 10-day Lilac Festival. Enjoy lilac walking tours, horse-drawn carriage rides, local food tastings, and the famous Lilac Festival Grand Parade.</p><p>Experience the island\'s historic charm covered in beautiful, sweet-smelling lilac blossoms.</p>',
                'short_description' => 'Ten days of parades, walks, and carriage tours celebrating Mackinac\'s historic lilac blossoms.',
                'start_date' => now()->addDays(15)->format('Y-m-d H:i:s'),
                'end_date' => now()->addDays(17)->format('Y-m-d H:i:s'),
                'venue_name' => 'Mackinac Island Tourism Bureau',
                'address' => '7274 Main St',
                'city' => 'Mackinac Island',
                'phone' => '906-847-3783',
                'website' => 'https://www.mackinacisland.org',
                'featured_image' => 'https://images.unsplash.com/photo-1557429287-b2e26467fc2b?w=800&auto=format&fit=crop',
                'map_iframe' => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m13!1d2777.6253457199144!2d-84.61905872350811!3d45.85049387108253!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4d35f29910c2c311%3A0xe54ef937a09cbf2a!2sMackinac%20Island%20Tourism%20Bureau!5e0!3m2!1sen!2sus!4v1700000000003!5m2!1sen!2sus" width="100%" height="350" style="border:0;" allowfullscreen="" loading="lazy"></iframe>',
                'faqs' => [
                    ['question' => 'How do I get to the island?', 'answer' => '<p>Mackinac Island is accessible by ferry from St. Ignace or Mackinaw City. Cars are not allowed, so you will get around by foot, bike, or carriage once you arrive.</p>']
                ]
            ],
            [
                'name' => 'Ann Arbor Street Art Fair',
                'category_index' => 4,
                'description' => '<p>The Ann Arbor Art Fair is a massive outdoor art event spanning 30 city blocks in downtown Ann Arbor. It brings together nearly 1,000 fine artists, live street performances, and local culinary booths.</p><p>As one of the largest and most prestigious art fairs in the country, it attracts visual artists and art collectors from across the globe.</p>',
                'short_description' => 'Explore 30 blocks of outdoor galleries featuring local and international fine artists.',
                'start_date' => now()->addDays(45)->format('Y-m-d H:i:s'),
                'end_date' => now()->addDays(47)->format('Y-m-d H:i:s'),
                'venue_name' => 'Downtown Ann Arbor',
                'address' => '300 S Main St',
                'city' => 'Ann Arbor',
                'phone' => '734-994-5260',
                'website' => 'https://www.theannarborartfair.com',
                'featured_image' => 'https://images.unsplash.com/photo-1543857778-c4a1a3e0b2eb?w=800&auto=format&fit=crop',
                'map_iframe' => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m13!1d2952.12781198539!2d-83.74900742355811!3d42.279010471192834!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x883cae384594c9ef%3A0x6b8bc228c2cbe01d!2sMain%20St%2C%20Ann%20Arbor%2C%20MI!5e0!3m2!1sen!2sus!4v1700000000004!5m2!1sen!2sus" width="100%" height="350" style="border:0;" allowfullscreen="" loading="lazy"></iframe>',
                'faqs' => [
                    ['question' => 'Are there kids\' activities?', 'answer' => '<p>Yes! There are dedicated creative zones for children to try pottery, painting, and crafting with professional guidance.</p>']
                ]
            ],
            [
                'name' => 'Michigan Brewers Guild Summer Beer Festival',
                'category_index' => 5,
                'description' => '<p>Sample hundreds of unique Michigan craft beers from over 150 local breweries at Riverside Park in Ypsilanti. Enjoy live music, delicious food from local vendors, and a vibrant community atmosphere.</p><p>This event is a true celebration of Michigan\'s position as one of the top craft beer states in the country.</p>',
                'short_description' => 'Sample over 1,000 craft beers from 150+ Michigan breweries along the Huron River.',
                'start_date' => now()->subDays(10)->format('Y-m-d H:i:s'),
                'end_date' => now()->subDays(8)->format('Y-m-d H:i:s'),
                'venue_name' => 'Riverside Park',
                'address' => '2 E Cross St',
                'city' => 'Ypsilanti',
                'phone' => '517-327-5001',
                'website' => 'https://www.mibeer.com',
                'featured_image' => 'https://images.unsplash.com/photo-1535958636474-b021ee887b13?w=800&auto=format&fit=crop',
                'map_iframe' => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m13!1d2953.513364964667!2d-83.61338872355977!3d42.24949517119213!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x883caf9f225ebf6b%3A0xcb1bbf6e568f6bf!2sRiverside%20Park!5e0!3m2!1sen!2sus!4v1700000000005!5m2!1sen!2sus" width="100%" height="350" style="border:0;" allowfullscreen="" loading="lazy"></iframe>',
                'faqs' => [
                    ['question' => 'Are tickets refundable?', 'answer' => '<p>No, tickets are non-refundable and the festival takes place rain or shine. Be sure to check the weather and dress accordingly!</p>'],
                    ['question' => 'Is this a 21+ only event?', 'answer' => '<p>Yes, all attendees must be 21 or older with a valid ID to enter the festival grounds.</p>']
                ]
            ],
        ];

        // 3. Create Events & FAQs
        foreach ($events as $eventData) {
            $cat = $categories[$eventData['category_index']];
            
            $event = Event::create([
                'event_category_id' => $cat->id,
                'name' => $eventData['name'],
                'slug' => Str::slug($eventData['name']),
                'description' => $eventData['description'],
                'short_description' => $eventData['short_description'],
                'start_date' => $eventData['start_date'],
                'end_date' => $eventData['end_date'],
                'venue_name' => $eventData['venue_name'],
                'address' => $eventData['address'],
                'city' => $eventData['city'],
                'state' => 'MI',
                'phone' => $eventData['phone'],
                'website' => $eventData['website'],
                'featured_image' => $eventData['featured_image'],
                'map_iframe' => $eventData['map_iframe'],
                'status' => 1,
                'is_featured' => 1
            ]);

            // Create SEO
            $event->seo()->create([
                'meta_title' => $eventData['name'] . ' | Michigan Explorer',
                'meta_description' => $eventData['short_description'],
                'og_title' => $eventData['name'],
                'og_description' => $eventData['short_description']
            ]);

            // Create FAQs
            foreach ($eventData['faqs'] as $faq) {
                EventFaq::create([
                    'event_id' => $event->id,
                    'question' => $faq['question'],
                    'answer' => $faq['answer'],
                    'sort_order' => 0
                ]);
            }
        }
    }
}
