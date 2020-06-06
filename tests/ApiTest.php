<?php

    namespace Test;

    use \PHPUnit\Framework\TestCase;
    use \Slim\App;
    use \Slim\Http\Environment;
    use \Slim\Http\Headers;
    use \Slim\Http\Request;
    use \Slim\Http\RequestBody;
    use \Slim\Http\Uri;
    use Api\Api;

class ApiTest extends TestCase
{
    protected App $api;

    public function setUp() : void
    {
        $_ENV['PHP_ENVIRONMENT'] = 'TEST';
        $this->api = (new Api())->get();
    }

    public function testUnknownRouteShouldReturn404() : void
    {
        $request = $this->prepareRequest();
        $this->api->getContainer()['request'] = $request;
        
        $response = $this->api->run(true);
        $responseStatus = $response->getStatusCode();

        $this->assertSame(404, $responseStatus);
    }

    private function prepareRequest(string $method = 'GET', string $uri = '/', array $requestBody = []) : Request
    {
        $environment = Environment::mock([
            'REQUEST_METHOD' => $method,
            'REQUEST_URI' => $uri,
        ]);

        if ($method === 'PATCH' ||
            $method === 'POST'
        ) {
            $environment = Environment::mock([
                'REQUEST_METHOD' => $method,
                'REQUEST_URI' => $uri,
                // Add required request header. Without it, request body ignored
                'CONTENT_TYPE' => 'application/json',
            ]);
        }

        $uri = Uri::createFromEnvironment($environment);
        $headers = Headers::createFromEnvironment($environment);
        $cookies = [];
        $serverParams = $environment->all();
        $body = new RequestBody();
        if (count($requestBody) > 0) {
            $requestBodyParsed = (string) json_encode($requestBody);
            $body->write($requestBodyParsed);
        }
        
        return new Request($method, $uri, $headers, $cookies, $serverParams, $body);
    }
}
