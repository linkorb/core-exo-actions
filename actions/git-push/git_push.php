<?php

use Exo\Toolkit\Runner;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

require_once __DIR__.'/../../autoload.php';

$run = Runner::run(function ($request) {
    $input = $request['input'];
    $directory = $input['directory'];

    $process = new Process('git push ');
    $process->setWorkingDirectory($directory);
    $process->run();

    if (!$process->isSuccessful()) {
        throw new ProcessFailedException($process);
    }

    return $response = [
                'status' => 'OK',
            ];
});
