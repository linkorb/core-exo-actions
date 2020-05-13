<?php

require_once __DIR__ . '/../../autoload.php';

use GuzzleHttp\Client;
use Exo\Toolkit\Runner;

$run = Runner::run(function($request) {
    $url = $request['input']['url'];
    $method = $request['input']['method'] ?? 'GET';
    $body = $request['input']['body'] ?? null;
    $preProcess = $request['input']['preProcess'] ?? null;
    $headers = $request['input']['headers'];
    $postProcess = $request['input']['postProcess'] ?? null;
    $username = $request['input']['username'] ?? null;
    $password = $request['input']['password'] ?? null;

    switch ($preProcess) {
        case 'json':
            $body = json_encode($body, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
            break;
        case null:
            break;
        default:
            throw new RuntimeException("Unsupported preProcess option: " . $preProcess);
    }
    
    $client = new Client([]);
    $options = [
        'http_errors' => false,
    ];
    if ($body) {
        $options['body'] = $body;
    }
    if ($headers) {
        $options['headers'] = $headers;
    }
    if ($username && $password) {
        $options['auth'] = [$username, $password];
    }
    $res = $client->request($method, $url, $options);

    $body = (string)$res->getBody();
    switch ($postProcess) {
        case 'json':
            $body = json_decode($body, true);
            break;
        case null:
            break;
        default:
            throw new RuntimeException("Unsupported postProcess option: " . $postProcess);
    }
    $status = 'ERROR';
    if (($res->getStatusCode()>=200) && ($res->getStatusCode()<300)) {
        $status = 'OK';
    }

    $response = [
        'status' => $status,
        'output' => [
            'statusCode' => $res->getStatusCode(),
            'body' => $body,
        ]
    ];

    return $response;
});