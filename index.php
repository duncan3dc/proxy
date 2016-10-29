<?php

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

require __DIR__ . "/vendor/autoload.php";

$client = new Client([
    "http_errors"   =>  false,
]);

$url = "https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];

$request = new Request($_SERVER["REQUEST_METHOD"], $url, [
    "User-Agent"    =>  $_SERVER["HTTP_USER_AGENT"],
]);

$response = $client->send($request);

http_response_code($response->getStatusCode());
header("Content-Type: " . $response->getHeader("content-type")[0]);
echo $response->getBody();
