<?php

require_once __DIR__.'/../../autoload.php';

use Exo\Toolkit\Runner;
use MessageBird\Client;
use essageBird\Exceptions\{
    AuthenticateException,
    BalanceException
};
use MessageBird\Objects\Message;

$run = Runner::run(function ($request) {
    $input = $request['input'];

    $originator = $input['originator'];
    $recipients = $input['recipients'];
    $text = $input['text'];
    $key = $input['key'];

    $messageBird = new Client($key);

    $message = new Message();
    $message->originator = $originator;
    $message->recipients = $recipients;
    $message->body = $text;

    try {
        $messageResult = $messageBird->messages->create($message);

        $response = [
            'status' => 'OK',
        ];
    } catch (AuthenticateException $e) {
        // That means that your accessKey is unknown

        $response = [
            'status' => 'error',
            'error' => [
                'message' => 'wrong login',
            ],
        ];
    } catch (BalanceException $e) {
        // That means that you are out of credits, so do something about it.
        $response = [
            'status' => 'ERROR',
            'error' => [
                'message' => 'no balance',
            ],
        ];
    } catch (Exception $e) {
        $response = [
            'status' => 'ERROR',
            'error' => [
                'message' => $e->getMessage(),
            ],
        ];
    }

    return $response;
});
