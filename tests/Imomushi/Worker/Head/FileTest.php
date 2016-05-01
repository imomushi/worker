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
use Imomushi\Worker\Tests\Head\FileExtend;
use Imomushi\Worker\Body;

/**
 * Class FileTest
 *
 * @package Imomushi\Worker\Tests\Head
 */


class FileTest extends \PHPUnit_Framework_TestCase
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
        $this -> tmpInput = tempnam(sys_get_temp_dir(), 'imomushi.worker.tests.head.file_head.input.');
        $this -> tmpTail = tempnam(sys_get_temp_dir(), 'imomushi.worker.tests.head.file_head.tail.');
        $this -> tmpLog = tempnam(sys_get_temp_dir(), 'imomushi.worker.tests.head.file_head.log.');

        $this -> tail = new FileTail($this -> tmpTail);
        $this -> target = new FileExtend([
            'input' => $this -> tmpInput,
            'tail'  => $this -> tail,
            'log'   => $this -> tmpLog
        ]);
        $this -> target -> stop();

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
            'Imomushi\Worker\Head\File',
            $this -> target
        );
        $this -> assertEquals(
            $this -> tmpInput,
            $this -> target -> input()
        );
        $this -> assertEquals(
            $this -> tmpLog,
            $this -> target -> log()
        );
        $body =    $this -> target -> body();
        $this -> assertInstanceOf(
            'Imomushi\Worker\Tail\FileTail',
            $body -> tail
        );
        //default cases;
        $default = new FileExtend();
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
        $tmpInputHead = new FileExtend(['input' => $tmp, 'tail' => $this -> tail]);
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
    public function testLogWrite()
    {
        $this -> assertTrue(
            method_exists(
                $this -> target,
                'logWrite'
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
    public function testSizeSetFromLog()
    {
        $this -> assertTrue(
            method_exists(
                $this -> target,
                'sizeSetFromLog'
            )
        );
        //backup
        $size = $this -> target -> size();
        $backupTmpLog = file_get_contents($this -> tmpLog);

        //normal case
        $tmpData = new \stdClass();
        $tmpData -> input = $this -> tmpInput;
        $tmpData -> size = 1979;

        file_put_contents($this -> tmpLog, json_encode($tmpData), LOCK_EX);
        $this -> target -> sizeSetFromLog();
        $this -> assertEquals(
            1979,
            $this -> target -> size()
        );

        //invalid input case
        $this -> target -> size($size);
        $tmpData -> input = "INVALID";

        file_put_contents($this -> tmpLog, json_encode($tmpData), LOCK_EX);
        $this -> target -> sizeSetFromLog();
        $this -> assertEquals(
            $size,
            $this -> target -> size()
        );

        //invalid size cases
        $this -> target -> size($size);
        $tmpData -> input = $this -> tmpInput;
        $tmpData -> size = "INVALID";

        file_put_contents($this -> tmpLog, json_encode($tmpData), LOCK_EX);
        $this -> target -> sizeSetFromLog();
        $this -> assertEquals(
            $size,
            $this -> target -> size()
        );

        $this -> target -> size($size);
        $tmpData -> size = -1;

        file_put_contents($this -> tmpLog, json_encode($tmpData), LOCK_EX);
        $this -> target -> sizeSetFromLog();
        $this -> assertEquals(
            $size,
            $this -> target -> size()
        );

        file_put_contents($this -> tmpLog, $backupTmpLog);
        $this -> target -> size($size);
    }
}
