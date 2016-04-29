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

use Imomushi\Worker\Segment\EchoStdout;

/**
 * Class EchoStdoutTest
 *
 * @package Imomushi\Worker\Tests
 */

class EchoStdoutTest extends \PHPUnit_Framework_TestCase
{
    /*
     * @vars
     */
    private $target;
    public function setUp()
    {
        $this -> target = new EchoStdout();

    }

    public function tearDown()
    {
    }

    public function testConstruct()
    {
        $this -> assertInstanceOf(
            'Imomushi\Worker\Segment\EchoStdout',
            $this -> target
        );
    }
}
