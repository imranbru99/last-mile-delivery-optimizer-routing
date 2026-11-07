<?php

declare(strict_types=1);

namespace App\Providers\Filament;

use App\Support\Filament\ConfiguresLmdoPanel;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;

class OperationsPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return ConfiguresLmdoPanel::apply(
            panel: $panel,
            id: 'operations',
            path: 'operations',
            resourcePath: app_path('Filament/Operations/Resources'),
            resourceNamespace: 'App\\Filament\\Operations\\Resources',
            pagePath: app_path('Filament/Operations/Pages'),
            pageNamespace: 'App\\Filament\\Operations\\Pages',
            widgetPath: app_path('Filament/Operations/Widgets'),
            widgetNamespace: 'App\\Filament\\Operations\\Widgets',
            primaryColor: Color::Sky,
            isDefault: false,
            spa: false,
        )->pages([
            Dashboard::class,
        ]);
    }
}
