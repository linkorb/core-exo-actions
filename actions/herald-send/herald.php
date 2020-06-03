<?php

require_once __DIR__.'/../../autoload.php';

use Exo\Toolkit\Runner;
use Herald\Client\Client as HeraldClient;
use Herald\Client\Message as HeraldMessage;

$run = Runner::run(function ($request) {
    $dsn = $request['input']['dsn'];
    $to = $request['input']['to'];
    $template = $request['input']['template'];
    $data = $request['input']['data'] ?? null;

    $herald = HeraldClient::fromDsn($dsn);

    if (!$herald->templateExists($template)) {
        return $response = [
            'status' => 'error',
            'error' => [
                'message' => 'Template '.$template.' not found',
            ],
        ];
    }

    $toArray = array_map('trim', explode(',', $to));

    foreach ($toArray as $toKey => $toAddress) {
        $message = new HeraldMessage();
        $message->setTemplate($template)
            ->setToAddress($toAddress);

        if ($data) {
            foreach ($data as $key => $value) {
                $message->setData(trim($key), trim($value));
            }
        }
        $herald->send($message);
    }

    return [
        'status' => 'OK',
    ];
});
