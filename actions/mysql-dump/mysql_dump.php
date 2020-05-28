<?php

require_once __DIR__.'/../../autoload.php';

use Connector\Connector;
use Exo\Toolkit\Runner;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

$run = Runner::run(function ($request) {
    $input = $request['input'];
    $filename = $input['filename'];
    $dsn = $input['dsn'];
    $gzip = $input['gzip'];
    $bzip = $input['bzip'];

    $connector = new Connector();

    $config = $connector->getConfig($dsn);
    if (!$connector->exists($config)) {
        return $response = [
            'status' => 'error',
            'error' => [
                'message' => 'Fail to connect database',
            ],
        ];
    }

    $zipType = null;
    if ($gzip) {
        $zipType = 'gzip';
    }
    if ($bzip) {
        $zipType = 'bzip2';
    }

    $process = Process::fromShellCommandline('mysqldump -u '.$config->getUsername().' --password='.$config->getPassword().' --databases '.$config->getName().'  | '.$zipType.' >  '.$filename);
    try {
        $process->mustRun();
        echo $process->getOutput();
        $response = [
            'status' => 'OK',
        ];
    } catch (ProcessFailedException $exception) {
        $response = [
            'status' => 'error',
            'error' => [
                'message' => $exception->getMessage(),
            ],
        ];
    }

    return $response;
});
