<?php

$loader = require_once __DIR__.'/vendor/autoload.php';
if (class_exists('AutoTune\Tuner')) {
    \AutoTune\Tuner::init($loader);
}