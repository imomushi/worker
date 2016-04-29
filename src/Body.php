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
 * Class Body
 *
 * @package Imomushi\Worker
 */
class Body
{
    /**
     * @var
     */

    /**
     * Constructer
     */
    public function __construct()
    {
    }

    public function dispatch($request)
    {
        $target = $this -> create($request -> segment);
        $target -> execute($request -> args);
        return true;
    }

    public function create($target)
    {
        $target = '\Imomushi\Worker\Segment\\'.$target;
        if (!class_exists($target)) {
            return new \Imomushi\Worker\Segment\NoSegment();
        }

        return new $target();
    }
}
