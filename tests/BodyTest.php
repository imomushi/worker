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
    public function setUp()
    {
        $this -> target = new Body();

    }

    public function tearDown()
    {
    }

    public function testConstruct()
    {
        $this -> assertInstanceOf(
            'Imomushi\Worker\Body',
            $this -> target
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
            '"function":"EchoStdout", "args": {"arg1": 1, "arg2": 2}}'
        );
        $this -> assertTrue(
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
