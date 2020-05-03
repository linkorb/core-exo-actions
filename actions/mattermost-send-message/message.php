<?php

require_once __DIR__ . '/../../autoload.php';

use Exo\Utils;
use GuzzleHttp\Client;
use ThibaudDauce\Mattermost\Mattermost;
use ThibaudDauce\Mattermost\Message;
use ThibaudDauce\Mattermost\Attachment;
use Exo\Toolkit\Runner;

$run = Runner::run(function($request) {
    $input = $request['input'];
    $url = $input['url'];
    $channel = $input['channel'];
    $text = $input['text'];

    $mattermost = new Mattermost(new Client);

    $message = (new Message)
        ->text($text)
        ->channel($channel)
    ;

    $mattermost->send($message, $url);
        
    $response = [
        'status' => 'OK'
    ];
    return $response;
});