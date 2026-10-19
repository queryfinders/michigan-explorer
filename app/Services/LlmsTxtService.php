<?php

namespace App\Services;

use App\Models\Hotel;
use App\Models\Restaurant;
use App\Models\Attraction;
use App\Models\Event;
use App\Models\Blog;
use App\Models\Page;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class LlmsTxtService
{
    /**
     * Generate the concise llms.txt content (per https://llmstxt.org/ standard).
     */
    public function generateLlmsTxt(): string
    {
        return Cache::rememberForever('llms.txt', function () {
            $siteUrl = url('/');
            $settings = Setting::pluck('value', 'key');
            $siteName = $settings['site_name'] ?? 'Michigan Explorer';

            $content = "# {$siteName}\n\n";
            $content .= "> {$siteName} is the premier curated guide to luxury travel, premier hotels, fine dining, scenic attractions, and vibrant events across the state of Michigan.\n\n";

            // Core Navigation
            $content .= "## Core Pages\n\n";
            $content .= "- [Home]({$siteUrl}): Main travel portal with search, featured stays, upcoming events, and destination highlights.\n";
            $content .= "- [Hotels](" . route('web.hotels.index') . "): Handpicked luxury resorts, boutique stays, and premier accommodations in Michigan.\n";
            $content .= "- [Restaurants](" . route('web.restaurants.index') . "): Curated dining guide covering fine dining, farm-to-table cuisine, and iconic eateries.\n";
            $content .= "- [Attractions](" . route('web.attractions.index') . "): Natural wonders, state parks, historical lighthouses, and must-see destinations.\n";
            $content .= "- [Events](" . route('web.events.index') . "): Festivals, conferences, cultural celebrations, and seasonal happenings in Michigan.\n";
            $content .= "- [Travel Guides](" . route('web.blogs.index') . "): In-depth itineraries, seasonal guides, and local travel insights.\n";
            $content .= "- [Contact Us](" . route('web.contact') . "): Get in touch with the Michigan Explorer team for recommendations and partnership inquiries.\n\n";

            // Featured Hotels
            $hotels = Hotel::where('status', 1)->with('category')->take(15)->get();
            if ($hotels->count() > 0) {
                $content .= "## Featured Hotels & Accommodations\n\n";
                foreach ($hotels as $hotel) {
                    $url = route('web.hotels.show', $hotel->slug);
                    $meta = [];
                    if (!empty($hotel->city)) $meta[] = $hotel->city;
                    if (!empty($hotel->category?->name)) $meta[] = $hotel->category->name;
                    if (!empty($hotel->starting_price)) $meta[] = "From \${$hotel->starting_price}/night";
                    $metaStr = count($meta) ? ' (' . implode(', ', $meta) . ')' : '';
                    $desc = $this->cleanHtmlToMarkdown($hotel->description ?? '');
                    $shortDesc = mb_strimwidth($desc, 0, 140, '...');
                    $content .= "- [{$hotel->name}]({$url}){$metaStr}: {$shortDesc}\n";
                }
                $content .= "\n";
            }

            // Top Restaurants
            $restaurants = Restaurant::where('status', 1)->with('category')->take(15)->get();
            if ($restaurants->count() > 0) {
                $content .= "## Top Dining & Restaurants\n\n";
                foreach ($restaurants as $restaurant) {
                    $url = route('web.restaurants.show', $restaurant->slug);
                    $meta = [];
                    if (!empty($restaurant->city)) $meta[] = $restaurant->city;
                    if (!empty($restaurant->cuisine)) $meta[] = $restaurant->cuisine;
                    if (!empty($restaurant->price_range)) $meta[] = $restaurant->price_range;
                    $metaStr = count($meta) ? ' (' . implode(', ', $meta) . ')' : '';
                    $desc = $this->cleanHtmlToMarkdown($restaurant->description ?? '');
                    $shortDesc = mb_strimwidth($desc, 0, 140, '...');
                    $content .= "- [{$restaurant->name}]({$url}){$metaStr}: {$shortDesc}\n";
                }
                $content .= "\n";
            }

            // Must-Visit Attractions
            $attractions = Attraction::where('status', 1)->with('category')->take(15)->get();
            if ($attractions->count() > 0) {
                $content .= "## Key Attractions & Sights\n\n";
                foreach ($attractions as $attraction) {
                    $url = route('web.attractions.show', $attraction->slug);
                    $meta = [];
                    if (!empty($attraction->city)) $meta[] = $attraction->city;
                    if (!empty($attraction->category?->name)) $meta[] = $attraction->category->name;
                    $metaStr = count($meta) ? ' (' . implode(', ', $meta) . ')' : '';
                    $desc = $this->cleanHtmlToMarkdown($attraction->description ?? '');
                    $shortDesc = mb_strimwidth($desc, 0, 140, '...');
                    $content .= "- [{$attraction->name}]({$url}){$metaStr}: {$shortDesc}\n";
                }
                $content .= "\n";
            }

            // Upcoming Events
            $events = Event::where('status', 1)->with('category')->take(10)->get();
            if ($events->count() > 0) {
                $content .= "## Events & Festivals\n\n";
                foreach ($events as $event) {
                    $url = route('web.events.show', $event->slug);
                    $meta = [];
                    if (!empty($event->city)) $meta[] = $event->city;
                    if (!empty($event->venue_name)) $meta[] = $event->venue_name;
                    $metaStr = count($meta) ? ' (' . implode(', ', $meta) . ')' : '';
                    $desc = $this->cleanHtmlToMarkdown($event->description ?? '');
                    $shortDesc = mb_strimwidth($desc, 0, 140, '...');
                    $content .= "- [{$event->name}]({$url}){$metaStr}: {$shortDesc}\n";
                }
                $content .= "\n";
            }

            // Travel Guides
            $blogs = Blog::where('status', 'published')->with('category')->take(10)->get();
            if ($blogs->count() > 0) {
                $content .= "## Curated Travel Guides\n\n";
                foreach ($blogs as $blog) {
                    $url = route('web.blogs.show', $blog->slug);
                    $desc = $this->cleanHtmlToMarkdown($blog->excerpt ?? $blog->content ?? '');
                    $shortDesc = mb_strimwidth($desc, 0, 140, '...');
                    $content .= "- [{$blog->title}]({$url}): {$shortDesc}\n";
                }
                $content .= "\n";
            }

            // Link to Full Documentation
            $content .= "## Full Knowledge Base\n\n";
            $content .= "- [Full Michigan Explorer Knowledge Base](" . route('web.llms-full') . "): Comprehensive plain-text documentation including full listing details, amenities, FAQs, and guides for LLMs and AI search engines.\n";

            return $content;
        });
    }

    /**
     * Generate the complete llms-full.txt content.
     */
    public function generateLlmsFullTxt(): string
    {
        return Cache::rememberForever('llms-full.txt', function () {
            $siteUrl = url('/');
            $settings = Setting::pluck('value', 'key');
            $siteName = $settings['site_name'] ?? 'Michigan Explorer';

            $content = "# {$siteName} — Full Knowledge Base\n\n";
            $content .= "Website: {$siteUrl}\n";
            $content .= "Description: Complete directory of luxury hotels, top dining, natural attractions, events, and travel itineraries across Michigan.\n\n";
            $content .= "---\n\n";

            // Contact & Organization
            $content .= "# Organization Details\n\n";
            $content .= "- Name: {$siteName}\n";
            if (!empty($settings['contact_email'])) $content .= "- Email: {$settings['contact_email']}\n";
            if (!empty($settings['contact_phone'])) $content .= "- Phone: {$settings['contact_phone']}\n";
            if (!empty($settings['contact_headquarters'])) {
                $cleanAddress = str_replace(["\r\n", "\n"], ", ", trim($settings['contact_headquarters']));
                $content .= "- Headquarters: {$cleanAddress}\n";
            }
            $content .= "- Contact URL: " . route('web.contact') . "\n\n";
            $content .= "---\n\n";

            // Detailed Hotels
            $hotels = Hotel::where('status', 1)->with(['category', 'amenities', 'faqs', 'bookingFeatures'])->get();
            if ($hotels->count() > 0) {
                $content .= "# Hotels & Luxury Accommodations\n\n";
                foreach ($hotels as $hotel) {
                    $content .= "## {$hotel->name}\n\n";
                    if ($hotel->category) $content .= "**Category:** {$hotel->category->name}\n\n";
                    if ($hotel->city) $content .= "**Location:** {$hotel->city}, Michigan\n\n";
                    if ($hotel->starting_price) $content .= "**Starting Price:** \${$hotel->starting_price} / night\n\n";
                    if ($hotel->address) $content .= "**Address:** {$hotel->address}\n\n";
                    if ($hotel->phone) $content .= "**Phone:** {$hotel->phone}\n\n";
                    if ($hotel->website) $content .= "**Website:** {$hotel->website}\n\n";

                    if (!empty($hotel->description)) {
                        $content .= "### Overview\n\n" . $this->cleanHtmlToMarkdown($hotel->description) . "\n\n";
                    }

                    if ($hotel->amenities->count() > 0) {
                        $content .= "### Amenities\n\n";
                        foreach ($hotel->amenities as $amenity) {
                            $content .= "- {$amenity->name}\n";
                        }
                        $content .= "\n";
                    }

                    if ($hotel->bookingFeatures->count() > 0) {
                        $content .= "### Key Features\n\n";
                        foreach ($hotel->bookingFeatures as $feature) {
                            $content .= "- {$feature->name}\n";
                        }
                        $content .= "\n";
                    }

                    if ($hotel->faqs->count() > 0) {
                        $content .= "### Frequently Asked Questions\n\n";
                        foreach ($hotel->faqs as $faq) {
                            $content .= "**Q: {$faq->question}**\n";
                            $content .= "A: " . $this->cleanHtmlToMarkdown($faq->answer) . "\n\n";
                        }
                    }

                    $content .= "URL: " . route('web.hotels.show', $hotel->slug) . "\n\n";
                    $content .= "---\n\n";
                }
            }

            // Detailed Restaurants
            $restaurants = Restaurant::where('status', 1)->with(['category', 'cuisines', 'features', 'faqs'])->get();
            if ($restaurants->count() > 0) {
                $content .= "# Dining & Restaurants\n\n";
                foreach ($restaurants as $restaurant) {
                    $content .= "## {$restaurant->name}\n\n";
                    if ($restaurant->category) $content .= "**Category:** {$restaurant->category->name}\n\n";
                    if ($restaurant->city) $content .= "**Location:** {$restaurant->city}, Michigan\n\n";
                    if ($restaurant->cuisine) $content .= "**Cuisine:** {$restaurant->cuisine}\n\n";
                    if ($restaurant->price_range) $content .= "**Price Range:** {$restaurant->price_range}\n\n";
                    if ($restaurant->address) $content .= "**Address:** {$restaurant->address}\n\n";
                    if ($restaurant->phone) $content .= "**Phone:** {$restaurant->phone}\n\n";

                    if (!empty($restaurant->description)) {
                        $content .= "### Overview\n\n" . $this->cleanHtmlToMarkdown($restaurant->description) . "\n\n";
                    }

                    if ($restaurant->features->count() > 0) {
                        $content .= "### Highlights & Features\n\n";
                        foreach ($restaurant->features as $feature) {
                            $content .= "- {$feature->name}\n";
                        }
                        $content .= "\n";
                    }

                    if ($restaurant->faqs->count() > 0) {
                        $content .= "### Frequently Asked Questions\n\n";
                        foreach ($restaurant->faqs as $faq) {
                            $content .= "**Q: {$faq->question}**\n";
                            $content .= "A: " . $this->cleanHtmlToMarkdown($faq->answer) . "\n\n";
                        }
                    }

                    $content .= "URL: " . route('web.restaurants.show', $restaurant->slug) . "\n\n";
                    $content .= "---\n\n";
                }
            }

            // Detailed Attractions
            $attractions = Attraction::where('status', 1)->with(['category', 'faqs'])->get();
            if ($attractions->count() > 0) {
                $content .= "# Attractions & Destinations\n\n";
                foreach ($attractions as $attraction) {
                    $content .= "## {$attraction->name}\n\n";
                    if ($attraction->category) $content .= "**Category:** {$attraction->category->name}\n\n";
                    if ($attraction->city) $content .= "**Location:** {$attraction->city}, Michigan\n\n";
                    if ($attraction->admission_fee) $content .= "**Admission:** {$attraction->admission_fee}\n\n";

                    if (!empty($attraction->description)) {
                        $content .= "### Overview\n\n" . $this->cleanHtmlToMarkdown($attraction->description) . "\n\n";
                    }

                    if ($attraction->faqs->count() > 0) {
                        $content .= "### Frequently Asked Questions\n\n";
                        foreach ($attraction->faqs as $faq) {
                            $content .= "**Q: {$faq->question}**\n";
                            $content .= "A: " . $this->cleanHtmlToMarkdown($faq->answer) . "\n\n";
                        }
                    }

                    $content .= "URL: " . route('web.attractions.show', $attraction->slug) . "\n\n";
                    $content .= "---\n\n";
                }
            }

            // Detailed Events
            $events = Event::where('status', 1)->with(['category', 'faqs'])->get();
            if ($events->count() > 0) {
                $content .= "# Events & Festivals\n\n";
                foreach ($events as $event) {
                    $content .= "## {$event->name}\n\n";
                    if ($event->category) $content .= "**Category:** {$event->category->name}\n\n";
                    if ($event->venue_name) $content .= "**Venue:** {$event->venue_name}\n\n";
                    if ($event->city) $content .= "**City:** {$event->city}, Michigan\n\n";
                    if ($event->start_date) $content .= "**Dates:** " . $event->start_date . ($event->end_date ? ' to ' . $event->end_date : '') . "\n\n";

                    if (!empty($event->description)) {
                        $content .= "### Overview\n\n" . $this->cleanHtmlToMarkdown($event->description) . "\n\n";
                    }

                    if ($event->faqs->count() > 0) {
                        $content .= "### Frequently Asked Questions\n\n";
                        foreach ($event->faqs as $faq) {
                            $content .= "**Q: {$faq->question}**\n";
                            $content .= "A: " . $this->cleanHtmlToMarkdown($faq->answer) . "\n\n";
                        }
                    }

                    $content .= "URL: " . route('web.events.show', $event->slug) . "\n\n";
                    $content .= "---\n\n";
                }
            }

            // Detailed Blogs
            $blogs = Blog::where('status', 'published')->with(['category', 'author', 'tags', 'faqs'])->get();
            if ($blogs->count() > 0) {
                $content .= "# Travel Guides & Articles\n\n";
                foreach ($blogs as $blog) {
                    $content .= "## {$blog->title}\n\n";
                    if ($blog->author) $content .= "**Author:** {$blog->author->name}\n\n";
                    if ($blog->category) $content .= "**Category:** {$blog->category->name}\n\n";
                    if ($blog->published_at) $content .= "**Published Date:** {$blog->published_at}\n\n";

                    if (!empty($blog->content)) {
                        $content .= "### Content\n\n" . $this->cleanHtmlToMarkdown($blog->content) . "\n\n";
                    }

                    if ($blog->faqs->count() > 0) {
                        $content .= "### Frequently Asked Questions\n\n";
                        foreach ($blog->faqs as $faq) {
                            $content .= "**Q: {$faq->question}**\n";
                            $content .= "A: " . $this->cleanHtmlToMarkdown($faq->answer) . "\n\n";
                        }
                    }

                    $content .= "URL: " . route('web.blogs.show', $blog->slug) . "\n\n";
                    $content .= "---\n\n";
                }
            }

            return $content;
        });
    }

    /**
     * Converts basic HTML to Markdown/plain text.
     */
    private function cleanHtmlToMarkdown(?string $html): string
    {
        if (empty($html)) return '';
        
        // Remove style and script tags
        $html = preg_replace('/<style\b[^>]*>(.*?)<\/style>/is', '', $html);
        $html = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $html);

        // Convert lists
        $html = preg_replace('/<li>(.*?)<\/li>/is', "- $1\n", $html);
        $html = preg_replace('/<\/?(ul|ol)[^>]*>/is', "\n", $html);

        // Convert headings
        $html = preg_replace('/<h1[^>]*>(.*?)<\/h1>/is', "# $1\n\n", $html);
        $html = preg_replace('/<h2[^>]*>(.*?)<\/h2>/is', "## $1\n\n", $html);
        $html = preg_replace('/<h3[^>]*>(.*?)<\/h3>/is', "### $1\n\n", $html);
        
        // Convert paragraphs and line breaks
        $html = preg_replace('/<\/p>/i', "\n\n", $html);
        $html = preg_replace('/<br[^>]*>/i', "\n", $html);

        // Strip remaining HTML tags
        $text = strip_tags($html);

        // Clean up excessive whitespace
        $text = preg_replace("/\n\s+\n/", "\n\n", $text);
        $text = preg_replace("/\n{3,}/", "\n\n", $text);

        return trim(html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8'));
    }
}
