<?php

require_once __DIR__.'/../../autoload.php';

use Exo\Toolkit\Runner;

$run = Runner::run(function ($request) {
    $input = $request['input'];

    $originator = $input['originator'];
    $recipients = $input['recipients'];
    $text = $input['text'];
    $key = $input['key'];

    $MessageBird = new \MessageBird\Client($key);

    $Message = new \MessageBird\Objects\Message();
    $Message->originator = $originator;
    $Message->recipients = $recipients;
    $Message->body = $text;



    $MessageList = $MessageBird->messages->getList(['offset' => 100, 'limit' => 30]);


       $response = [
            'status' => 'OK',
            'output' => [
                'message' => 'cool',
                'test' => $MessageList
            ],
        ];

     return $response;

    try {
        $MessageResult = $MessageBird->messages->create($Message);

        $response = [
            'status' => 'OK',
            'output' => [
                'message' => 'cool',
            ],
        ];
    } catch (\MessageBird\Exceptions\AuthenticateException $e) {
        // That means that your accessKey is unknown

        $response = [
            'status' => 'fail',
            'output' => [
                'message' => 'wrong login',
            ],
        ];
    } catch (\MessageBird\Exceptions\BalanceException $e) {
        // That means that you are out of credits, so do something about it.
        $response = [
            'status' => 'fail',
            'output' => [
                'message' => 'no balance',
            ],
        ];
    } catch (\Exception $e) {
        $response = [
            'status' => 'fail',
            'output' => [
                'message' => $e->getMessage(),
            ],
        ];
    }

    return $response;
});
