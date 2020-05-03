<?php

require_once __DIR__ . '/../../autoload.php';

use Exo\Toolkit\Runner;

$run = Runner::run(function($request) {
    $input = $request['input'];
    $greeting = $input['greeting'];
    $name = $input['name'];
    $color = getenv('EXO__EXAMPLE__COLOR') ?? 'undefined';

    $text = $greeting . ', ' . $name . '! (' . $color . ')';
    return [
        'status' => 'OK',
        'output' => [
            'text' => $text
        ]
    ];
});
