<?php

declare(strict_types=1);

namespace App\Modules\Contracts;

interface ModuleDefinition
{
    public function key(): string;

    /**
     * @return list<string>
     */
    public function dependencies(): array;

    public function boot(): void;

    public function register(): void;
}
