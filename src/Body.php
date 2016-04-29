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
        $segment = 'NoSegment';
        $args    = null;
        if (is_object($request)) {
            if (property_exists($request, 'segment')) {
                $segment = $request -> segment;
            }
            if (property_exists($request, 'args')) {
                $args = $request -> args;
            }
        }
        $target = $this -> create($segment);
        $target -> execute($args);
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
