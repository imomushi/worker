<?php
/*
 * This file is part of Worker.
 *
 ** (c) 2016 -  Fumikazu FUjiwara
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Imomushi\Worker;

/**
 * Class FileMonitor
 *
 * @package Imomushi\Worker
 */
class FileMonitor
{
    /**
     * @var
     */
    private $file;
    private $fh;
    private $size = 0;
    private $currentSize = 0;

    /**
     * Constructer
     */
    public function __construct($file)
    {
        $this -> file = $file;
    }

    public function open()
    {
        return false != (
            $this -> fh = fopen($this -> file, 'r')
        );
    }

    public function close()
    {
        return fclose($this -> fh);
    }

    public function changed()
    {
        $size = $this -> size;

        clearstatcache();
        $fstat = fstat($this ->fh);
        $this -> currentSize = $fstat['size'];

        return $this -> size != $this -> currentSize;
    }

    public function getInput()
    {
        $this -> open();
        $lines = array();
        if ($this -> changed()) {
            fseek($this -> fh, $this -> size);
            $data = "";
            while ($d = fgets($this -> fh)) {
                $data .= $d;
            }
            $lines = array_map(
                'json_decode',
                array_filter(split(PHP_EOL, $data))
            );
            $this -> size = $this -> currentSize;
        }
        $this -> close();
        return $lines;
    }
}
