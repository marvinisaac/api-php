<?php

    namespace Api\Shared\BaseClass;

    use \Slim\App;
    use \Slim\Http\Environment;
    use \Slim\Http\Headers;
    use \Slim\Http\Request;
    use \Slim\Http\RequestBody;
    use \Slim\Http\Response;
    use \Slim\Http\Uri;
    use Api\Api;
    use Api\Shared\InterfaceClass\Database;
    use Api\Shared\InterfaceClass\Output;
    use Api\Shared\InterfaceClass\Resource as ResourceInterface;

abstract class Resource implements ResourceInterface
{
    protected Database $database;
    protected Output $output;
    
    abstract public function create(array $input) : Response;

    abstract public function readAll() : Response;

    abstract public function readBy(string $identifier) : Response;

    abstract public function updateBy(string $identifier, array $input): Response;

    public function setDatabase(Database $database) : void
    {
        $this->database = $database;
    }

    public function setOutput(Output $output) : void
    {
        $this->output = $output;
    }

    protected function checkRequired(array $inputRequired, array $input) : array
    {
        $inputMissing = [];

        foreach ($inputRequired as $parent => $child) {
            if (is_array($child)) {
                if (!isset($input[$parent])) {
                    $inputMissing[] = $parent;
                    continue;
                }
                foreach ($child as $childField) {
                    if (!isset($input[$parent][$childField]) ||
                        trim($input[$parent][$childField]) === ''
                    ) {
                        $inputMissing[] = $parent . '->' . $childField;
                    }
                }
            } else {
                if (!isset($input[$child]) ||
                    trim($input[$child]) === ''
                ) {
                    $inputMissing[] = $child;
                    continue;
                }
            }
        }

        return $inputMissing;
    }

    protected function filterReadResults(array $fieldPublic, array $fieldResults) : array
    {
        foreach ($fieldResults as $field => $value) {
            if (!in_array($field, $fieldPublic)) {
                unset($fieldResults[$field]);
            }
        }

        return $fieldResults;
    }

    protected function handleInputMissing(array $inputMissing) : Response
    {
        $errorMessage = 'Missing input: ' . implode(', ', $inputMissing);
        return $this->output->error(400, $errorMessage);
    }

    protected function run(string $method = 'GET', string $uri = '/', array $requestBody = []) : array
    {
        $api = (new Api())->get();

        // Prepare environment
        if ($method === 'PATCH' ||
            $method === 'POST'
        ) {
            $environment = Environment::mock([
                'REQUEST_METHOD' => $method,
                'REQUEST_URI' => $uri,
                // Add required request header. Without it, request body is ignored
                'CONTENT_TYPE' => 'application/json',
            ]);
        } else {
            $environment = Environment::mock([
                'REQUEST_METHOD' => $method,
                'REQUEST_URI' => $uri,
            ]);
        }

        // Prepare request
        $uri = Uri::createFromEnvironment($environment);
        $headers = Headers::createFromEnvironment($environment);
        $cookies = [];
        $serverParams = $environment->all();
        $body = new RequestBody();
        if (count($requestBody) > 0) {
            $requestBodyParsed = (string) json_encode($requestBody);
            $body->write($requestBodyParsed);
        }
        $request = new Request($method, $uri, $headers, $cookies, $serverParams, $body);

        // Run request and return response
        $api->getContainer()['request'] = $request;
        $response = $api->run(true);
        $responseStatus = $response->getStatusCode();
        $responseBody = json_decode((string)$response->getBody(), true);
        return [
            'status' => $responseStatus,
            'body' => $responseBody,
        ];
    }
}
