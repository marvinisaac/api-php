<?php

    namespace Api;

    use \Slim\App;
    use Api\Middleware\Authenticator;
    use Api\Middleware\CorsHeadersAdder;

final class MiddlewareInjector
{
    public function inject(App $api) : App
    {
        return $api;
    }
}
