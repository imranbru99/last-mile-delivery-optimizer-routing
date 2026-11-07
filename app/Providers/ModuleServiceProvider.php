<?php

declare(strict_types=1);

namespace App\Providers;

use App\Modules\Contracts\ModuleDefinition;
use Illuminate\Support\ServiceProvider;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RegexIterator;

class ModuleServiceProvider extends ServiceProvider
{
    /** @var list<class-string<ModuleDefinition>> */
    private array $moduleClasses = [];

    public function register(): void
    {
        foreach ($this->discoverModules() as $class) {
            $this->app->singleton($class);
            $this->moduleClasses[] = $class;

            /** @var ModuleDefinition $module */
            $module = $this->app->make($class);
            $module->register();
        }

        $this->app->instance('lmdo.modules.definitions', $this->moduleClasses);
    }

    public function boot(): void
    {
        foreach ($this->moduleClasses as $class) {
            /** @var ModuleDefinition $module */
            $module = $this->app->make($class);
            $module->boot();
        }
    }

    /**
     * @return list<class-string<ModuleDefinition>>
     */
    private function discoverModules(): array
    {
        $root = app_path('Modules');

        if (! is_dir($root)) {
            return [];
        }

        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root));
        $files = new RegexIterator($iterator, '/^.+Module\.php$/i', RegexIterator::GET_MATCH);

        $classes = [];

        foreach ($files as $file) {
            $path = $file[0] ?? null;

            if (! is_string($path)) {
                continue;
            }

            $relative = str_replace([app_path().'/', '.php', '/'], ['', '', '\\'], $path);
            $class = 'App\\'.$relative;

            if (! class_exists($class)) {
                continue;
            }

            if (! is_subclass_of($class, ModuleDefinition::class)) {
                continue;
            }

            if ((new \ReflectionClass($class))->isAbstract()) {
                continue;
            }

            $classes[] = $class;
        }

        sort($classes);

        return $classes;
    }
}
