<?php

    namespace Api;
    
    use \Slim\App;

final class Router
{
    public static function set(App $api) : App
    {
        $api->group('/user', function (App $api) {
            $api->post('', 'User:create');

            /**
             * NOTE
             * These endpoints are ONLY FOR DEMONSTRATION.
             * The following endpoints are VERY dangerous.
             * User data should not be readable like this.
             */
            $api->get('', 'User:readAll');
            $api->get('/{identifier:[a-z0-9]+}', 'User:readBy');
            
            $api->patch('/{identifier:[a-z0-9]+}', 'User:updateBy');
            $api->delete('/{identifier:[a-z0-9]+}', 'User:deleteBy');
        });

        $api->any('/[{path:.*}]', function ($request, $response, $args) {
            return $response->withStatus(404);
        });
        
        return $api;
    }
}
