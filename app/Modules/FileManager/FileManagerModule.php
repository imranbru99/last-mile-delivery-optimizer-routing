<?php

declare(strict_types=1);

namespace App\Modules\FileManager;

use App\Modules\AbstractModule;

final class FileManagerModule extends AbstractModule
{
    public function key(): string
    {
        return 'file_manager';
    }
}
