<?php
/*
 * This file is part of Worker.
 *
 ** (c) 2016 - Fumikazu Fujiwara
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Imomushi\Worker\Tests\Head;

use Imomushi\Worker\Tail\FileTail;
use Imomushi\Worker\Tests\Head\FileHeadExtend;
use Imomushi\Worker\Body;

/**
 * Class FileHeadTest
 *
 * @package Imomushi\Worker\Tests\Head
 */


class FileHeadTest extends \PHPUnit_Framework_TestCase
{
    /*
     * @vars
     */
    private $target;
    private $tmpInput;
    private $tmpTail;
    private $tmpLog;
    private $tail;
    public function setUp()
    {
        $this -> tmpInput = tempnam(sys_get_temp_dir(), 'imomushi.worker.tests.head.file_head.input');
        $this -> tmpTail = tempnam(sys_get_temp_dir(), 'imomushi.worker.tests.head.file_head.tail');
        $this -> tmpLog = tempnam(sys_get_temp_dir(), 'imomushi.worker.tests.head.file_head.log');

        $this -> tail = new FileTail($this -> tmpTail);
        $this -> target = new FileHeadExtend([
            'input' => $this -> tmpInput,
            'tail'  => $this -> tail,
            'log'   => $this -> tmpLog
        ]);
        $this -> target -> once();

    }

    public function tearDown()
    {
        unlink($this -> tmpInput);
        unlink($this -> tmpTail);
        unlink($this -> tmpLog);
    }

    public function testConstruct()
    {
        $this -> assertInstanceOf(
            'Imomushi\Worker\Head\FileHead',
            $this -> target
        );
        $default = new FileHeadExtend();
        $this -> assertEquals(
            '/tmp/input.txt',
            $default -> input()
        );
        $this -> assertEquals(
            '/tmp/imomushi.worker.head.file_head.log',
            $default -> log()
        );
        $body =    $default -> body();
        $this -> assertInstanceOf(
            'Imomushi\Worker\Tail\FileTail',
            $body -> tail
        );
    }

    public function testOpen()
    {
        $this -> assertTrue(
            method_exists(
                $this -> target,
                'open'
            )
        );
        $this -> assertTrue(
            $this -> target -> open()
        );
    }

    public function testClose()
    {
        $this -> assertTrue(
            method_exists(
                $this -> target,
                'close'
            )
        );

        $this -> target -> open();
        $this -> assertTrue(
            $this -> target -> close()
        );
    }

    public function testChanged()
    {
        $this -> assertTrue(
            method_exists(
                $this -> target,
                'changed'
            )
        );

        $this -> target -> open();
        $this -> assertFalse(
            $this -> target -> changed()
        );

        $tmp = tempnam(sys_get_temp_dir(), 'Imomushi.');
        $tmpInputHead = new FileHeadExtend(['input' => $tmp, 'tail' => $this -> tail]);
        $tmpInputHead -> open();
        $tmpInputHead -> changed();

        $fh = fopen($tmp, 'w');
        fwrite(
            $fh,
            '{"pipeline_id": "hogehoge", "segment_id": 1,'.
            '"segment":"Imomushi", "args": {"arg1": 1, "arg2": 2}}'.PHP_EOL
        );
        fclose($fh);

        $this -> assertTrue(
            $tmpInputHead -> changed()
        );

        $tmpInputHead -> close();
        unlink($tmp);
    }

    public function testGetRequest()
    {
        $this -> assertTrue(
            method_exists(
                $this -> target,
                'getRequest'
            )
        );
        $request = $this -> target -> getRequest();
        $this -> assertEmpty(
            $request
        );

        $fh = fopen($this -> tmpInput, 'w');
        fwrite(
            $fh,
            '{"pipeline_id": "hogehoge", "segment_id": 1,'.
            '"segment":"Imomushi", "args": {"arg1": 1, "arg2": 2}}'.PHP_EOL
        );
        fclose($fh);

        $request = $this -> target -> getRequest();
        $this -> assertNotEmpty(
            $request
        );
        $this -> assertEquals(
            1,
            count($request)
        );

        $request = $this -> target -> getRequest();
        $this -> assertEmpty(
            $request
        );

        $fh = fopen($this -> tmpInput, 'a');
        fwrite(
            $fh,
            '{"pipeline_id": "hogehoge", "segment_id": 2,'.
            '"segment":"Imomushi", "args": {"arg1": 1, "arg2": 2}}'.PHP_EOL
        );
        fwrite(
            $fh,
            '{"pipeline_id": "hogehoge", "segment_id": 3,'.
            '"segment":"Imomushi", "args": {"arg1": 1, "arg2": 2}}'.PHP_EOL
        );
        fwrite(
            $fh,
            '{"pipeline_id": "hogehoge", "segment_id": 4,'.
            '"segment":"Imomushi", "args": {"arg1": 1, "arg2": 2}}'.PHP_EOL
        );
        fwrite(
            $fh,
            '{"pipeline_id": "hogehoge", "segment_id": 5,'.
            '"segment":"Imomushi", "args": {"arg1": 1, "arg2": 2}}'.PHP_EOL
        );
        fwrite(
            $fh,
            '{"pipeline_id": "hogehoge", "segment_id": 6,'.
            '"segment":"Imomushi", "args": {"arg1": 1, "arg2": 2}}'.PHP_EOL
        );
        fclose($fh);
        $request = $this -> target -> getRequest();
        $this -> assertNotEmpty(
            $request
        );
        $this -> assertEquals(
            5,
            count($request)
        );
    }

    public function testRun()
    {
        $this -> assertTrue(
            method_exists(
                $this -> target,
                'run'
            )
        );

        $fh = fopen($this -> tmpInput, 'w');
        fwrite(
            $fh,
            '{"pipeline_id": "hogehoge", "segment_id": 1,'.
            '"segment":"Imomushi", "args": {"arg1": 1, "arg2": 2}}'.PHP_EOL
        );
        fclose($fh);
        $this -> target -> run();
    }
    public function testLog()
    {
        $this -> assertTrue(
            method_exists(
                $this -> target,
                'log'
            )
        );
        $input =
            '{"pipeline_id": "hogehoge", "segment_id": 1,'.
            '"segment":"Imomushi", "args": {"arg1": 1, "arg2": 2}}'.PHP_EOL;

        $fh = fopen($this -> tmpInput, 'w');
        fwrite(
            $fh,
            $input
        );
        fclose($fh);
        $this -> target -> getRequest();
        $log = json_decode(file_get_contents($this -> tmpLog));
        $this -> assertNotNull(
            $log
        );
        $this -> assertEquals(
            $log -> input,
            $this -> tmpInput
        );
        $this -> assertEquals(
            $log -> size,
            strlen($input)
        );

        $fh = fopen($this -> tmpInput, 'a');
        fwrite(
            $fh,
            $input
        );
        fclose($fh);
        $this -> target -> getRequest();
        $log = json_decode(file_get_contents($this -> tmpLog));
        $this -> assertNotNull(
            $log
        );
        $this -> assertEquals(
            $log -> input,
            $this -> tmpInput
        );
        $this -> assertEquals(
            $log -> size,
            strlen($input) * 2
        );
    }
}
