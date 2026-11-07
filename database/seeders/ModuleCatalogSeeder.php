<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Support\Modules\ModuleManager;
use Illuminate\Database\Seeder;

class ModuleCatalogSeeder extends Seeder
{
    public function run(): void
    {
        $count = app(ModuleManager::class)->syncCatalogToDatabase();
        $this->command?->info("Synced {$count} modules into catalog.");
    }
}
