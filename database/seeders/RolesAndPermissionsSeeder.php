<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Support\Enums\UserRole;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'businesses.view', 'businesses.manage',
            'modules.view', 'modules.manage',
            'users.view', 'users.manage',
            'orders.view', 'orders.manage',
            'routes.view', 'routes.optimize', 'routes.dispatch',
            'fleet.view', 'fleet.manage',
            'hubs.view', 'hubs.manage',
            'finance.view', 'finance.manage',
            'support.view', 'support.manage',
            'reports.view', 'reports.export',
            'settings.manage',
            'impersonate',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }

        foreach (UserRole::cases() as $roleEnum) {
            $role = Role::findOrCreate($roleEnum->value);

            $role->syncPermissions(match ($roleEnum) {
                UserRole::SuperAdmin => Permission::all(),
                UserRole::BusinessOwner => Permission::where('name', '!=', 'impersonate')->get(),
                UserRole::OperationsManager => [
                    'orders.view', 'orders.manage', 'routes.view', 'routes.optimize', 'routes.dispatch',
                    'fleet.view', 'hubs.view', 'reports.view',
                ],
                UserRole::HubManager => ['orders.view', 'hubs.view', 'hubs.manage', 'routes.view'],
                UserRole::Driver => ['orders.view', 'routes.view'],
                UserRole::FleetManager => ['fleet.view', 'fleet.manage', 'routes.view'],
                UserRole::Finance => ['finance.view', 'finance.manage', 'orders.view', 'reports.view', 'reports.export'],
                UserRole::SupportAgent => ['support.view', 'support.manage', 'orders.view'],
                UserRole::Merchant => ['orders.view', 'orders.manage', 'finance.view'],
                UserRole::Customer => ['orders.view'],
                UserRole::FranchisePartner => ['orders.view', 'orders.manage', 'finance.view', 'reports.view'],
                UserRole::Auditor => [
                    'businesses.view', 'modules.view', 'users.view', 'orders.view', 'routes.view',
                    'fleet.view', 'hubs.view', 'finance.view', 'support.view', 'reports.view',
                ],
            });
        }
    }
}
