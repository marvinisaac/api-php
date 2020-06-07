<?php

    namespace Api;

final class Setting
{
    public static function get() : array
    {
        return [
            'settings' => [
                'addContentLengthHeader' => false,
                'database' => [
                    'driver' => 'mysql',
                    'host' => $_ENV['MYSQL_HOSTNAME'],
                    'database' => $_ENV['MYSQL_DATABASE'],
                    'username' => $_ENV['MYSQL_USERNAME'],
                    'password' => $_ENV['MYSQL_PASSWORD'],
                    'charset' => 'utf8',
                    'prefix' => '',
                    'options' => [],
                ],
                'determineRouteBeforeAppMiddleware' => true,
                'displayErrorDetails' => true,
            ],
        ];
    }
}
