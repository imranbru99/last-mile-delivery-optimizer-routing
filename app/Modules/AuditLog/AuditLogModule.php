<?php

declare(strict_types=1);

namespace App\Modules\AuditLog;

use App\Modules\AbstractModule;

final class AuditLogModule extends AbstractModule
{
    public function key(): string
    {
        return 'audit_log';
    }
}
