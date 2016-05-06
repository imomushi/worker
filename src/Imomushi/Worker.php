<?php
/*
 * This file is part of Worker.
 *
 ** (c) 2016 -  Fumikazu FUjiwara
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Imomushi;

use \Imomushi\Worker\Body;

/**
 * Class Worker
 *
 * @package Imomushi;
 */
class Worker
{
    /**
     * @var
     */
    public $body;

    /**
     * Constructer
     */
    public function __construct($tail)
    {
        $this -> body = new Body($tail);
    }
}
