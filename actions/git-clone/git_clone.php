<?php

use Exo\Toolkit\Runner;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

require_once __DIR__.'/../../autoload.php';

$run = Runner::run(function ($request) {
    $input = $request['input'];
    $url = $input['url'];
    $directory = $input['directory'];

    if (!is_dir($directory)) {
        $process = new Process('git clone '.$url.' '.$directory);
    } else {
        $process = new Process('git pull ');
        $process->setWorkingDirectory($directory);
    }
    $process->run();

    if (!$process->isSuccessful()) {
        throw new ProcessFailedException($process);
    }

    return $response = [
                'status' => 'OK',
            ];
});
