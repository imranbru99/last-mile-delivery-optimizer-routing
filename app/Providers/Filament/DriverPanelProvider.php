<?php

declare(strict_types=1);

namespace App\Providers\Filament;

use App\Support\Filament\ConfiguresLmdoPanel;
use Filament\Facades\Filament;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;

class DriverPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return ConfiguresLmdoPanel::apply(
            panel: $panel,
            id: 'driver',
            path: 'driver',
            resourcePath: app_path('Filament/Driver/Resources'),
            resourceNamespace: 'App\\Filament\\Driver\\Resources',
            pagePath: app_path('Filament/Driver/Pages'),
            pageNamespace: 'App\\Filament\\Driver\\Pages',
            widgetPath: app_path('Filament/Driver/Widgets'),
            widgetNamespace: 'App\\Filament\\Driver\\Widgets',
            primaryColor: Color::Emerald,
            isDefault: false,
            spa: true,
        )
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn (): string => Filament::getCurrentPanel()?->getId() === 'driver'
                    ? '<link rel="manifest" href="/manifest.webmanifest" />'
                    : '',
            )
            ->pages([
                Dashboard::class,
            ]);
    }
}
