<?php

namespace duncan3dc\Proxy;

use GuzzleHttp\Client;
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
        $url = $this->generateUrl();

        $response = $this->client->request($_SERVER["REQUEST_METHOD"], $url, [
            "headers"       =>  [
                "User-Agent"    =>  $_SERVER["HTTP_USER_AGENT"],
            ],
            "form_params"   =>  $_POST,
        ]);

        $this->respond($response);
    }


    private function generateUrl(): string
    {
        return "https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
    }


    private function respond(ResponseInterface $response): void
    {
        http_response_code($response->getStatusCode());
        header("Content-Type: " . $response->getHeader("content-type")[0]);
        echo $response->getBody();
    }
}
