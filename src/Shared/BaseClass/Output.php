<?php

    namespace Api\Shared\BaseClass;

    use \Slim\Http\Response;
    use Api\Shared\InterfaceClass\Output as OutputInterface;

class Output implements OutputInterface
{
    protected Response $response;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function success(int $status, array $output = []) : Response
    {
        $response = $this->response->withStatus($status);

        if (count($output) > 0) {
            $response = $response->withJson($output);
        }

        return $response;
    }

    public function error(int $status, string $message = '') : Response
    {
        $response = $this->response->withStatus($status);

        if ($message !== '') {
            $response = $response->withJson([
                'error_message' => $message,
            ]);
        }
            
        return $response;
    }
}
