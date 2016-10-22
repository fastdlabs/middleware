<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Middleware;

use Closure;

/**
 * Class Stack
 *
 * @package FastD\Middleware
 */
class Stack
{
    /**
     * @var array
     */
    protected $stack = [];

    /**
     * @var Closure
     */
    protected $handle;

    public function append($name, $handle)
    {
        $this->stack[$name] = $handle;

        return $this;
    }

    /**
     * @param $handler
     * @return Closure
     */
    protected function prepare($handler)
    {
        foreach ($this->stack as $fn) {
            $handler = $fn($handler);
        }

        return $handler;
    }

    public function run($handle, array $arguments = [])
    {
        $handle = $this->prepare($handle);

        return call_user_func_array($handle, $arguments);
    }
}