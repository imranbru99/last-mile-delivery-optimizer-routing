<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class DemoResetCommand extends Command
{
    protected $signature = 'demo:reset {--fresh : Drop all tables before reseeding}';

    protected $description = 'Reset demo data (used by Super Admin and hourly scheduler)';

    public function handle(): int
    {
        if ($this->option('fresh')) {
            $this->call('migrate:fresh', ['--seed' => true, '--force' => true]);
        } else {
            $this->call('db:seed', ['--force' => true]);
        }

        $this->info('Demo data reset complete.');

        return self::SUCCESS;
    }
}
