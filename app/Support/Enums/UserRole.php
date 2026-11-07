<?php

declare(strict_types=1);

namespace App\Support\Enums;

enum UserRole: string
{
    case SuperAdmin = 'super_admin';
    case BusinessOwner = 'business_owner';
    case OperationsManager = 'operations_manager';
    case HubManager = 'hub_manager';
    case Driver = 'driver';
    case FleetManager = 'fleet_manager';
    case Finance = 'finance';
    case SupportAgent = 'support_agent';
    case Merchant = 'merchant';
    case Customer = 'customer';
    case FranchisePartner = 'franchise_partner';
    case Auditor = 'auditor';

    public function label(): string
    {
        return match ($this) {
            self::SuperAdmin => 'Super Admin',
            self::BusinessOwner => 'Business Owner / Admin',
            self::OperationsManager => 'Operations Manager / Dispatcher',
            self::HubManager => 'Hub / Warehouse Manager',
            self::Driver => 'Driver / Rider',
            self::FleetManager => 'Fleet Manager',
            self::Finance => 'Finance / Accountant',
            self::SupportAgent => 'Customer Support Agent',
            self::Merchant => 'Merchant / Shipper',
            self::Customer => 'End Customer / Recipient',
            self::FranchisePartner => 'Franchise / Agent Partner',
            self::Auditor => 'Auditor / Read-only',
        };
    }

    public function panelId(): string
    {
        return match ($this) {
            self::SuperAdmin => 'super-admin',
            self::BusinessOwner => 'business',
            self::OperationsManager => 'operations',
            self::HubManager => 'hub',
            self::Driver => 'driver',
            self::FleetManager => 'fleet',
            self::Finance => 'finance',
            self::SupportAgent => 'support',
            self::Merchant => 'merchant',
            self::Customer => 'customer',
            self::FranchisePartner => 'franchise',
            self::Auditor => 'auditor',
        };
    }

    /**
     * @return list<self>
     */
    public static function businessRoles(): array
    {
        return array_values(array_filter(
            self::cases(),
            fn (self $role): bool => $role !== self::SuperAdmin
        ));
    }
}
