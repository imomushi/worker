<?php
require __DIR__.'/../vendor/autoload.php';

$loop = React\EventLoop\Factory::create();
$file  = "/tmp/input.txt";

$size = 0;
$line = 0;
$timer = $loop->addPeriodicTimer(0, function() use (&$size,$file) {
    clearstatcache();
    $currentSize = filesize($file);
    if ($size == $currentSize) {
        return;
    }

    $fh = fopen($file, "r");
    fseek($fh, $size);

    $data = "";
    while ($d = fgets($fh)) {
        $data .= $d;
    }
    $lines = split(PHP_EOL,$data); 
    var_dump($lines); 

    fclose($fh);
    $size = $currentSize;

});

$loop->run();
