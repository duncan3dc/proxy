<?php

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

require __DIR__ . "/vendor/autoload.php";

$client = new Client;

$url = "https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];

$request = new Request($_SERVER["REQUEST_METHOD"], $url);

$response = $client->send($request);

http_response_code($response->getStatusCode());
echo $response->getBody();
