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

/**
 * Class Middleware
 *
 * @package FastD\Middleware
 */
interface MiddlewareInterface
{
    /**
     * @return mixed
     */
    public function handle();
}