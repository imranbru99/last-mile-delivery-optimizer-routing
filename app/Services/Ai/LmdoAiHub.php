<?php

declare(strict_types=1);

namespace App\Services\Ai;

use ImranDevBd\AiHub\Facades\AIHub;
use Throwable;

/**
 * Application-facing wrapper around imrandevbd/laravel-ai-hub.
 */
final class LmdoAiHub
{
    public function explainRoute(array $routeMeta): string
    {
        try {
            $response = AIHub::system('You are a logistics dispatcher copilot. Explain route decisions briefly.')
                ->prompt('Explain this optimized route JSON: '.json_encode($routeMeta))
                ->send();

            return (string) ($response->content ?? $response);
        } catch (Throwable $e) {
            return 'AI explanation unavailable: '.$e->getMessage();
        }
    }

    public function cleanAddress(string $raw, string $locale = 'en'): string
    {
        try {
            $response = AIHub::system('Normalize and clean delivery addresses. Return only the cleaned address.')
                ->prompt("Locale: {$locale}\nAddress: {$raw}")
                ->send();

            return trim((string) ($response->content ?? $raw));
        } catch (Throwable) {
            return trim(preg_replace('/\s+/', ' ', $raw) ?? $raw);
        }
    }

    public function forecastDemand(array $history): array
    {
        try {
            $response = AIHub::system('Forecast next 7 days order demand. Reply with JSON array of {date, orders}.')
                ->prompt((string) json_encode($history))
                ->send();

            $content = (string) ($response->content ?? '[]');
            $decoded = json_decode($content, true);

            return is_array($decoded) ? $decoded : [];
        } catch (Throwable) {
            return [];
        }
    }

    /**
     * @param  array<string, mixed>  $context
     */
    public function dispatcherChat(string $message, array $context = []): string
    {
        try {
            $response = AIHub::system(
                'You are an AI dispatcher copilot for last-mile delivery. Be concise, actionable, and safety-aware.'
            )
                ->prompt("Operational context JSON:\n".json_encode($context)."\n\nDispatcher question:\n".$message)
                ->send();

            return trim((string) ($response->content ?? $response));
        } catch (Throwable $e) {
            return 'Copilot unavailable: '.$e->getMessage();
        }
    }
}
