<?php

declare(strict_types=1);

namespace App\Support\Filament;

use App\Http\Middleware\SetTenantFromAuth;
use App\Support\Enums\UserRole;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

final class ConfiguresLmdoPanel
{
    /**
     * @param  class-string  $resourceNamespace
     * @param  class-string  $pageNamespace
     * @param  class-string  $widgetNamespace
     */
    public static function apply(
        Panel $panel,
        string $id,
        string $path,
        string $resourcePath,
        string $resourceNamespace,
        string $pagePath,
        string $pageNamespace,
        string $widgetPath,
        string $widgetNamespace,
        mixed $primaryColor = null,
        bool $isDefault = false,
        bool $spa = false,
    ): Panel {
        $panel = $panel
            ->id($id)
            ->path($path)
            ->login()
            ->colors([
                'primary' => $primaryColor ?? Color::Teal,
            ])
            ->discoverResources(in: $resourcePath, for: $resourceNamespace)
            ->discoverPages(in: $pagePath, for: $pageNamespace)
            ->discoverWidgets(in: $widgetPath, for: $widgetNamespace)
            ->widgets([
                AccountWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                SetTenantFromAuth::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->brandName('Last-Mile Optimizer')
            ->favicon(asset('favicon.ico'))
            ->sidebarCollapsibleOnDesktop();

        if ($isDefault) {
            $panel = $panel->default();
        }

        if ($spa) {
            $panel = $panel->spa();
        }

        return $panel;
    }

    public static function roleForPanel(string $panelId): ?UserRole
    {
        foreach (UserRole::cases() as $role) {
            if ($role->panelId() === $panelId) {
                return $role;
            }
        }

        return null;
    }
}
