<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class GeoIPResolver
{
    /**
     * Resolve IP address to country, state, and city.
     */
    public static function resolve(string $ip): array
    {
        // 1. Local / Private IP detection
        if (in_array($ip, ['127.0.0.1', '::1']) || str_starts_with($ip, '192.168.') || str_starts_with($ip, '10.') || str_starts_with($ip, '172.16.')) {
            return [
                'country_code' => 'US',
                'country_name' => 'Local Development',
                'state'        => 'Testing Environment',
                'city'         => 'Localhost'
            ];
        }

        // 2. Cache queries for 30 days to optimize performance and respect rate limits
        return Cache::remember('geoip_ip_v2_' . $ip, 60 * 24 * 30, function () use ($ip) {
            try {
                // Local XAMPP/Apache servers often block DNS resolution in web context.
                // We execute the HTTP request via PHP CLI which has full DNS permissions.
                $url = "http://ip-api.com/json/" . $ip . "?fields=status,message,country,countryCode,regionName,city";
                $output = shell_exec('P:\xampp83\php\php.exe -r "echo @file_get_contents(\'' . $url . '\');"');
                $data = json_decode($output, true);

                if ($data && isset($data['status']) && $data['status'] === 'success') {
                    return [
                        'country_code' => $data['countryCode'] ?: 'US',
                        'country_name' => $data['country'] ?: 'United States',
                        'state'        => $data['regionName'] ?: 'Michigan',
                        'city'         => $data['city'] ?: 'Traverse City'
                    ];
                }
                
                // If CLI fails, fall back to direct HTTP client
                $response = Http::timeout(2.0)->get($url);
                if ($response->successful() && $response->json('status') === 'success') {
                    return [
                        'country_code' => $response->json('countryCode') ?: 'US',
                        'country_name' => $response->json('country') ?: 'United States',
                        'state'        => $response->json('regionName') ?: 'Michigan',
                        'city'         => $response->json('city') ?: 'Traverse City'
                    ];
                }
            } catch (\Exception $e) {
                Log::warning("GeoIP resolution failed for {$ip}: " . $e->getMessage());
            }

            // Safe fallback defaults (used when offline/firewall blocks API requests)
            return [
                'country_code' => 'US',
                'country_name' => 'United States',
                'state'        => 'Michigan',
                'city'         => 'Traverse City'
            ];
        });
    }
}
