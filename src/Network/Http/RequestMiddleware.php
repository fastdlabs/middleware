<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Middleware\Network\Http;

use FastD\Middleware\Middleware;

class RequestMiddleware extends Middleware
{
    public function handle($arguments = [])
    {
        return $arguments;
    }
}