<?php
/*
 * This file is part of Worker.
 *
 ** (c) 2016 -  Fumikazu FUjiwara
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Imomushi\Worker\Segment;

/**
 * Class EchoStdout
 *
 * @package Imomushi\Worker
 */
class EchoStdout
{
    /**
     * @var
     */
    public $stdOut;

    /**
     * Constructer
     */
    public function __construct()
    {
        $this -> stdOut = fopen('php://stdout', 'w');
    }

    public function execute($arguments)
    {
        $args = var_export($arguments, true);
        fprintf($this -> stdOut, "%s\n", $args);
        return $args;
    }
}
