<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Restaurant;
use App\Models\RestaurantCategory;
use App\Models\RestaurantCuisine;
use App\Models\RestaurantFeature;

class RestaurantSeeder extends Seeder
{
    public function run(): void
    {
        // ─── 1. CATEGORIES ──────────────────────────────────────────────────
        $categories = [
            ['name' => 'Fine Dining',  'slug' => 'fine-dining',   'is_featured' => 1, 'status' => 1, 'icon' => 'fas fa-wine-glass-alt'],
            ['name' => 'Seafood',      'slug' => 'seafood',        'is_featured' => 1, 'status' => 1, 'icon' => 'fas fa-fish'],
            ['name' => 'Steakhouse',   'slug' => 'steakhouse',     'is_featured' => 1, 'status' => 1, 'icon' => 'fas fa-drumstick-bite'],
        ];
        $catIds = [];
        foreach ($categories as $c) {
            $cat = RestaurantCategory::firstOrCreate(['slug' => $c['slug']], $c);
            $catIds[$c['slug']] = $cat->id;
        }

        // ─── 2. CUISINES ─────────────────────────────────────────────────────
        $cuisineData = [
            ['name' => 'American',     'slug' => 'american'],
            ['name' => 'Seafood',      'slug' => 'seafood-cuisine'],
            ['name' => 'Italian',      'slug' => 'italian'],
            ['name' => 'Steakhouse',   'slug' => 'steakhouse-cuisine'],
            ['name' => 'Mediterranean','slug' => 'mediterranean'],
            ['name' => 'Farm to Table','slug' => 'farm-to-table'],
        ];
        $cuisineIds = [];
        foreach ($cuisineData as $c) {
            $cuisine = RestaurantCuisine::firstOrCreate(['slug' => $c['slug']], array_merge($c, ['status' => 1, 'sort_order' => 0]));
            $cuisineIds[$c['slug']] = $cuisine->id;
        }

        // ─── 3. FEATURES ─────────────────────────────────────────────────────
        $featureData = [
            ['name' => 'Outdoor Seating',  'slug' => 'outdoor-seating',  'icon_class' => 'fas fa-chair'],
            ['name' => 'Full Bar',         'slug' => 'full-bar',          'icon_class' => 'fas fa-wine-glass'],
            ['name' => 'Family Friendly',  'slug' => 'family-friendly',   'icon_class' => 'fas fa-child'],
            ['name' => 'Valet Parking',    'slug' => 'valet-parking',     'icon_class' => 'fas fa-parking'],
            ['name' => 'Waterfront View',  'slug' => 'waterfront-view',   'icon_class' => 'fas fa-water'],
            ['name' => 'Private Dining',   'slug' => 'private-dining',    'icon_class' => 'fas fa-door-closed'],
            ['name' => 'Live Music',       'slug' => 'live-music',        'icon_class' => 'fas fa-music'],
            ['name' => 'Takeout',          'slug' => 'takeout',           'icon_class' => 'fas fa-shopping-bag'],
        ];
        $featureIds = [];
        foreach ($featureData as $f) {
            $feature = RestaurantFeature::firstOrCreate(['slug' => $f['slug']], array_merge($f, ['status' => 1, 'sort_order' => 0]));
            $featureIds[$f['slug']] = $feature->id;
        }

        // ─── 4. RESTAURANTS ──────────────────────────────────────────────────
        $restaurants = [
            [
                'restaurant' => [
                    'restaurant_category_id' => $catIds['fine-dining'],
                    'name'              => 'Lakeside Prime Steakhouse',
                    'slug'              => 'lakeside-prime-steakhouse',
                    'description'       => 'Experience the finest waterfront dining in Traverse City. Our culinary team crafts exquisite dishes using locally sourced ingredients, perfectly paired with our award-winning wine selection. Whether you are here for a romantic dinner or a family gathering, our beautifully appointed dining room and exceptional service ensure a perfect evening.',
                    'short_description' => 'Fine waterfront dining with award-winning wines and locally sourced ingredients.',
                    'cuisine'           => 'American, Steakhouse',
                    'opening_hours'     => 'Mon-Thu: 11am-10pm | Fri-Sat: 11am-11:30pm | Sun: 10am-9pm',
                    'address'           => '123 Marina Drive',
                    'city'              => 'Traverse City',
                    'state'             => 'MI',
                    'zip'               => '49684',
                    'phone'             => '(231) 555-0110',
                    'email'             => 'reservations@lakesideprime.com',
                    'website'           => 'https://lakesideprime.example.com',
                    'featured_image'    => 'images/fine_dining_1783508270763.png',
                    'is_featured'       => 1,
                    'status'            => 1,
                    'meta_title'        => 'Lakeside Prime Steakhouse | Fine Dining Traverse City MI',
                    'meta_description'  => 'Waterfront fine dining in Traverse City. Premium steaks, fresh seafood, award-winning wines.',
                ],
                'cuisines'  => ['american', 'steakhouse-cuisine'],
                'features'  => ['outdoor-seating', 'full-bar', 'waterfront-view', 'valet-parking', 'private-dining'],
            ],
            [
                'restaurant' => [
                    'restaurant_category_id' => $catIds['seafood'],
                    'name'              => 'Harbor Catch Seafood Grill',
                    'slug'              => 'harbor-catch-seafood-grill',
                    'description'       => 'Fresh from Lake Michigan to your plate – Harbor Catch Seafood Grill brings you the best of Michigan\'s Great Lakes seafood. Our chefs prepare the day\'s catch in creative ways, from classic fish fry to elegant cedar-plank salmon. Enjoy stunning harbor views from our deck seating while savoring locally caught perch, walleye, and lake trout.',
                    'short_description' => 'Fresh Great Lakes seafood with stunning harbor views and a relaxed atmosphere.',
                    'cuisine'           => 'Seafood, American',
                    'opening_hours'     => 'Tue-Thu: 12pm-9pm | Fri-Sat: 11am-10pm | Sun: 11am-8pm | Mon: Closed',
                    'address'           => '450 Harbor View Blvd',
                    'city'              => 'Petoskey',
                    'state'             => 'MI',
                    'zip'               => '49770',
                    'phone'             => '(231) 555-0242',
                    'email'             => 'hello@harborcatch.com',
                    'website'           => 'https://harborcatch.example.com',
                    'featured_image'    => 'images/fine_dining_1783508270763.png',
                    'is_featured'       => 1,
                    'status'            => 1,
                    'meta_title'        => 'Harbor Catch Seafood Grill | Fresh Lake Michigan Seafood Petoskey',
                    'meta_description'  => 'Great Lakes seafood restaurant in Petoskey MI. Fresh perch, walleye, and lake trout with harbor views.',
                ],
                'cuisines'  => ['seafood-cuisine', 'american'],
                'features'  => ['outdoor-seating', 'waterfront-view', 'family-friendly', 'takeout', 'live-music'],
            ],
            [
                'restaurant' => [
                    'restaurant_category_id' => $catIds['fine-dining'],
                    'name'              => 'The Vineyard Table',
                    'slug'              => 'the-vineyard-table',
                    'description'       => 'Nestled among the rolling vineyards of Old Mission Peninsula, The Vineyard Table offers an immersive farm-to-table dining experience like no other in Michigan. Our seasonal menu features ingredients harvested directly from surrounding farms, paired with exceptional wines from neighboring Traverse City wineries. The intimate dining room and sweeping vineyard views create the perfect setting for a memorable meal.',
                    'short_description' => 'Farm-to-table dining on Old Mission Peninsula with local wines and vineyard views.',
                    'cuisine'           => 'Mediterranean, Farm to Table, Italian',
                    'opening_hours'     => 'Wed-Sun: 5pm-10pm | Mon-Tue: Closed',
                    'address'           => '789 Peninsula Drive',
                    'city'              => 'Old Mission',
                    'state'             => 'MI',
                    'zip'               => '49673',
                    'phone'             => '(231) 555-0379',
                    'email'             => 'dining@vineyardtable.com',
                    'website'           => 'https://vineyardtable.example.com',
                    'featured_image'    => 'images/fine_dining_1783508270763.png',
                    'is_featured'       => 0,
                    'status'            => 1,
                    'meta_title'        => 'The Vineyard Table | Farm-to-Table Dining Old Mission Peninsula MI',
                    'meta_description'  => 'Seasonal farm-to-table restaurant on Old Mission Peninsula. Local wines, Mediterranean-inspired menus, and vineyard views.',
                ],
                'cuisines'  => ['mediterranean', 'farm-to-table', 'italian'],
                'features'  => ['full-bar', 'private-dining', 'live-music', 'outdoor-seating'],
            ],
        ];

        foreach ($restaurants as $entry) {
            $restaurant = Restaurant::firstOrCreate(
                ['slug' => $entry['restaurant']['slug']],
                $entry['restaurant']
            );

            // Sync cuisines
            $cIds = array_filter(array_map(fn($s) => $cuisineIds[$s] ?? null, $entry['cuisines']));
            $restaurant->cuisines()->sync($cIds);

            // Sync features
            $fIds = array_filter(array_map(fn($s) => $featureIds[$s] ?? null, $entry['features']));
            $restaurant->features()->sync($fIds);
        }

        $this->command->info('✅ RestaurantSeeder: 3 restaurants, ' . count($categories) . ' categories, ' . count($cuisineData) . ' cuisines, ' . count($featureData) . ' features seeded.');
    }
}
