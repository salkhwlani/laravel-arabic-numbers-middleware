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
        'eastern-to-arabic' => TransformEasternToArabicNumbers::class,
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
     *
     * @return Collection|bool
     */
    protected function autoAppendMiddleware()
    {
        $this->groupMiddleware = $this->app['config']->get('arabic-numbers-middleware.auto_register_middleware', false);

        if ($this->groupMiddleware === false) {
            return false;
        }

        $this->auto_middleware = collect($this->app['config']->get('arabic-numbers-middleware.auto_middleware', []));

        // Register as global Middleware
        if ($this->groupMiddleware === true) {
            $kernel = $this->app->make('Illuminate\Contracts\Http\Kernel');

            return $this->auto_middleware->each(function ($middleware) use ($kernel) {
                $kernel->pushMiddleware($middleware);
            });
        }

        // Register Middleware for group
        if (is_array($this->groupMiddleware) && count($this->groupMiddleware) > 0) {
            return $this->auto_middleware->each(function ($middleware) {
                foreach ($this->groupMiddleware as $group) {
                    $this->app['router']->prependMiddlewareToGroup($group, $middleware);
                }
            });
        }

        return false;
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
