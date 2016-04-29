#!/usr/bin/env php
<?php
require __DIR__.'/../vendor/autoload.php';
use Imomushi\Worker\FileMonitor;

$fileMonitor  = new FileMonitor("/tmp/input.txt");
while(true){
    $input = $fileMonitor->getInput();
    if (0 != count($input)) {
        print_r($input);
    }
}
