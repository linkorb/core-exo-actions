<?php

use Exo\Toolkit\Runner;
use Telegram\Bot\Api;

require_once __DIR__.'/../../autoload.php';

$run = Runner::run(function ($request) {
    $input = $request['input'];

    $token = $input['token'];
    $channel = $input['channel'];
    $text = $input['text'];

    $telegram = new Api($token);

    $telegram->sendMessage(
        [
            'chat_id' => $channel ,
            'parse_mode' => 'markdown',
            'text' => 'Hi @BotFather  send message from api',
            'disable_web_page_preview' => 'false',
        ]
    );

    $response = [
        'status' => 'OK',
    ];

    return $response;
});
