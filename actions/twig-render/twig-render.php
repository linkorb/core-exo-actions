<?php

require_once __DIR__ . '/../../autoload.php';

use Exo\Toolkit\Runner;

$run = Runner::run(function($request) {
    $template = $request['input']['template'];
    $variables = $request['input']['variables'];

    $loader = new \Twig\Loader\ArrayLoader([
        'template' => $template,
    ]);
    $twig = new \Twig\Environment($loader);
    $result = $twig->render('template', $variables);

    $response = [
        'status' => 'OK',
        'output' => [
            'result' => $result
        ]
    ];

    return $response;
});