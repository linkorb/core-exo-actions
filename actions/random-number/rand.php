<?php

require_once __DIR__ . '/../../autoload.php';

use Exo\Toolkit\Runner;

$run = Runner::run(function($request) {
    $input = $request['input'];
    $min = $input['min'];
    $max = $input['max'];

    $result = rand($min, $max);
    $response = [
        'status' => 'OK',
        'output' => [
            'result' => (int)$result
        ]
    ];

    return $response;
});