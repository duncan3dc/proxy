<?php

namespace duncan3dc\Proxy;

use duncan3dc\Guzzle\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class App
{
    private $client;


    public function __construct()
    {
        $this->client = new Client([
            "http_errors"   =>  false,
        ]);
    }


    public function run(): void
    {
        $request = $this->createRequest();

        $response = $this->getResponse($request);

        $this->respond($response);
    }


    private function createRequest(): RequestInterface
    {
        $url = "https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];

        $request = Request::make($_SERVER["REQUEST_METHOD"], $url, [
            "headers"       =>  [
                "User-Agent"    =>  $_SERVER["HTTP_USER_AGENT"],
            ],
            "form_params"   =>  $_POST,
        ]);

        return $request;
    }


    private function getResponse(RequestInterface $request): ResponseInterface
    {
        try {
            $response = $this->client->send($request);
        } catch (\Throwable $e) {
            $response = $this->createErrorResponse($e);
        }

        return $response;
    }


    private function createErrorResponse(\Throwable $e): ResponseInterface
    {
        return new Response(500, ["Content-Type" => "text/plain"], $e->getMessage());
    }


    private function respond(ResponseInterface $response): void
    {
        http_response_code($response->getStatusCode());
        header("Content-Type: " . $response->getHeader("content-type")[0]);
        echo $response->getBody();
    }
}
