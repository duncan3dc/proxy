<?php

namespace duncan3dc\ProxyTests;

use duncan3dc\Guzzle\Request;
use duncan3dc\ObjectIntruder\Intruder;
use duncan3dc\Proxy\App;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class AppTest extends TestCase
{
    private $app;

    public function setUp()
    {
        $app = new App;
        $this->app = new Intruder($app);
    }


    public function tearDown()
    {
        Handlers::clear();
    }


    public function testCreateRequest()
    {
        $_SERVER["HTTP_HOST"] = "example.com";
        $_SERVER["REQUEST_URI"] = "/index.php?test=1";
        $_SERVER["REQUEST_METHOD"] = "POST";
        $_SERVER["HTTP_USER_AGENT"] = "php/phpunit";

        $_POST = [
            "var_one"   =>  1,
            "var_two"   =>  2,
        ];

        $request = $this->app->createRequest();

        $this->assertSame("php/phpunit", $request->getHeader("user-agent")[0]);
        $this->assertSame("var_one=1&var_two=2", (string) $request->getBody());
    }


    public function testGetResponse()
    {
        $request = Request::make("GET", "https://google.com/");

        $response = $this->app->getResponse($request);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertStringStartsWith("text/html", $response->getHeader("content-type")[0]);
    }


    public function testGetResponseError()
    {
        $request = Request::make("GET", "htt://nope/");

        $response = $this->app->getResponse($request);

        $this->assertSame(500, $response->getStatusCode());
        $this->assertSame("text/plain", $response->getHeader("content-type")[0]);
        $this->assertStringStartsWith("cURL error", (string) $response->getBody());
    }


    public function testCreateErrorResponse()
    {
        $response = $this->app->createErrorResponse(new \Exception("Test Message"));

        $this->assertSame(500, $response->getStatusCode());
        $this->assertSame("text/plain", $response->getHeader("content-type")[0]);
        $this->assertSame("Test Message", (string) $response->getBody());
    }


    public function testRespond()
    {
        $response = new Response(404, ["Content-Type" => "text/plain"], "Not Found");

        $header = "";
        Handlers::handle("header", function ($param) use (&$header) {
            $header = $param;
        });
        $this->expectOutputString("Not Found");

        $this->app->respond($response);

        $this->assertSame("Content-Type: text/plain", $header);
        $this->assertSame(404, http_response_code());
    }
}
