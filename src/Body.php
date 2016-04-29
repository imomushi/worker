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
    public $tail;

    /**
     * Constructer
     */
    public function __construct($tail)
    {
        $this -> tail = $tail;
    }

    public function dispatch($request)
    {
        $segment = 'NoSegment';
        $args    = null;
        $pipelineId = null;
        $segmentId = null;
        if (is_object($request)) {
            if (property_exists($request, 'segment')) {
                $segment = $request -> segment;
            }
            if (property_exists($request, 'args')) {
                $args = $request -> args;
            }
            if (property_exists($request, 'pipeline_id')) {
                $pipelineId = $request -> pipeline_id;
            }
            if (property_exists($request, 'segment_id')) {
                $segmentId = $request -> segment_id;
            }
        }
        $target = $this -> create($segment);
        $result = $target -> execute($args);
        $this -> tail -> respond($pipelineId, $segmentId, $result);
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
