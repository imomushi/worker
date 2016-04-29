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
    private $tmpStdOut;
    public function setUp()
    {
        $this -> tmpStdOut = tempnam(sys_get_temp_dir(), 'Imomushi.Segment.EchoStdout.');
        $this -> target = new EchoStdout();

    }

    public function tearDown()
    {
        unlink($this -> tmpStdOut);
    }

    public function testConstruct()
    {
        $this -> assertInstanceOf(
            'Imomushi\Worker\Segment\EchoStdout',
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
        $arguments = json_decode(
            '{"arg1": 1, "arg2": 2}'
        );
        $this -> target -> stdOut = fopen($this -> tmpStdOut, 'wb');
        $this -> assertNull(
            $this -> target -> execute($arguments)
        );
        $actual = implode(PHP_EOL, array_map('trim', explode(PHP_EOL, file_get_contents($this -> tmpStdOut))));

        $this -> assertEquals(
            "stdClass::__set_state(array(\n".
            "'arg1' => 1,\n".
            "'arg2' => 2,\n".
            "))",
            $actual
        );
    }
}
