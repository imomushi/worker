<?php
/*
 * This file is part of Worker.
 *
 ** (c) 2016 -  Fumikazu FUjiwara
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Imomushi\Worker\Tail;

use Imomushi\Worker\Body;

/**
 * Class FileTail
 *
 * @package Imomushi\Worker
 */
class FileTail
{
    /**
     * @var
     */
    private $file;
    private $fh;

    public $inTest = false;

    /**
     * Constructer
     */
    public function __construct($file)
    {
        $this -> file = $file;
    }

    public function respond()
    {
    }
}
