<?php

    namespace Test;

    use \PHPUnit\Framework\TestCase;
    use \Slim\App;
    use Api\Api;
    use Test\ApiTestHelper as Helper;

class UserTest extends TestCase
{
    protected App $api;
    protected Helper $helper;

    public function setUp() : void
    {
        $_ENV['PHP_ENVIRONMENT'] = 'TEST';
        $this->api = (new Api())->get();
        $this->helper = new Helper();
    }

    public function testIncompleteRequestsShouldReturn400WithErrorMessage() : void
    {
        $requestsIncomplete = [
            [
                'body' => [
                    'username' => '',
                    'passsword' => '',
                ],
                'error_message' => 'Missing input: username, password',
            ], [
                'body' => [
                    'username' => 'test' . time(),
                ],
                'error_message' => 'Missing input: password',
            ], [
                'body' => [
                    'password' => 'password',
                ],
                'error_message' => 'Missing input: username',
            ],
        ];
        foreach ($requestsIncomplete as $incomplete) {
            $request = $this->helper->prepareRequest('POST', '/user', $incomplete['body']);
            $this->api->getContainer()['request'] = $request;
            
            $response = $this->api->run(true);
            $responseBody = json_decode((string)$response->getBody(), true);
            $responseStatus = $response->getStatusCode();
    
            $this->assertSame(400, $responseStatus);
            $this->assertArrayHasKey('error_message', $responseBody);
            $this->assertSame($incomplete['error_message'], $responseBody['error_message']);
        }
    }

    public function testCompleteRequestsShouldReturn200WithUsername() : void
    {
        $requestComplete = [
            'username' => 'test' . time(),
            'password' => 'password',
        ];
        $request = $this->helper->prepareRequest('POST', '/user', $requestComplete);
        $this->api->getContainer()['request'] = $request;
        
        $response = $this->api->run(true);
        $responseBody = json_decode((string)$response->getBody(), true);
        $responseStatus = $response->getStatusCode();

        $this->assertSame(200, $responseStatus);
        $this->assertArrayHasKey('username', $responseBody);
        $this->assertSame($requestComplete['username'], $responseBody['username']);
    }
}
