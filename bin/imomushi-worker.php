#!/usr/bin/env php
<?php
require __DIR__.'/../vendor/autoload.php';
use Imomushi\Worker\Head\FileHead;

$fileHead  = new FileHead("/tmp/input.txt");
while(true){
    $input = $fileHead->getInput();
    if (0 != count($input)) {
        print_r($input);
    }
}
