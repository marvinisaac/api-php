<?php

    namespace Api\Shared\BaseClass;

    use \Slim\Http\Request;
    use \Slim\Http\Response;
    use Api\Shared\BaseClass\Output;
    use Api\Shared\InterfaceClass\Input as InputInterface;
    use Api\Shared\InterfaceClass\Resource  as ResourceInterface;

class Input implements InputInterface
{
    protected ResourceInterface $resource;

    public function __construct(ResourceInterface $resource)
    {
        $this->resource = $resource;
    }

    public function create(Request $request, Response $response, array $args) : Response
    {
        $output = $this->setOutput($response);

        $input = (array) $request->getParsedBody();
        return $this->resource->create($input);
    }

    public function readAll(Request $request, Response $response, array $args) : Response
    {
        $output = $this->setOutput($response);
        return $this->resource->readAll();
    }

    public function readBy(Request $request, Response $response, array $args) : Response
    {
        $output = $this->setOutput($response);
        
        $id = $args['identifier'];
        return $this->resource->readBy($id);
    }

    public function updateBy(Request $request, Response $response, array $args) : Response
    {
        $output = $this->setOutput($response);

        $input = (array) $request->getParsedBody();
        $identifier = $args['identifier'];
        return $this->resource->updateBy($identifier, $input);
    }

    public function deleteBy(Request $request, Response $response, array $args) : Response
    {
        $output = $this->setOutput($response);
        
        $id = $args['identifier'];
        return $this->resource->deleteBy($id);
    }

    protected function logError(string $errorMessage) : void
    {
        if ($_ENV['PHP_ENVIRONMENT'] !== 'PRODUCTION' &&
            $_ENV['PHP_ENVIRONMENT'] !== 'TEST'
        ) {
            error_log('>>> Debug message: ' . $errorMessage);
        }
    }

    protected function setOutput(Response $response) : Output
    {
        $output = new Output($response);
        $this->resource->setOutput($output);

        return $output;
    }
}
