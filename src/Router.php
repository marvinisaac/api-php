<?php

    namespace Api;
    
    use \Slim\App;

final class Router
{
    public static function set(App $api) : App
    {
        $api->any('/[{path:.*}]', function ($request, $response, $args) {
            return $response->withStatus(404);
        });
        
        return $api;
    }
}
