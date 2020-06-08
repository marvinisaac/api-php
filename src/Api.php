<?php

    namespace Api;

    use \Slim\App;
    use \Slim\Http\Request;
    use \Slim\Http\Response;
    use Api\DependencyInjector;
    use Api\MiddlewareInjector;
    use Api\Environment;
    use Api\Setting;
    use Api\Router;

final class Api
{
    private App $api;

    public function __construct()
    {
        Environment::set();

        $setting = Setting::get();
        $api = new App($setting);

        $dependencyInjector = new DependencyInjector();
        $api = $dependencyInjector->inject($api);

        $middlewareInjector = new MiddlewareInjector();
        $api = $middlewareInjector->inject($api);

        $api = Router::set($api);

        $this->api = $api;
    }

    public function get() : App
    {
        return $this->api;
    }
}
