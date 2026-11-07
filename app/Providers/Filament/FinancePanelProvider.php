<?php

declare(strict_types=1);

namespace App\Providers\Filament;

use App\Support\Filament\ConfiguresLmdoPanel;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;

class FinancePanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return ConfiguresLmdoPanel::apply(
            panel: $panel,
            id: 'finance',
            path: 'finance',
            resourcePath: app_path('Filament/Finance/Resources'),
            resourceNamespace: 'App\\Filament\\Finance\\Resources',
            pagePath: app_path('Filament/Finance/Pages'),
            pageNamespace: 'App\\Filament\\Finance\\Pages',
            widgetPath: app_path('Filament/Finance/Widgets'),
            widgetNamespace: 'App\\Filament\\Finance\\Widgets',
            primaryColor: Color::Green,
            isDefault: false,
            spa: false,
        )->pages([
            Dashboard::class,
        ]);
    }
}
