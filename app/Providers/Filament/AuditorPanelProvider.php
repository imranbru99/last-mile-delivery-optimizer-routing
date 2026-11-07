<?php

declare(strict_types=1);

namespace App\Providers\Filament;

use App\Support\Filament\ConfiguresLmdoPanel;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;

class AuditorPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return ConfiguresLmdoPanel::apply(
            panel: $panel,
            id: 'auditor',
            path: 'auditor',
            resourcePath: app_path('Filament/Auditor/Resources'),
            resourceNamespace: 'App\\Filament\\Auditor\\Resources',
            pagePath: app_path('Filament/Auditor/Pages'),
            pageNamespace: 'App\\Filament\\Auditor\\Pages',
            widgetPath: app_path('Filament/Auditor/Widgets'),
            widgetNamespace: 'App\\Filament\\Auditor\\Widgets',
            primaryColor: Color::Slate,
            isDefault: false,
            spa: false,
        )->pages([
            Dashboard::class,
        ]);
    }
}
