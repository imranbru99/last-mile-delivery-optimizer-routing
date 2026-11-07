<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Lead;
use App\Services\Leads\DirectoryLeadScraper;
use App\Support\Tenancy\TenantContext;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class LeadGenerationJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        public readonly int $businessId,
        public readonly string $query,
        public readonly string $locale = 'en',
    ) {}

    public function handle(DirectoryLeadScraper $scraper, TenantContext $tenant): void
    {
        $tenant->set(\App\Models\Business::query()->findOrFail($this->businessId));

        foreach ($scraper->scrape($this->query, $this->locale) as $row) {
            Lead::query()->create([
                'business_id' => $this->businessId,
                'name' => (string) ($row['name'] ?? $this->query),
                'email' => $row['email'] ?? null,
                'phone' => $row['phone'] ?? null,
                'source' => (string) ($row['source'] ?? 'scraper'),
                'status' => 'new',
                'meta' => $row,
            ]);
        }
    }
}
