<?php
/**
 *
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Middleware;

abstract class Middleware
{
    protected $previous;

    public function previous (Middleware $previous)
    {
        $this->previous = $previous;
    }

    abstract public function handle();
}