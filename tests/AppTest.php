<?php

namespace duncan3dc\ProxyTests;

use duncan3dc\ObjectIntruder\Intruder;
use duncan3dc\Proxy\App;
use PHPUnit\Framework\TestCase;

class AppTest extends TestCase
{
    private $app;

    public function setUp()
    {
        $app = new App;
        $this->app = new Intruder($app);
    }


    public function testCreateErrorResponse()
    {
        $response = $this->app->createErrorResponse(new \Exception("Test Message"));

        $this->assertSame(500, $response->getStatusCode());
        $this->assertSame("text/plain", $response->getHeader("content-type")[0]);
        $this->assertSame("Test Message", (string) $response->getBody());
    }
}
