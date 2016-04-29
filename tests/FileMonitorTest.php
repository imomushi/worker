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
 *
 * @package Imomushi\Worker\Tests
 */

class FileMonitorTest extends \PHPUnit_Framework_TestCase
{
    /*
     * @vars
     */
    private $fileMonitor;
    private $tmpFile;
    public function setUp()
    {
        $this -> tmpFile = tempnam(sys_get_temp_dir(), 'Imomushi.');
        $this -> fileMonitor = new FileMonitor($this -> tmpFile);

    }

    public function tearDown()
    {
        unlink($this -> tmpFile);
    }

    public function testConstruct()
    {
        $this -> assertInstanceOf(
            'Imomushi\Worker\FileMonitor',
            $this -> fileMonitor
        );
    }

    public function testOpen()
    {
        $this -> assertTrue(
            method_exists(
                $this -> fileMonitor,
                'open'
            )
        );
        $this -> assertTrue(
            $this -> fileMonitor -> open()
        );
    }

    public function testClose()
    {
        $this -> assertTrue(
            method_exists(
                $this -> fileMonitor,
                'close'
            )
        );

        $this -> fileMonitor -> open();
        $this -> assertTrue(
            $this -> fileMonitor -> close()
        );
    }

    public function testChanged()
    {
        $this -> assertTrue(
            method_exists(
                $this -> fileMonitor,
                'changed'
            )
        );

        $this -> fileMonitor -> open();
        $this -> assertFalse(
            $this -> fileMonitor -> changed()
        );

        $tmp = tempnam(sys_get_temp_dir(), 'Imomushi.');
        $tmpFileMonitor = new FileMonitor($tmp);
        $tmpFileMonitor -> open();
        $tmpFileMonitor -> changed();

        $fh = fopen($tmp, 'w');
        fwrite($fh, '{"pipeline_id": "hogehoge", "segment_id": 1,'.
            ' function":"func1", "args": {"arg1": 1, "arg2": 2}}'."\n");
        fclose($fh);

        $this -> assertTrue(
            $tmpFileMonitor -> changed()
        );

        $tmpFileMonitor -> close();
        unlink($tmp);
    }

    public function testGetInput()
    {
        $this -> assertTrue(
            method_exists(
                $this -> fileMonitor,
                'getInput'
            )
        );
        $input = $this -> fileMonitor -> getInput();
        $this -> assertEmpty(
            $input
        );

        $fh = fopen($this -> tmpFile, 'w');
        fwrite($fh, '{"pipeline_id": "hogehoge", "segment_id": 1,'.
            '"function":"func1", "args": {"arg1": 1, "arg2": 2}}'.PHP_EOL);
        fclose($fh);

        $input = $this -> fileMonitor -> getInput();
        $this -> assertNotEmpty(
            $input
        );
        $this -> assertEquals(
            1,
            count($input)
        );

        $input = $this -> fileMonitor -> getInput();
        $this -> assertEmpty(
            $input
        );

        $fh = fopen($this -> tmpFile, 'a');
        fwrite($fh, '{"pipeline_id": "hogehoge", "segment_id": 2,'.
            '"function":"func1", "args": {"arg1": 1, "arg2": 2}}'.PHP_EOL);
        fwrite($fh, '{"pipeline_id": "hogehoge", "segment_id": 3,'.
            '"function":"func1", "args": {"arg1": 1, "arg2": 2}}'.PHP_EOL);
        fwrite($fh, '{"pipeline_id": "hogehoge", "segment_id": 4,'.
            '"function":"func1", "args": {"arg1": 1, "arg2": 2}}'.PHP_EOL);
        fwrite($fh, '{"pipeline_id": "hogehoge", "segment_id": 5,'.
            '"function":"func1", "args": {"arg1": 1, "arg2": 2}}'.PHP_EOL);
        fwrite($fh, '{"pipeline_id": "hogehoge", "segment_id": 6,'.
            '"function":"func1", "args": {"arg1": 1, "arg2": 2}}'.PHP_EOL);
        fclose($fh);
        $input = $this -> fileMonitor -> getInput();
        $this -> assertNotEmpty(
            $input
        );
        $this -> assertEquals(
            5,
            count($input)
        );
    }
}
