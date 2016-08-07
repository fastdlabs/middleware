<?php

use FastD\Middleware\MiddlewareInterface;

/**
 *
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

class DemoMiddleware implements MiddlewareInterface
{
    public function handle()
    {
        return 'test';
    }
}