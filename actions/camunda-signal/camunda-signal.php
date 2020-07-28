<?php

require_once __DIR__ . '/../../autoload.php';

use GuzzleHttp\Client;
use Exo\Toolkit\Runner;

$run = Runner::run(function($request) {
    $url = $request['input']['url'];
    $name = $request['input']['name'];
    $variables = $request['input']['variables'] ?? [];

    $client = new Client(
        [
            'base_uri' => $url,
            'timeout' => 5.0,
            'http_errors' => true,
            'headers' => [
                'Content-type' => 'application/json',
                'Accept' => 'application/json',
            ],
        ]
    );

    // Construct camunda-specific key/value format
    $data = [];
    foreach ($variables as $k=>$v) {
        $variables[$k] = [
            'value' => $v,
        ];
    }

    $response = $client->post('/engine-rest/signal',
        [
            'json' => [
                'name' => $name,
                'variables' => $variables,
            ],
        ]
    );

    if ((200 != $response->getStatusCode()) && (204 != $response->getStatusCode())) {
        $body = $response->getBody();
        throw new RuntimeException('Camunda ' . $response->getStatusCode() . ': ' . $body);
    }

    return ['status' => 'OK'];
});
