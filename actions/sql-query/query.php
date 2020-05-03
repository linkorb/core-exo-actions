<?php

require_once __DIR__ . '/../../autoload.php';

use Connector\Connector;
use Exo\Toolkit\Runner;

$run = Runner::run(function($request) {
    $sql = $request['input']['sql'];
    $dsn = $request['input']['dsn'];

    $connector = new Connector();
    $config = $connector->getConfig($dsn);

    $pdo = $connector->getPdo($config);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $response = [
        'status' => 'OK',
        'output' => [
            'rows' => $rows
        ]
    ];

    return $response;
});