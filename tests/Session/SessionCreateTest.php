<?php

    namespace Test\Session;

    use \PHPUnit\Framework\TestCase;
    use \Slim\App;
    use Api\Api;
    use Test\ApiTestHelper as Helper;

class SessionCreateTest extends TestCase
{
    protected App $api;
    protected Helper $helper;
    protected string $testUsername;

    public function setUp() : void
    {
        $_ENV['PHP_ENVIRONMENT'] = 'TEST';
        $this->api = (new Api())->get();
        $this->helper = new Helper();
        $this->testUsername = 'test' . time();
    }

    public function testRequestWithInvalidInputShouldReturn400() : void
    {
        $errorMessage = 'Invalid username and password combination.';
        $requestsIncomplete = [
            [
                'body' => [
                    'username' => '',
                    'passsword' => '',
                ],
            ], [
                'body' => [
                    'username' => 'test' . time(),
                ],
            ], [
                'body' => [
                    'password' => 'password',
                ],
            ], [
                'body' => [
                    'username' => 'user' . time(),
                    'passsword' => 'pass' . time(),
                ],
            ],
        ];
        foreach ($requestsIncomplete as $incomplete) {
            $request = $this->helper->prepareRequest('POST', '/session', $incomplete['body']);
            $this->api->getContainer()['request'] = $request;
            
            $response = $this->api->run(true);
            $responseBody = json_decode((string)$response->getBody(), true);
            $responseStatus = $response->getStatusCode();
    
            $this->assertSame(400, $responseStatus);
            $this->assertArrayHasKey('error_message', $responseBody);
            $this->assertSame($errorMessage, $responseBody['error_message']);
        }
    }

    public function testRequestWithValidInputShouldReturn200() : void
    {
        $credentials = [
            'username' => $_ENV['TEST_USERNAME'],
            'password' => $_ENV['TEST_PASSWORD'],
        ];
        $request = $this->helper->prepareRequest('POST', '/session', $credentials);
        $this->api->getContainer()['request'] = $request;
        
        $response = $this->api->run(true);
        $responseBody = json_decode((string)$response->getBody(), true);
        $responseCookies = $this->helper->parseResponseCookies($response);
        $responseStatus = $response->getStatusCode();

        $this->assertSame(200, $responseStatus);
        $this->assertArrayHasKey('header', $responseCookies);
        $this->assertArrayHasKey('payload', $responseCookies);
        $this->assertArrayHasKey('signature', $responseCookies);
    }
}
