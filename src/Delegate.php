<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Middleware;


use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class Delegate
 * @package FastD\Middleware
 */
class Delegate implements DelegateInterface
{
    /**
     * @var callable
     */
    protected $callback;

    /**
     * @param callable $callback function (RequestInterface $request) : ResponseInterface
     */
    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    /**
     * Dispatch the next available middleware and return the response.
     *
     * @param RequestInterface $request
     *
     * @return ResponseInterface
     */
    public function process(RequestInterface $request)
    {
        return call_user_func($this->callback, $request);
    }
}