<?php

    namespace Api;
    
    use \Slim\App;

final class Router
{
    public static function set(App $api) : App
    {
        $api->group('/user', function (App $api) {
            $api->post('', 'User:create');
        });

        $api->any('/[{path:.*}]', function ($request, $response, $args) {
            return $response->withStatus(404);
        });
        
        return $api;
    }
}
