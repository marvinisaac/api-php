<?php

    namespace Api;

final class Setting
{
    public static function get() : array
    {
        return [
            'settings' => [
                'addContentLengthHeader' => false,
                'determineRouteBeforeAppMiddleware' => true,
                'displayErrorDetails' => true,
            ],
        ];
    }
}
