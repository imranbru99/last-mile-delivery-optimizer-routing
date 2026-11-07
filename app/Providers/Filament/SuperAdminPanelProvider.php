<?php

declare(strict_types=1);

namespace App\Providers\Filament;

use App\Support\Filament\ConfiguresLmdoPanel;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;

class SuperAdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return ConfiguresLmdoPanel::apply(
            panel: $panel,
            id: 'super-admin',
            path: 'super-admin',
            resourcePath: app_path('Filament/SuperAdmin/Resources'),
            resourceNamespace: 'App\\Filament\\SuperAdmin\\Resources',
            pagePath: app_path('Filament/SuperAdmin/Pages'),
            pageNamespace: 'App\\Filament\\SuperAdmin\\Pages',
            widgetPath: app_path('Filament/SuperAdmin/Widgets'),
            widgetNamespace: 'App\\Filament\\SuperAdmin\\Widgets',
            primaryColor: Color::Indigo,
            isDefault: false,
            spa: false,
        )->pages([
            Dashboard::class,
        ]);
    }
}
