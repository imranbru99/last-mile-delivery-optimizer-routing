<?php

declare(strict_types=1);

namespace App\Providers\Filament;

use App\Support\Filament\ConfiguresLmdoPanel;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;

class MerchantPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return ConfiguresLmdoPanel::apply(
            panel: $panel,
            id: 'merchant',
            path: 'merchant',
            resourcePath: app_path('Filament/Merchant/Resources'),
            resourceNamespace: 'App\\Filament\\Merchant\\Resources',
            pagePath: app_path('Filament/Merchant/Pages'),
            pageNamespace: 'App\\Filament\\Merchant\\Pages',
            widgetPath: app_path('Filament/Merchant/Widgets'),
            widgetNamespace: 'App\\Filament\\Merchant\\Widgets',
            primaryColor: Color::Violet,
            isDefault: false,
            spa: false,
        )->pages([
            Dashboard::class,
        ]);
    }
}
