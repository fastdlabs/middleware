<?php
/**
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
abstract class Middleware implements MiddlewareInterface
{
    /**
     * @var mixed
     */
    protected $result;

    /**
     * @param array $arguments
     * @return mixed
     */
    public function run($arguments = [])
    {
        $this->result = $this->handle($arguments);

        return $this->result;
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }
}