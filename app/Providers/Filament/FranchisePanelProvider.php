<?php

declare(strict_types=1);

namespace App\Providers\Filament;

use App\Support\Filament\ConfiguresLmdoPanel;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;

class FranchisePanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return ConfiguresLmdoPanel::apply(
            panel: $panel,
            id: 'franchise',
            path: 'franchise',
            resourcePath: app_path('Filament/Franchise/Resources'),
            resourceNamespace: 'App\\Filament\\Franchise\\Resources',
            pagePath: app_path('Filament/Franchise/Pages'),
            pageNamespace: 'App\\Filament\\Franchise\\Pages',
            widgetPath: app_path('Filament/Franchise/Widgets'),
            widgetNamespace: 'App\\Filament\\Franchise\\Widgets',
            primaryColor: Color::Fuchsia,
            isDefault: false,
            spa: false,
        )->pages([
            Dashboard::class,
        ]);
    }
}
