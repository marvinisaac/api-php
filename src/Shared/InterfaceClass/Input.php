<?php

    namespace Api\Shared\InterfaceClass;

    use \Slim\Http\Request;
    use \Slim\Http\Response;

interface Input
{
    public function create(Request $request, Response $response, array $args) : Response;
}
