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

class MiddlewareInvoker
{
    /**
     * @var Middleware[]
     */
    protected $middlewares = [];

    public function addMiddleware(Middleware $middleware)
    {
        $this->middlewares[] = $middleware;

        return $this;
    }

    public function invoke()
    {
        $result = [];

        foreach ($this->middlewares as $key => $middleware) {
            $result[$key] = $middleware->invoke();
        }

        return $result;
    }
}