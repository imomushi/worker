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

    public function respond($pipelineId, $segmentId, $result)
    {
        $response = new \stdClass();
        $response -> pipeline_id = $pipelineId;
        $response -> segment_id = $segmentId;
        $response -> result  = $result;
        error_log(json_encode($response).PHP_EOL, 3, $this -> file);
    }
}
