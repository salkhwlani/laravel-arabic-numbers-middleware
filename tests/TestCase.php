<?php

namespace Yemenifree\LaravelArabicNumbersMiddleware\Test;

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Routing\RouteCollection;
use Orchestra\Testbench\TestCase as Orchestra;
use Yemenifree\LaravelArabicNumbersMiddleware\ServiceProvider;

class TestCase extends Orchestra
{
    /** @var array */
    protected $config = [];
    protected $auto_register_middleware = true;

    protected $easternTestData = ['login' => '٠٥٠٠٤٨٤٣٥٠', 'pass' => '١٢٣٤٥٦'];
    protected $arabicTestData = ['login' => '0500484350', 'pass' => '123456'];
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
     * {@inheritDoc}
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('arabic-numbers-middleware.auto_register_middleware', $this->auto_register_middleware);
    }

    /**
     * refresh (reboot) app
     *
     * because auto register middleware as global happen when boot app so if we need disabled it we need reboot app also
     */
    protected function refreshApp()
    {
        $this->app = false;
        $this->setUp();
    }

    public function setUp()
    {
        parent::setUp();
        $this->setUpRoutes($this->app);
        $this->config = $this->app['config']->get('arabic-numbers-middleware');
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