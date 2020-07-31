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
        echo "Creating database\n";
        $connector->create($config);
    }

    $zipType = null;
    if ($gzip) {
        $zipType = 'zcat';
    }
    if ($bzip) {
        $zipType = 'bunzip2';
    }

    if ($zipType) {
        $process = Process::fromShellCommandline('"${:zipType}" < "${:filename}" | mysql -u "${:username}"  --password="${:password}" "${:dbName}" ');

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
