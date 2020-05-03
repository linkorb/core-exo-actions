<?php

require_once __DIR__ . '/../../autoload.php';

use Exo\Toolkit\Runner;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;

$run = Runner::run(function($request) {
    $to = $request['input']['to'];
    $subject = $request['input']['subject'] ?? '(No subject)';
    $body = $request['input']['body'] ?? 'No body content';

    $dsn = $request['input']['dsn'];
    $from = $request['input']['from'];

    $email = (new Email())
        ->from($from)
        ->to($to)
        ->subject($subject)
        ->text($body)
    ;

    $transport = Transport::fromDsn($dsn);
    $mailer = new Mailer($transport);
    $mailer->send($email);

    return [
        'status' => 'OK',
    ];
});
