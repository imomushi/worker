<?php
/*
 * This file is part of Worker.
 *
 ** (c) 2016 - Fumikazu Fujiwara
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Imomushi\Tests;

use Imomushi\Worker;

/**
 * Class WorkerTest
 *
 * @package Imomushi\Tests
 */

class WorkerTest extends \PHPUnit_Framework_TestCase
{
    /*
     * @vars
     */
    private $target;
    private $tmpFile;
    public function setUp()
    {
        $this -> tmpFile = tempnam(sys_get_temp_dir(), 'imomushi.worker');
        $this -> target = new Worker(new \Imomushi\Worker\Tail\File($this -> tmpFile));
    }

    public function tearDown()
    {
        unlink($this -> tmpFile);
    }

    public function testConstruct()
    {
        $this -> assertInstanceOf(
            'Imomushi\Worker',
            $this -> target
        );
        $this -> assertInstanceOf(
            'Imomushi\Worker\Tail\File',
            $this -> target -> body -> tail
        );
    }
}
