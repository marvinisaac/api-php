<?php

    namespace Api\Shared\InterfaceClass;

    use \Slim\Http\Response;
    use Api\Shared\InterfaceClass\Output;

interface Resource
{
    public function setOutput(Output $output) : void;
    
    public function create(array $input) : Response;
}
