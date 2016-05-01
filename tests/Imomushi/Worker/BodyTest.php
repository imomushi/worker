<?php
/*
 * This file is part of Worker.
 *
 ** (c) 2016 - Fumikazu Fujiwara
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Imomushi\Worker\Tests;

use Imomushi\Worker\Body;

/**
 * Class BodyTest
 *
 * @package Imomushi\Worker\Tests
 */

class BodyTest extends \PHPUnit_Framework_TestCase
{
    /*
     * @vars
     */
    private $target;
    private $tmpFile;
    public function setUp()
    {
        $this -> tmpFile = tempnam(sys_get_temp_dir(), 'Imomushi.Body');
        $this -> target = new Body(new \Imomushi\Worker\Tail\FileTail($this -> tmpFile));
    }

    public function tearDown()
    {
        unlink($this -> tmpFile);
    }

    public function testConstruct()
    {
        $this -> assertInstanceOf(
            'Imomushi\Worker\Body',
            $this -> target
        );
        $this -> assertInstanceOf(
            'Imomushi\Worker\Tail\FileTail',
            $this -> target -> tail
        );
    }

    public function testDispatch()
    {
        $this -> assertTrue(
            method_exists(
                $this -> target,
                'dispatch'
            )
        );
        $request = json_decode(
            '{"pipeline_id": "hogehoge", "segment_id": 2,'.
            '"segment":"EchoStdout", "args": {"arg1": 1, "arg2": 2}}'
        );
        $this -> assertNull(
            $this -> target -> dispatch($request)
        );
        $request = json_decode(
            '{"pipeline_id": "hogehoge", "segment_id": 2,'.
            '"segment":"Null", "args": {"arg1": 1, "arg2": 2}}'
        );
        $this -> assertNull(
            $this -> target -> dispatch($request)
        );
    }

    public function testCreate()
    {
        $this -> assertTrue(
            method_exists(
                $this -> target,
                'create'
            )
        );
        $this -> assertInstanceOf(
            'Imomushi\Worker\Segment\EchoStdout',
            $this -> target -> create('EchoStdout')
        );
        $this -> assertInstanceOf(
            'Imomushi\Worker\Segment\NoSegment',
            $this -> target -> create('Arienai')
        );
    }
}
