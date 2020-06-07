<?php

    namespace Test\User;

    use \PHPUnit\Framework\TestCase;
    use \Slim\App;
    use Api\Api;
    use Test\ApiTestHelper as Helper;

class UserDeleteTest extends TestCase
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

    public function testRequestForInvalidRecordShouldReturn404() : void
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

    public function testRequestForValidRecordShouldReturn200() : void
    {
        $request = $this->helper->prepareRequest('DELETE', '/user/' . $this->testUsername);
        $this->api->getContainer()['request'] = $request;
        
        $response = $this->api->run(true);
        $responseBody = json_decode((string)$response->getBody(), true);
        $responseStatus = $response->getStatusCode();

        $this->assertSame(200, $responseStatus);
        $this->assertArrayHasKey('username', $responseBody);
        $this->assertArrayHasKey('created_at', $responseBody);
    }
}
