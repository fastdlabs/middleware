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
use FastD\Middleware\Exceptions\StackException;

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
    public $stack = [
        Stack::SEQUENCE_BEFORE => [],
        Stack::SEQUENCE_AFTER => [],
    ];

    const SEQUENCE_AFTER = 'after';
    const SEQUENCE_BEFORE = 'before';

    /**
     * @var Closure
     */
    protected $handler;

    /**
     * With logic.
     *
     * @param $handler
     * @return $this
     */
    public function with($handler)
    {
        if (!($handler instanceof Middleware) && !(is_callable($handler))) {
            throw new StackException();
        }

        if ($handler instanceof Middleware) {
            $handler = function ($arguments) use ($handler) {
                return $handler->run($arguments);
            };
        }

        $this->handler = $handler;

        return $this;
    }

    /**
     * @param $handler
     * @param null $sort
     * @return Stack
     */
    public function after($handler, $sort = null)
    {
        return $this->append($handler, Stack::SEQUENCE_AFTER, $sort);
    }

    /**
     * @param $handler
     * @param null $sort
     * @return Stack
     */
    public function before($handler, $sort = null)
    {
        return $this->append($handler, Stack::SEQUENCE_BEFORE, $sort);
    }

    /**
     * @param $handler
     * @param string $sequence
     * @param null $sort
     * @return $this
     */
    public function append($handler, $sequence = Stack::SEQUENCE_BEFORE, $sort = null)
    {
        if ($handler instanceof Middleware) {
            $handler = function ($arguments) use ($handler) {
                return $handler->run($arguments);
            };
        }

        if (empty($sort)) {
            $this->stack[$sequence][] = $handler;
        } else {
            $this->stack[$sequence][$sort] = $handler;
        }

        return $this;
    }

    /**
     * @param $handler
     * @return Closure
     */
    public function prepare($handler)
    {
        if (!empty($this->stack[Stack::SEQUENCE_BEFORE])) {
            rsort($this->stack[Stack::SEQUENCE_BEFORE]);

            $before = function ($arguments) {
                return $arguments;
            };
            foreach ($this->stack[Stack::SEQUENCE_BEFORE] as $fn) {
                $before = function ($arguments) use ($before, $fn) {
                    return $fn($before($arguments));
                };
            }

            $handler = function ($arguments) use ($handler, $before) {
                return $handler($before($arguments));
            };
        }

        if (!empty($this->stack[Stack::SEQUENCE_AFTER])) {
            rsort($this->stack[Stack::SEQUENCE_AFTER]);
            $after = function ($arguments) {
                return $arguments;
            };
            foreach ($this->stack[Stack::SEQUENCE_AFTER] as $fn) {
                $after = function ($arguments) use ($after, $fn) {
                    return $fn($after($arguments));
                };
            }

            $handler = function ($arguments = []) use ($after, $handler) {
                return $after($handler($arguments));
            };
        }

        return $handler;
    }

    /**
     * @param $handler
     * @param array $arguments
     * @return mixed
     */
    public function run($handler = null, array $arguments = [])
    {
        $handler = null === $handler ? $this->handler : $handler;

        $handler = $this->prepare($handler);

        return $handler($arguments);
    }
}