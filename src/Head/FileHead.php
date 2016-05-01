<?php /*
 * This file is part of Worker.
 *
 ** (c) 2016 -  Fumikazu FUjiwara
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Imomushi\Worker\Head;

use Imomushi\Worker\Body;
use Imomushi\Worker\Tail\FileTail;

/**
 * Class FileHead
 *
 * @package Imomushi\Worker
 */
class FileHead
{
    /**
     * @var
     */
    protected $input;
    protected $log;
    protected $fh;
    protected $size = 0;
    protected $body;
    protected $currentSize = 0;
    protected $stop = false;

    /**
     * Constructer
     */
    public function __construct($config = array())
    {
        $this -> input = isset($config['input']) ?
            $config['input'] : '/tmp/input.txt';

        $this -> log = isset($config['log']) ?
            $config['log'] : '/tmp/imomushi.worker.head.file_head.log';

        $this -> body = new Body(
            isset($config['tail']) ?
            $config['tail'] :
            new FileTail('/tmp/output.txt')
        );
        $this -> sizeSetFromLog();
    }

    /**
     * main function
     */
    public function run()
    {
        do {
            foreach ($this -> getRequest() as $request) {
                $this -> body -> dispatch($request);
            }
        } while (!$this -> stop);
    }

    /**
     * protected functions
     */

    protected function open()
    {
        return false != (
            $this -> fh = fopen($this -> input, 'r')
        );
    }

    protected function close()
    {
        return fclose($this -> fh);
    }

    protected function changed()
    {
        clearstatcache();
        $fstat = fstat($this -> fh);

        $this -> currentSize = $fstat['size'];

        return $this -> size != $this -> currentSize;
    }

    protected function getRequest()
    {
        $this -> open();
        $lines = array();
        if ($this -> changed()) {
            fseek($this -> fh, $this -> size);
            $data = "";
            while ($d = fgets($this -> fh)) {
                $data .= $d;
            }
            $lines = array_map(
                'json_decode',
                array_filter(explode(PHP_EOL, $data))
            );
            $this -> size = $this -> currentSize;
            $this -> logWrite();
        }
        $this -> close();
        return $lines;
    }

    protected function logWrite()
    {
        $log = new \stdClass();
        $log -> input = $this -> input;
        $log -> size = $this -> size;
        file_put_contents($this-> log, json_encode($log).PHP_EOL, LOCK_EX);
    }

    protected function sizeSetFromLog()
    {
        $log = json_decode(@file_get_contents($this -> log));
        if (is_object($log)) {
            if (property_exists($log, 'input') &&
                $this -> input == $log -> input &&
                property_exists($log, 'size') &&
                is_integer($log -> size) &&
                0 < $log -> size
            ) {
                $this -> size = $log -> size;
            }
        }
    }
}
