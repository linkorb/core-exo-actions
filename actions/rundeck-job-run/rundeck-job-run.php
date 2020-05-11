<?php

require_once __DIR__ . '/../../autoload.php';

use Exo\Toolkit\Runner;
use GuzzleHttp\Client;

$run = Runner::run(function($request) {
    $input = $request['input'];
    $jobId = $input['job'];
    $dsn = $input['dsn'];
   
    $part = parse_url($dsn);
    
    $baseUrl = $part['scheme'] . '://' . $part['host'];
    $auth = $part['user'];
    if ($auth!='token') {
        return [
            'status' => 'ERROR',
            'error' => [
                [
                    'code' => 'AUTH_MUST_BE_TOKEN',
                    'message' => 'Only supports auth type `token` in username',
                ]
            ],
        ]; 
    }
    $token = $part['pass'] ?? null;

    if (!$token) {
        return [
            'status' => 'ERROR',
            'error' => [
                [
                    'code' => 'AUTH_TOKEN_REQUIRED',
                    'message' => 'Please provide a token in the DSN',
                ]
            ],
        ]; 
    }


    $client = new Client(
        [
            'base_uri' => $baseUrl,
            'timeout'  => 2.0,
            'http_errors' => false,
            'headers' => [
                'X-Rundeck-Auth-Token' => $token,
                'Content-type' => 'application/json',
                'Accept' => 'application/json',
            ]
        ]
    );
    $response = $client->post('/api/18/job/' . $jobId . '/run',
        [
            'json' => [
                'options' => [
                    'dbname' => 'n_0000',
                ],
            ],
        ]
    );
    $json = $response->getBody();
    $data = json_decode($json, true);
    if ($response->getStatusCode()!=200) {
        // fail
        return [
            'status' => 'ERROR',
            'error' => [
                [
                    'code' => $response->getStatusCode(),
                    'message' => ($data['message'] ?? null),
                ]
            ],
        ]; 
    }
    
    $executionId = $data['id'] ?? null;

    return [
        'status' => 'OK',
        'output' => [
            'executionId' => $executionId
        ]
    ];
});
