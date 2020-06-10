<?php

    namespace Test\User;

    use \PHPUnit\Framework\TestCase;
    use \Slim\App;
    use Api\Api;
    use Test\ApiTestHelper as Helper;

class UserUpdateAndDeleteTest extends TestCase
{
    protected App $api;
    protected Helper $helper;
    protected string $testUsername;

    public function setUp() : void
    {
        $_ENV['PHP_ENVIRONMENT'] = 'TEST';
        $this->api = (new Api())->get();
        $this->helper = new Helper();
        $this->testUsername = 'test' . date('Ymdhi');
    }
    
    public function testUpdateRequestWithInvalidUsernameAndMissingInputShouldReturn404() : void
    {
        $request = $this->helper->prepareRequest('PATCH', '/user/' . $this->testUsername . 'x');
        $this->api->getContainer()['request'] = $request;
        
        $response = $this->api->run(true);
        $responseBody = json_decode((string)$response->getBody(), true);
        $responseStatus = $response->getStatusCode();

        $this->assertSame(404, $responseStatus);
        $this->assertArrayHasKey('error_message', $responseBody);
        $this->assertSame('Username not found.', $responseBody['error_message']);
    }
    
    public function testUpdateRequestWithInvalidUsernameButValidInputShouldReturn404() : void
    {
        $request = $this->helper->prepareRequest('PATCH', '/user/' . $this->testUsername . 'x', [
            'password' => 'password',
        ]);
        $this->api->getContainer()['request'] = $request;
        
        $response = $this->api->run(true);
        $responseBody = json_decode((string)$response->getBody(), true);
        $responseStatus = $response->getStatusCode();

        $this->assertSame(404, $responseStatus);
        $this->assertArrayHasKey('error_message', $responseBody);
        $this->assertSame('Username not found.', $responseBody['error_message']);
    }
    
    public function testUpdateRequestWithMissingInputButValidUsernameShouldReturn400() : void
    {
        $request = $this->helper->prepareRequest('PATCH', '/user/' . $this->testUsername);
        $this->api->getContainer()['request'] = $request;
        
        $response = $this->api->run(true);
        $responseBody = json_decode((string)$response->getBody(), true);
        $responseStatus = $response->getStatusCode();

        $this->assertSame(400, $responseStatus);
        $this->assertArrayHasKey('error_message', $responseBody);
        $this->assertSame('Missing input: password', $responseBody['error_message']);
    }
    
    public function testUpdateRequestWithValidUsernameAndValidInputShouldReturn200() : void
    {
        $request = $this->helper->prepareRequest('PATCH', '/user/' . $this->testUsername, [
            'password' => 'password',
        ]);
        $this->api->getContainer()['request'] = $request;
        
        $response = $this->api->run(true);
        $responseStatus = $response->getStatusCode();

        $this->assertSame(200, $responseStatus);
    }

    public function testDeleteRequestForInvalidRecordShouldReturn404() : void
    {
        $request = $this->helper->prepareRequest('DELETE', '/user/' . $this->testUsername . 'x');
        $this->api->getContainer()['request'] = $request;
        
        $response = $this->api->run(true);
        $responseBody = json_decode((string)$response->getBody(), true);
        $responseStatus = $response->getStatusCode();

        $this->assertSame(404, $responseStatus);
        $this->assertArrayHasKey('error_message', $responseBody);
        $this->assertSame('Username not found.', $responseBody['error_message']);
    }

    public function testDeleteRequestForValidRecordShouldReturn200() : void
    {
        $request = $this->helper->prepareRequest('DELETE', '/user/' . $this->testUsername);
        $this->api->getContainer()['request'] = $request;
        
        $response = $this->api->run(true);
        $responseStatus = $response->getStatusCode();

        $this->assertSame(200, $responseStatus);
    }
}
