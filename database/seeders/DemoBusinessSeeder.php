<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Business;
use App\Models\User;
use App\Support\Enums\UserRole;
use App\Support\Modules\ModuleManager;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoBusinessSeeder extends Seeder
{
    public function run(): void
    {
        $manager = app(ModuleManager::class);

        $super = User::query()->updateOrCreate(
            ['email' => 'superadmin@demo.test'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'role_hint' => UserRole::SuperAdmin->value,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        $super->syncRoles([UserRole::SuperAdmin->value]);

        $businesses = [
            [
                'name' => 'SwiftParcel Express',
                'slug' => 'swiftparcel-express',
                'email' => 'hello@swiftparcel.test',
                'business_type' => 'courier',
                'city' => 'Dhaka',
                'preset' => 'courier',
                'domain' => 'swiftparcel.test',
            ],
            [
                'name' => 'FreshBasket Grocery',
                'slug' => 'freshbasket-grocery',
                'email' => 'hello@freshbasket.test',
                'business_type' => 'grocery',
                'city' => 'Chattogram',
                'preset' => 'grocery',
                'domain' => 'freshbasket.test',
            ],
            [
                'name' => 'UrbanChauffeur Fleet',
                'slug' => 'urbanchauffeur-fleet',
                'email' => 'hello@urbanchauffeur.test',
                'business_type' => 'chauffeur',
                'city' => 'Sylhet',
                'preset' => 'chauffeur',
                'domain' => 'urbanchauffeur.test',
            ],
        ];

        foreach ($businesses as $data) {
            $business = Business::query()->updateOrCreate(
                ['slug' => $data['slug']],
                [
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'business_type' => $data['business_type'],
                    'city' => $data['city'],
                    'country_code' => 'BD',
                    'currency' => 'BDT',
                    'timezone' => 'Asia/Dhaka',
                    'is_active' => true,
                    'plan' => 'growth',
                    'primary_color' => '#0F766E',
                    'secondary_color' => '#134E4A',
                ]
            );

            $manager->applyPreset($data['preset'], $business);
            $this->seedUsersForBusiness($business, $data['domain']);
        }
    }

    private function seedUsersForBusiness(Business $business, string $domain): void
    {
        $map = [
            UserRole::BusinessOwner->value => ['name' => 'Owner', 'email' => "owner@{$domain}"],
            UserRole::OperationsManager->value => ['name' => 'Dispatcher', 'email' => "dispatcher@{$domain}"],
            UserRole::HubManager->value => ['name' => 'Hub Manager', 'email' => "hub@{$domain}"],
            UserRole::Driver->value => ['name' => 'Driver One', 'email' => "driver1@{$domain}"],
            UserRole::FleetManager->value => ['name' => 'Fleet Manager', 'email' => "fleet@{$domain}"],
            UserRole::Finance->value => ['name' => 'Finance', 'email' => "finance@{$domain}"],
            UserRole::SupportAgent->value => ['name' => 'Support', 'email' => "support@{$domain}"],
            UserRole::Merchant->value => ['name' => 'Merchant', 'email' => "merchant@{$domain}"],
            UserRole::Customer->value => ['name' => 'Customer', 'email' => "customer@{$domain}"],
            UserRole::FranchisePartner->value => ['name' => 'Franchise', 'email' => "franchise@{$domain}"],
            UserRole::Auditor->value => ['name' => 'Auditor', 'email' => "auditor@{$domain}"],
        ];

        foreach ($map as $role => $info) {
            $user = User::query()->updateOrCreate(
                ['email' => $info['email']],
                [
                    'business_id' => $business->id,
                    'name' => $info['name'].' · '.$business->name,
                    'password' => Hash::make('password'),
                    'role_hint' => $role,
                    'is_active' => true,
                    'email_verified_at' => now(),
                    'locale' => 'en',
                    'timezone' => 'Asia/Dhaka',
                ]
            );
            $user->syncRoles([$role]);
        }
    }
}
