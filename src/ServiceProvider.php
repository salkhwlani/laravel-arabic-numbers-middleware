<?php

namespace Yemenifree\LaravelArabicNumbersMiddleware;

use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Yemenifree\LaravelArabicNumbersMiddleware\Middleware\TransformArabicToEasternNumbers;
use Yemenifree\LaravelArabicNumbersMiddleware\Middleware\TransformEasternToArabicNumbers;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * All of the short-hand keys for middlewares.
     *
     * @var array
     */
    protected $middleware = [
        'arabic-to-eastern' => TransformArabicToEasternNumbers::class,
        'eastern-to-arabic' => TransformEasternToArabicNumbers::class
    ];

    /**
     * list of group middleware to auto append middleware to them.
     *
     *      true => all groups
     *      false => none
     *      ['web'] => for web group only
     *
     * @var bool|array
     */
    protected $groupMiddleware = false;

    /**
     * auto append middleware.
     *
     * @var Collection
     */
    protected $auto_middleware;


    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/arabic-numbers-middleware.php' => config_path('arabic-numbers-middleware.php'),
        ], 'config');

        $this->autoAppendMiddleware();
    }


    /**
     * auto append middleware to router.
     */
    protected function autoAppendMiddleware()
    {
        $this->groupMiddleware = $this->app['config']->get('arabic-numbers-middleware.auto_register_middleware', false);
        $this->auto_middleware = $this->app['config']->get('arabic-numbers-middleware.auto_middleware', false);

        if ($this->groupMiddleware === false || $this->auto_middleware === false) {
            return;
        }

        if ($this->groupMiddleware === true) { // Register middleware as global Middleware
            $this->app->make('Illuminate\Contracts\Http\Kernel')->pushMiddleware($this->auto_middleware);
        } else if (is_array($this->groupMiddleware) && count($this->groupMiddleware) > 0) { // Register Middleware for route group
            $this->pushMiddlewareToGroups($this->auto_middleware);
        }

        return;
    }

    /**
     * push middleware to route groups.
     *
     * @param $middleware
     */
    function pushMiddlewareToGroups($middleware)
    {
        foreach ($this->groupMiddleware as $group) {
            $this->app['router']->pushMiddlewareToGroup($group, $middleware);
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/arabic-numbers-middleware.php', 'arabic-numbers-middleware');
        $this->registerAliasMiddleware();
    }

    /**
     *  register alias middleware.
     */
    protected function registerAliasMiddleware()
    {
        foreach ($this->middleware as $alias => $middleware) {
            $this->app['router']->aliasMiddleware($alias, $middleware);
        }
    }
}