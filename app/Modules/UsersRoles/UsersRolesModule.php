<?php

declare(strict_types=1);

namespace App\Modules\UsersRoles;

use App\Modules\AbstractModule;

final class UsersRolesModule extends AbstractModule
{
    public function key(): string
    {
        return 'users_roles';
    }
}
