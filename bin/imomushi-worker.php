#!/usr/bin/env php
<?php
require __DIR__.'/../vendor/autoload.php';
use Imomushi\Worker\Head\FileHead;

$fileHead  = new FileHead("/tmp/input.txt");
$fileHead -> run();
