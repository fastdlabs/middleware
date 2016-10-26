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
 * Interface MiddlewareInterface
 *
 * @package FastD\Middleware
 */
interface MiddlewareInterface
{
    /**
     * @param array $arguments
     * @return mixed
     */
    public function handle($arguments = []);

    /**
     * Get previous middleware execute return results.
     *
     * @return mixed
     */
    public function getResult();
}