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
    $gzip = $input['gzip'] ?? false;
    $bzip = $input['bzip'] ?? false;

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

    if ($zipType) {
        $process = Process::fromShellCommandline('mysqldump -u "${:username}"  --password="${:password}" --databases "${:dbName}"  | "${:zipType}" --stdout >  "${:filename}" ');
        try {
            $process->mustRun(null, [
                'username' => $config->getUsername(),
                'password' => $config->getPassword(),
                'dbName' => $config->getName(),
                'zipType' => $zipType,
                'filename' => $filename,
            ]);

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
    } else {
        $response = [
            'status' => 'error',
            'error' => [
                'message' => 'Not provide any format',
            ],
        ];
    }

    return $response;
});
