<?php

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

require __DIR__ . "/vendor/autoload.php";

$client = new Client([
    "http_errors"   =>  false,
]);

$url = "https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];

$response = $client->request($_SERVER["REQUEST_METHOD"], $url, [
    "headers"       =>  [
        "User-Agent"    =>  $_SERVER["HTTP_USER_AGENT"],
    ],
    "form_params"   =>  $_POST,
]);

http_response_code($response->getStatusCode());
header("Content-Type: " . $response->getHeader("content-type")[0]);
echo $response->getBody();
