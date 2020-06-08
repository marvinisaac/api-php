<?php

    namespace Test;

    use \PHPUnit\Framework\TestCase;
    use \Slim\App;
    use Api\Api;
    use Test\ApiTestHelper as Helper;

class ApiTest extends TestCase
{
    protected App $api;
    protected Helper $helper;

    public function setUp() : void
    {
        $_ENV['PHP_ENVIRONMENT'] = 'TEST';
        $this->api = (new Api())->get();
        $this->helper = new Helper();
    }

    public function testUnknownRouteShouldReturn404() : void
    {
        $request = $this->helper->prepareRequest();
        $this->api->getContainer()['request'] = $request;
        
        $response = $this->api->run(true);
        $responseStatus = $response->getStatusCode();

        $this->assertSame(404, $responseStatus);
    }
}
