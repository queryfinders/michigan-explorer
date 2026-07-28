<?php

namespace App\Services;

class ReferrerAnalyzer
{
    /**
     * Map of categories to domain patterns, icons, and display names.
     */
    protected static array $config = [
        'Google' => [
            'name' => 'Google',
            'icon' => 'fa-brands fa-google text-primary',
            'patterns' => [
                '/^google\.com$/i',
                '/^www\.google\.com$/i',
                '/^google\.co\.[a-z]{2,3}$/i',
                '/^google\.[a-z]{2,3}$/i',
            ]
        ],
        'Facebook' => [
            'name' => 'Facebook',
            'icon' => 'fa-brands fa-facebook text-info',
            'patterns' => [
                '/^facebook\.com$/i',
                '/^www\.facebook\.com$/i',
                '/^m\.facebook\.com$/i',
                '/^l\.facebook\.com$/i',
            ]
        ],
        'Instagram' => [
            'name' => 'Instagram',
            'icon' => 'fa-brands fa-instagram text-danger',
            'patterns' => [
                '/^instagram\.com$/i',
                '/^l\.instagram\.com$/i',
            ]
        ],
        'WhatsApp' => [
            'name' => 'WhatsApp',
            'icon' => 'fa-brands fa-whatsapp text-success',
            'patterns' => [
                '/^web\.whatsapp\.com$/i',
                '/^api\.whatsapp\.com$/i',
                '/^whatsapp\.com$/i',
                '/^android-app:\/\/com\.whatsapp$/i',
            ]
        ],
        'LinkedIn' => [
            'name' => 'LinkedIn',
            'icon' => 'fa-brands fa-linkedin text-primary',
            'patterns' => [
                '/^linkedin\.com$/i',
                '/^www\.linkedin\.com$/i',
            ]
        ],
        'Direct' => [
            'name' => 'Direct',
            'icon' => 'fa-regular fa-compass text-secondary',
            'patterns' => [] // Matched explicitly when referer is empty
        ],
        'Other' => [
            'name' => 'Other',
            'icon' => 'fa-solid fa-share-nodes text-muted',
            'patterns' => [] // Fallback category
        ]
    ];

    /**
     * Get all categories with default config.
     */
    public static function getCategories(): array
    {
        return self::$config;
    }

    /**
     * Parse a referer URL or host into a category.
     */
    public static function parse(?string $referer): string
    {
        if (empty($referer)) {
            return 'Direct';
        }

        // Clean up referrer
        $referer = trim($referer);

        // Check if it's an android app intent (like WhatsApp)
        if (str_starts_with($referer, 'android-app://')) {
            foreach (self::$config as $category => $data) {
                foreach ($data['patterns'] as $pattern) {
                    if (preg_match($pattern, $referer)) {
                        return $category;
                    }
                }
            }
        }

        // Extract host
        $host = parse_url($referer, PHP_URL_HOST);
        if (empty($host)) {
            $host = $referer;
        }

        // Match host against pattern configurations
        foreach (self::$config as $category => $data) {
            foreach ($data['patterns'] as $pattern) {
                if (preg_match($pattern, $host)) {
                    return $category;
                }
            }
        }

        return 'Other';
    }

    /**
     * Analyze a collection of click logs and return counts sorted by count descending.
     */
    public static function analyze($clickLogs): array
    {
        // Initialize counts with 0 for all categories to ensure they stay visible
        $counts = [];
        foreach (self::$config as $category => $data) {
            $counts[$category] = [
                'name' => $data['name'],
                'icon' => $data['icon'],
                'count' => 0
            ];
        }

        // Aggregate counts from logs
        foreach ($clickLogs as $log) {
            $category = self::parse($log->referer);
            $counts[$category]['count']++;
        }

        // Sort descending by count, maintaining key names
        uasort($counts, function ($a, $b) {
            return $b['count'] <=> $a['count'];
        });

        return $counts;
    }
}
