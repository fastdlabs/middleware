<?php
use FastD\Middleware\Middleware;

/**
 *
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

class DemoMiddleware extends Middleware
{
    public function handle()
    {
        return 'test';
    }
}