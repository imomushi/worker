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
 * Class File
 *
 * @package Imomushi\Worker
 */
class File
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
        file_put_contents($this->file, json_encode($response).PHP_EOL, FILE_APPEND | LOCK_EX);
    }
}
