<?php

/*
 * This file is part of Worker.
 *
 ** (c) 2016 -  Fumikazu FUjiwara
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Imomushi\Worker;

/**
 * Class FileMonitor
 * @package Imomushi\Worker
 */
class FileMonitor
{

    /**
     * Current version of Worker
     */
    const VERSION = '0.0.1-DEV';

    /**
     * Collection of Plugins in use (PluginInterface)
     *
     * @var \SplObjectStorage
     */
    private $plugins;

    /**
     * Constructer
     */
    public function __construct()
    {
        if (false === strpos(PHP_VERSION, 'hiphop')) {
            gc_enable();
        }
    }
}
