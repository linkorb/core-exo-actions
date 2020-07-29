<?php

use Exo\Toolkit\Runner;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

require_once __DIR__.'/../../autoload.php';

$run = Runner::run(function ($request) {
    $input = $request['input'];
    $directory = $input['directory'];
    $message = $input['message'];

    $process = Process::fromShellCommandline('git add . && git commit -m "${:message}" ');
    $process->setWorkingDirectory($directory);
    $process->run(null, ['message' => $message]);

    if (!$process->isSuccessful()) {
        throw new ProcessFailedException($process);
    }

    return $response = [
                'status' => 'OK',
            ];
});
