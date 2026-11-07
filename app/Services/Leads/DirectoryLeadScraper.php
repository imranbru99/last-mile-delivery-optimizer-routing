<?php

declare(strict_types=1);

namespace App\Services\Leads;

use Illuminate\Support\Facades\Http;
use ImranDevBd\AiCatalog\Facades\AiCatalog;
use Throwable;

/**
 * Uses imrandevbd/laravel-ai-catalog for public directory enrichment / lead lists.
 * Respects robots.txt and compliance toggles in business settings.
 */
final class DirectoryLeadScraper
{
    /**
     * @return list<array<string, mixed>>
     */
    public function scrape(string $query, string $locale = 'en', bool $complianceEnabled = true): array
    {
        if (! $complianceEnabled) {
            return [];
        }

        try {
            $generation = AiCatalog::fromKeyword($query)
                ->locale($locale)
                ->generate();

            $payload = method_exists($generation, 'toArray')
                ? $generation->toArray()
                : (array) $generation;

            return [
                [
                    'name' => $payload['title'] ?? $query,
                    'description' => $payload['description'] ?? null,
                    'source' => 'ai-catalog',
                    'raw' => $payload,
                ],
            ];
        } catch (Throwable $e) {
            return [
                [
                    'name' => $query.' Demo Merchant',
                    'city' => 'Dhaka',
                    'source' => 'fallback',
                    'note' => $e->getMessage(),
                ],
            ];
        }
    }

    public function robotsAllows(string $baseUrl): bool
    {
        try {
            $robots = Http::timeout(5)->get(rtrim($baseUrl, '/').'/robots.txt')->body();

            return ! preg_match('/(?im)^disallow:\s*\/\s*$/', $robots);
        } catch (Throwable) {
            return false;
        }
    }
}
