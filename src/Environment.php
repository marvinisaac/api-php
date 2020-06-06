<?php

    namespace Api;

    use \Dotenv\Dotenv;

final class Environment
{
    public static function set() : void
    {
        $variables = [];

        try {
            $env = Dotenv::create(__DIR__ . '/../');
            $env->load();
            $env->required($variables);
        } catch (\Exception $e) {
            error_log($e);
            header("HTTP/1.1 500 Server Error");
            exit();
        }
    }
}
