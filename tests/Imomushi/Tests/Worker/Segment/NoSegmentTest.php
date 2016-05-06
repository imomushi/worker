<?php
/*
 * This file is part of Worker.
 *
 ** (c) 2016 - Fumikazu Fujiwara
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Imomushi\Tests\Worker\Segment;

use Imomushi\Worker\Segment\NoSegment;

/**
 * Class NoSegmentTest
 *
 * @package Imomushi\Tests\Worker
 */

class NoSegmentTest extends \PHPUnit_Framework_TestCase
{
    /*
     * @vars
     */
    private $target;
    public function setUp()
    {
        $this -> target = new NoSegment();

    }

    public function tearDown()
    {
    }

    public function testConstruct()
    {
        $this -> assertInstanceOf(
            'Imomushi\Worker\Segment\NoSegment',
            $this -> target
        );
    }

    public function testExecute()
    {
        $this -> assertTrue(
            method_exists(
                $this -> target,
                'execute'
            )
        );
        $this -> assertEquals(
            $this -> target -> execute(null),
            'Imomushi\Worker\Segment\NoSegment'
        );
    }
}
