<?php

/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */
class Before extends \FastD\Middleware\Middleware
{
    /**
     * @param \FastD\Middleware\MiddlewareInterface $prev
     * @param array $arguments
     * @param \FastD\Middleware\MiddlewareInterface $next
     * @return mixed
     */
    public function handle($arguments = [])
    {
        return $arguments;
    }
}