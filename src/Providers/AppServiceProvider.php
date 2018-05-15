<?php

declare(strict_types=1);

namespace Mai1015\PlusIllusts\Providers;

use Zhiyi\Plus\Support\PackageHandler;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Boorstrap the service provider.
     *
     * @return void
     */
    public function boot()
    {
        // Register a database migration path.
        $this->loadMigrationsFrom($this->app->make('path.plus-illusts.migrations'));

        // Register translations.
        $this->loadTranslationsFrom($this->app->make('path.plus-illusts.lang'), 'plus-illusts');

        // Register view namespace.
        $this->loadViewsFrom($this->app->make('path.plus-illusts.views'), 'plus-illusts');

        // Publish public resource.
        $this->publishes([
            $this->app->make('path.plus-illusts.assets') => $this->app->publicPath().'/assets/plus-illusts',
        ], 'plus-illusts-public');

        // Publish config.
        $this->publishes([
            $this->app->make('path.plus-illusts.config').'/plus-illusts.php' => $this->app->configPath('plus-illusts.php'),
        ], 'plus-illusts-config');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // Bind all of the package paths in the container.
        $this->bindPathsInContainer();

        // Merge config.
        $this->mergeConfigFrom(
            $this->app->make('path.plus-illusts.config').'/plus-illusts.php',
            'plus-illusts'
        );

        // register cntainer aliases
        $this->registerCoreContainerAliases();

        // Register singletons.
        $this->registerSingletions();

        // Register Plus package handlers.
        $this->registerPackageHandlers();
    }

    /**
     * Bind paths in container.
     *
     * @return void
     */
    protected function bindPathsInContainer()
    {
        foreach ([
            'path.plus-illusts' => $root = dirname(dirname(__DIR__)),
            'path.plus-illusts.assets' => $root.'/assets',
            'path.plus-illusts.config' => $root.'/config',
            'path.plus-illusts.database' => $database = $root.'/database',
            'path.plus-illusts.resources' => $resources = $root.'/resources',
            'path.plus-illusts.lang' => $resources.'/lang',
            'path.plus-illusts.views' => $resources.'/views',
            'path.plus-illusts.migrations' => $database.'/migrations',
            'path.plus-illusts.seeds' => $database.'/seeds',
        ] as $abstract => $instance) {
            $this->app->instance($abstract, $instance);
        }
    }

    /**
     * Register singletons.
     *
     * @return void
     */
    protected function registerSingletions()
    {
        // Owner handler.
        $this->app->singleton('plus-illusts:handler', function () {
            return new \Mai1015\PlusIllusts\Handlers\PackageHandler();
        });

        // Develop handler.
        $this->app->singleton('plus-illusts:dev-handler', function ($app) {
            return new \Mai1015\PlusIllusts\Handlers\DevPackageHandler($app);
        });
    }

    /**
     * Register the package class aliases in the container.
     *
     * @return void
     */
    protected function registerCoreContainerAliases()
    {
        foreach ([
            'plus-illusts:handler' => [
                \Mai1015\PlusIllusts\Handlers\PackageHandler::class,
            ],
            'plus-illusts:dev-handler' => [
                \Mai1015\PlusIllusts\Handlers\DevPackageHandler::class,
            ],
        ] as $abstract => $aliases) {
            foreach ($aliases as $alias) {
                $this->app->alias($abstract, $alias);
            }
        }
    }

    /**
     * Register Plus package handlers.
     *
     * @return void
     */
    protected function registerPackageHandlers()
    {
        $this->loadHandleFrom('plus-illusts', 'plus-illusts:handler');
        $this->loadHandleFrom('plus-illusts-dev', 'plus-illusts:dev-handler');
    }

    /**
     * Register handler.
     *
     * @param string $name
     * @param \Zhiyi\Plus\Support\PackageHandler|string $handler
     * @return void
     */
    private function loadHandleFrom(string $name, $handler)
    {
        PackageHandler::loadHandleFrom($name, $handler);
    }
}
