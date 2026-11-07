<?php

declare(strict_types=1);

namespace App\Providers\Filament;

use App\Support\Filament\ConfiguresLmdoPanel;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;

/**
 * Convenience alias at /admin that mirrors the business owner panel.
 */
class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return ConfiguresLmdoPanel::apply(
            panel: $panel,
            id: 'admin',
            path: 'admin',
            resourcePath: app_path('Filament/Business/Resources'),
            resourceNamespace: 'App\\Filament\\Business\\Resources',
            pagePath: app_path('Filament/Business/Pages'),
            pageNamespace: 'App\\Filament\\Business\\Pages',
            widgetPath: app_path('Filament/Business/Widgets'),
            widgetNamespace: 'App\\Filament\\Business\\Widgets',
            primaryColor: Color::Teal,
            isDefault: false,
        )->pages([
            Dashboard::class,
        ]);
    }
}
