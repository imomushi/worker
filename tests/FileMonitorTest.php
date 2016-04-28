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

use Imomushi\Worker\FileMonitor;

/**
 * Class FileMonitorTest
 * @package Imomushi\Worker\Tests
 */

class FileMonitorTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {

    }

    public function tearDown()
    {

    }

    public function testConstruct()
    {
        $this -> assertInstanceOf(
            'Imomushi\Worker\FileMonitor',
            new FileMonitor()
        );
    }
}
