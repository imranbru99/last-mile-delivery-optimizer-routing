<?php

declare(strict_types=1);

namespace App\Modules;

use App\Modules\Concerns\RegistersModuleRoutes;
use App\Modules\Contracts\ModuleDefinition;
use App\Support\Modules\ModuleManager;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;

abstract class AbstractModule implements ModuleDefinition
{
    use RegistersModuleRoutes;

    abstract public function key(): string;

    /**
     * @return list<string>
     */
    public function dependencies(): array
    {
        $registry = app(ModuleManager::class)->find($this->key());

        return array_values($registry['dependencies'] ?? []);
    }

    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $base = $this->basePath();

        $this->loadModuleRoutes($this->key(), $base.'/routes/web.php');
        $this->loadModuleApiRoutes($this->key(), $base.'/routes/api.php');
        $this->registerPolicies();
        $this->registerListeners();
    }

    public function isEnabled(): bool
    {
        return app(ModuleManager::class)->isEnabled($this->key());
    }

    protected function basePath(): string
    {
        $reflector = new \ReflectionClass($this);

        return dirname($reflector->getFileName() ?: __DIR__);
    }

    /**
     * @return array<class-string, class-string>
     */
    protected function policies(): array
    {
        return [];
    }

    /**
     * @return array<class-string, list<class-string>>
     */
    protected function listeners(): array
    {
        return [];
    }

    private function registerPolicies(): void
    {
        foreach ($this->policies() as $model => $policy) {
            Gate::policy($model, $policy);
        }
    }

    private function registerListeners(): void
    {
        foreach ($this->listeners() as $event => $listeners) {
            foreach ($listeners as $listener) {
                Event::listen($event, $listener);
            }
        }
    }
}
