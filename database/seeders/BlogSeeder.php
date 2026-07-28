<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Author;
use App\Models\BlogCategory;
use App\Models\BlogTag;
use App\Models\Blog;
use App\Models\BlogTagMap;
use Illuminate\Support\Str;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Authors
        $authors = [
            ['name' => 'Sarah Jenkins', 'email' => 'sarah@example.com', 'bio' => 'Travel enthusiast and local Michigan guide with 10 years of experience.', 'avatar' => 'storage/demo/author1.jpg'],
            ['name' => 'Michael Ch', 'email' => 'michael@example.com', 'bio' => 'Food critic and weekend adventurer exploring the best of the Midwest.', 'avatar' => 'storage/demo/author2.jpg'],
        ];

        $authorIds = [];
        foreach ($authors as $authorData) {
            $author = Author::firstOrCreate(['email' => $authorData['email']], $authorData);
            $authorIds[] = $author->id;
        }

        // 2. Create Categories
        $categories = [
            ['name' => 'Travel Guides', 'icon' => 'fas fa-map-marked-alt'],
            ['name' => 'Local Tips', 'icon' => 'fas fa-lightbulb'],
            ['name' => 'Weekend Itineraries', 'icon' => 'fas fa-calendar-alt'],
            ['name' => 'Seasonal Activities', 'icon' => 'fas fa-snowflake'],
            ['name' => 'Restaurant Reviews', 'icon' => 'fas fa-utensils'],
            ['name' => 'Road Trips', 'icon' => 'fas fa-car'],
            ['name' => 'Hidden Gems', 'icon' => 'fas fa-gem']
        ];
        $categoryIds = [];
        foreach ($categories as $catData) {
            $cat = BlogCategory::firstOrCreate(
                ['slug' => Str::slug($catData['name'])],
                ['name' => $catData['name'], 'icon' => $catData['icon']]
            );
            $categoryIds[] = $cat->id;
        }

        // 3. Create Tags
        $tags = ['Family Friendly', 'Outdoors', 'Budget', 'Luxury', 'Romance', 'Foodie', 'History', 'Nature', 'Camping', 'Wine'];
        $tagIds = [];
        foreach ($tags as $tagName) {
            $tag = BlogTag::firstOrCreate(
                ['slug' => Str::slug($tagName)],
                ['name' => $tagName]
            );
            $tagIds[] = $tag->id;
        }

        // 4. Create Blogs
        $blogs = [
            [
                'title' => 'The Ultimate 3-Day Traverse City Itinerary',
                'excerpt' => 'Discover the best wineries, beaches, and restaurants in Traverse City for a perfect weekend getaway.',
                'content' => '<h2>Day 1: Arrival and Downtown</h2><p>Start your trip by exploring the vibrant downtown area...</p><h3>Wineries to Visit</h3><p>Don\'t miss out on the incredible local wine scene.</p><blockquote>The views from the peninsula are simply breathtaking.</blockquote>',
                'status' => 'published',
                'is_featured' => 1,
                'featured_image' => 'storage/demo/michigan_sleeping_bear_1783683642640.png',
                'views' => rand(100, 5000),
                'blog_category_id' => $categoryIds[2], // Weekend Itineraries
                'author_id' => $authorIds[0]
            ],
            [
                'title' => 'A Foodie\'s Guide to Detroit: Top 10 Restaurants',
                'excerpt' => 'From classic Coney Islands to upscale dining, explore the culinary delights of Motor City.',
                'content' => '<h2>The Classics</h2><p>You can\'t visit Detroit without trying a Coney dog. The longstanding rivalry between Lafayette and American Coney Island is legendary.</p><img src="https://images.unsplash.com/photo-1544025162-81111420d4d7?auto=format&fit=crop&w=1200" alt="Hot Dog" class="img-fluid rounded-4 my-4 shadow-sm"><h2>Fine Dining</h2><p>For a special night out, we recommend these top spots in downtown Detroit. The culinary scene has exploded over the last decade.</p><img src="https://images.unsplash.com/photo-1414235077428-338989a2e8c0?auto=format&fit=crop&w=1200" alt="Fine Dining" class="img-fluid rounded-4 my-4 shadow-sm"><blockquote>Detroit is completely redefining what it means to be a culinary destination in the Midwest.</blockquote>',
                'status' => 'published',
                'is_featured' => 0,
                'featured_image' => 'storage/demo/michigan_hotel_lobby_1783683621508.png',
                'views' => rand(100, 5000),
                'blog_category_id' => $categoryIds[4], // Restaurant Reviews
                'author_id' => $authorIds[1]
            ],
            [
                'title' => '10 Hidden Gems in the Upper Peninsula',
                'excerpt' => 'Skip the crowds and explore these secret waterfalls, pristine beaches, and quiet trails in Michigan\'s U.P.',
                'content' => '<p>The Upper Peninsula holds some of Michigan\'s best secrets...</p><h3>1. Kitch-iti-kipi</h3><p>This natural spring is stunning...</p>',
                'status' => 'published',
                'is_featured' => 0,
                'featured_image' => 'storage/demo/upper_peninsula_hidden_gems_1783774413992.png',
                'views' => rand(100, 5000),
                'blog_category_id' => $categoryIds[6], // Hidden Gems
                'author_id' => $authorIds[0]
            ],
            [
                'title' => 'Best Winter Activities in Michigan',
                'excerpt' => 'Embrace the cold with our guide to skiing, snowmobiling, and ice fishing in the Great Lakes state.',
                'content' => '<p>Winter in Michigan is magical...</p><p>From the ski resorts of Boyne Mountain to the ice caves of Munising...</p>',
                'status' => 'published',
                'is_featured' => 0,
                'featured_image' => 'storage/demo/michigan_lighthouse_1783683652511.png',
                'views' => rand(100, 5000),
                'blog_category_id' => $categoryIds[3], // Seasonal
                'author_id' => $authorIds[0]
            ],
            [
                'title' => 'The Best Fall Color Drives in Michigan',
                'excerpt' => 'Hit the road this autumn and experience the spectacular colors of Michigan\'s forests.',
                'content' => '<h2>M-22 Scenic Drive</h2><p>Voted one of the most scenic autumn drives in America...</p><h2>Tunnel of Trees</h2><p>A spectacular canopy of color along the Lake Michigan shoreline.</p>',
                'status' => 'published',
                'is_featured' => 0,
                'featured_image' => 'storage/demo/fall_color_drives_michigan_1783774431556.png',
                'views' => rand(100, 5000),
                'blog_category_id' => $categoryIds[5], // Road Trips
                'author_id' => $authorIds[1]
            ]
        ];

        foreach ($blogs as $blogData) {
            $blog = Blog::firstOrCreate(
                ['slug' => Str::slug($blogData['title'])],
                $blogData
            );
            
            // Assign random tags
            $randomTagIds = array_rand(array_flip($tagIds), rand(2, 4));
            foreach ($randomTagIds as $tId) {
                BlogTagMap::firstOrCreate([
                    'blog_id' => $blog->id,
                    'blog_tag_id' => $tId
                ]);
            }
        }
    }
}
