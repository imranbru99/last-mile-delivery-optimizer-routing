<?php

declare(strict_types=1);

namespace App\Providers\Filament;

use App\Support\Filament\ConfiguresLmdoPanel;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;

class SupportPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return ConfiguresLmdoPanel::apply(
            panel: $panel,
            id: 'support',
            path: 'support',
            resourcePath: app_path('Filament/Support/Resources'),
            resourceNamespace: 'App\\Filament\\Support\\Resources',
            pagePath: app_path('Filament/Support/Pages'),
            pageNamespace: 'App\\Filament\\Support\\Pages',
            widgetPath: app_path('Filament/Support/Widgets'),
            widgetNamespace: 'App\\Filament\\Support\\Widgets',
            primaryColor: Color::Rose,
            isDefault: false,
            spa: false,
        )->pages([
            Dashboard::class,
        ]);
    }
}
