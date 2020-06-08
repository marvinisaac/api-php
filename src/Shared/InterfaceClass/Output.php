<?php

    namespace Api\Shared\InterfaceClass;

    use \Slim\Http\Response;

interface Output
{
    public function success(int $status, array $output = []) : Response;

    public function error(int $status, string $message = '') : Response;
}
