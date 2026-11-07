<?php

declare(strict_types=1);

namespace App\Providers\Filament;

use App\Support\Filament\ConfiguresLmdoPanel;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;

class HubPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return ConfiguresLmdoPanel::apply(
            panel: $panel,
            id: 'hub',
            path: 'hub',
            resourcePath: app_path('Filament/Hub/Resources'),
            resourceNamespace: 'App\\Filament\\Hub\\Resources',
            pagePath: app_path('Filament/Hub/Pages'),
            pageNamespace: 'App\\Filament\\Hub\\Pages',
            widgetPath: app_path('Filament/Hub/Widgets'),
            widgetNamespace: 'App\\Filament\\Hub\\Widgets',
            primaryColor: Color::Amber,
            isDefault: false,
            spa: false,
        )->pages([
            Dashboard::class,
        ]);
    }
}
