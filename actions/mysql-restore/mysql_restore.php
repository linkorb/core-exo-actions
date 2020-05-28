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
        echo "Creating database\n";
        $connector->create($config);
    }

    $zipType = null;
    if ($gzip) {
        $zipType = 'zcat';
    }
    if ($bzip) {
        $zipType = 'bunzip2 < ';
    }

    $process = Process::fromShellCommandline($zipType.' '.$filename.' | mysql -u '.$config->getUsername().' --password='.$config->getPassword().'  '.$config->getName());
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
