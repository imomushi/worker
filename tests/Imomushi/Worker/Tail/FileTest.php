<?php
/*
 * This file is part of Worker.
 *
 ** (c) 2016 - Fumikazu Fujiwara
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Imomushi\Worker\Tests\Tail;

use Imomushi\Worker\Tail\File;

/**
 * Class FileTest
 *
 * @package Imomushi\Worker\Tests
 */

class FileTest extends \PHPUnit_Framework_TestCase
{
    /*
     * @vars
     */
    private $target;
    private $tmpTail;
    public function setUp()
    {
        $this -> tmpTail = tempnam(sys_get_temp_dir(), 'Imomushi.Tail.File');

        $this -> target = new File($this -> tmpTail);

    }

    public function tearDown()
    {
        unlink($this -> tmpTail);
    }

    public function testConstruct()
    {
        $this -> assertInstanceOf(
            'Imomushi\Worker\Tail\File',
            $this -> target
        );
    }

    public function testRespond()
    {
        $this -> assertTrue(
            method_exists(
                $this -> target,
                'respond'
            )
        );
        $pipelineId = "hoge";
        $segmentId = 1;
        $result = new \stdClass();
        $result -> hotel = "Marumo";
        $result -> tel   = "090-xxxx-yyyy";
        $this -> assertNull(
            $this -> target -> respond($pipelineId, $segmentId, $result)
        );
        $expectation = new \stdClass();
        $expectation -> pipeline_id = $pipelineId;
        $expectation -> segment_id = $segmentId;
        $expectation -> result = $result;

        $actual = array_filter(array_map('json_decode', explode(PHP_EOL, file_get_contents($this -> tmpTail))));
        $this -> assertEquals(
            1,
            count($actual)
        );
        $this -> assertEquals(
            $expectation,
            $actual[0]
        );

        $this -> target -> respond(2, 2, $result);
        $this -> target -> respond(3, 3, $result);
        $actual = array_filter(array_map('json_decode', explode(PHP_EOL, file_get_contents($this -> tmpTail))));
        $this -> assertEquals(
            3,
            count($actual)
        );
    }
}
