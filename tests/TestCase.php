<?php

namespace Yemenifree\LaravelArabicNumbersMiddleware\Test;

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Routing\RouteCollection;
use Illuminate\Routing\Router;
use Orchestra\Testbench\TestCase as Orchestra;
use Yemenifree\LaravelArabicNumbersMiddleware\ServiceProvider;

class TestCase extends Orchestra
{
    /** @var bool|array */
    protected $auto_register_middleware = true;

    /** @var array */
    protected $easternTestData = ['login' => '٠٥٠٠٤٨٤٣٥٠', 'pass' => '١٢٣٤٥٦'];
    /** @var array */
    protected $arabicTestData = ['login' => '0500484350', 'pass' => '123456'];
    /** @var array */
    protected $ignoreTestData = ['login' => '٠٥٠٠٤٨٤٣٥٠', 'pass' => '123456'];

    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            ServiceProvider::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('app.key', 'base64:3vtsrVEhtx8x64PxYlaP2AxGlhFW75AyTr4t5uhJi/4=');
        $app['config']->set('arabic-numbers-middleware.auto_register_middleware', $this->auto_register_middleware);
        $app['router']->middlewareGroup('web', []);
    }

    /**
     * refresh (reboot) app.
     *
     * because auto register middleware as global happen when boot app so if we need disabled it we need reboot app also
     *
     * @param bool|array $auto_register_middleware
     */
    protected function refreshApp($auto_register_middleware = true)
    {
        $this->app = false;
        $this->auto_register_middleware = $auto_register_middleware;
        $this->setUp();
    }

    public function setUp()
    {
        parent::setUp();
        $this->setUpRoutes($this->app);
    }

    /**
     * @param Application $app
     */
    protected function setUpRoutes($app)
    {
        $this->app->get('router')->setRoutes(new RouteCollection());
        $this->app->get('router')->any('/login-eastern-to-arabic', ['middleware' => 'eastern-to-arabic', $this->responseRequest()]);
        $this->app->get('router')->any('/login-eastern-to-arabic-ignore-pass-field-inline', ['middleware' => 'eastern-to-arabic:pass', $this->responseRequest()]);
        $this->app->get('router')->any('/login-eastern-to-arabic-auto-append', [$this->responseRequest()]);
        $this->app->get('router')->any('/login-arabic-to-eastern', ['middleware' => 'arabic-to-eastern', $this->responseRequest()]);
        $this->app->get('router')->any('/login-arabic-to-eastern-ignore-pass-field-inline', ['middleware' => 'arabic-to-eastern:pass', $this->responseRequest()]);
        $this->app->get('router')->any('/login-arabic-to-eastern-auto-append', [$this->responseRequest()]);
        $this->app->get('router')->any('/login-normal', [$this->responseRequest()]);
        $this->app->get('router')->group(['middleware' => 'web'], function (Router $router) {
            $router->any('/login-eastern-to-arabic-auto-append-to-route-group', [$this->responseRequest()]);
            $router->any('/login-arabic-to-eastern-auto-append-to-route-group', [$this->responseRequest()]);
        });
    }

    /**
     * @return \Closure
     */
    protected function responseRequest()
    {
        return function (Request $request) {
            return response()->json($request, 200, [], JSON_UNESCAPED_UNICODE);
        };
    }
}
