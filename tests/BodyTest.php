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
    private $body;
    public function setUp()
    {
        $this -> body = new Body();

    }

    public function tearDown()
    {
    }

    public function testConstruct()
    {
        $this -> assertInstanceOf(
            'Imomushi\Worker\Body',
            $this -> body
        );
    }
}
