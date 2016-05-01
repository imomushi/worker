<?php
/*
 * This file is part of Worker.
 *
 ** (c) 2016 - Fumikazu Fujiwara
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Imomushi\Worker\Tests\Head;

/**
 * Class FileExtend
 *
 * @package Imomushi\Worker\Tests\Head
 */

class FileExtend extends \Imomushi\Worker\Head\File
{
    public function input()
    {
        return $this -> input;
    }
    public function log()
    {
        return $this -> log;
    }
    public function fh()
    {
        return $this -> fh;
    }
    public function body()
    {
        return $this -> body;
    }
    public function size($size = null)
    {
        return $this -> size = is_null($size) ? $this -> size : $size;
    }
    public function currentSize()
    {
        return $this -> currentSize;
    }
    public function open()
    {
        return parent::open();
    }
    public function close()
    {
        return parent::close();
    }
    public function changed()
    {
        return parent::changed();
    }
    public function stop()
    {
        $this -> stop = true;
    }
    public function getRequest()
    {
        return parent::getRequest();
    }
    public function logWrite()
    {
        return parent::logWrite();
    }
    public function sizeSetFromLog()
    {
        return parent::sizeSetFromLog();
    }
}
