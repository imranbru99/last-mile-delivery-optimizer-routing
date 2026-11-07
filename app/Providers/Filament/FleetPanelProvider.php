<?php

declare(strict_types=1);

namespace App\Providers\Filament;

use App\Support\Filament\ConfiguresLmdoPanel;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;

class FleetPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return ConfiguresLmdoPanel::apply(
            panel: $panel,
            id: 'fleet',
            path: 'fleet',
            resourcePath: app_path('Filament/Fleet/Resources'),
            resourceNamespace: 'App\\Filament\\Fleet\\Resources',
            pagePath: app_path('Filament/Fleet/Pages'),
            pageNamespace: 'App\\Filament\\Fleet\\Pages',
            widgetPath: app_path('Filament/Fleet/Widgets'),
            widgetNamespace: 'App\\Filament\\Fleet\\Widgets',
            primaryColor: Color::Orange,
            isDefault: false,
            spa: false,
        )->pages([
            Dashboard::class,
        ]);
    }
}
