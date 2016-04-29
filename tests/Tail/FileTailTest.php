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

use Imomushi\Worker\Tail\FileTail;

/**
 * Class FileTailTest
 *
 * @package Imomushi\Worker\Tests
 */

class FileTailTest extends \PHPUnit_Framework_TestCase
{
    /*
     * @vars
     */
    private $target;
    private $tmpFile;
    private $tmpTail;
    private $tail;
    public function setUp()
    {
        $this -> tmpTail = tempnam(sys_get_temp_dir(), 'Imomushi.Tail.FileTail');

        $this -> tail = new FileTail($this -> tmpTail);

    }

    public function tearDown()
    {
        unlink($this -> tmpTail);
    }

    public function testConstruct()
    {
        $this -> assertInstanceOf(
            'Imomushi\Worker\Tail\FileTail',
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
        $this -> assertNull(
            $this -> target -> respond()
        );
    }
}
