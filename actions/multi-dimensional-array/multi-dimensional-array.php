<?php

require_once __DIR__ . '/../../autoload.php';

use Exo\Toolkit\Runner;

$run = Runner::run(function($request) {
    $input = $request['input'];
    $color = $input['color'] ?? getenv('EXO__EXAMPLE__COLOR') ?? 'undefined';
    $attributes = $input['attributes'] ?? [];

    $text = 'Color: ' . $color . ', Attributes: ' . json_encode($attributes, JSON_UNESCAPED_SLASHES);
    return [
        'status' => 'OK',
        'output' => [
            'text' => $text
        ]
    ];
});
