<?php

declare(strict_types=1);

namespace App\Providers\Filament;

use App\Support\Filament\ConfiguresLmdoPanel;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;

class CustomerPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return ConfiguresLmdoPanel::apply(
            panel: $panel,
            id: 'customer',
            path: 'customer',
            resourcePath: app_path('Filament/Customer/Resources'),
            resourceNamespace: 'App\\Filament\\Customer\\Resources',
            pagePath: app_path('Filament/Customer/Pages'),
            pageNamespace: 'App\\Filament\\Customer\\Pages',
            widgetPath: app_path('Filament/Customer/Widgets'),
            widgetNamespace: 'App\\Filament\\Customer\\Widgets',
            primaryColor: Color::Cyan,
            isDefault: false,
            spa: true,
        )->pages([
            Dashboard::class,
        ]);
    }
}
