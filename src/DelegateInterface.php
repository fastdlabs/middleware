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
 * Interface DelegateInterface
 * @package FastD\Middleware
 */
interface DelegateInterface
{
    /**
     * Dispatch the next available middleware and return the response.
     *
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function process(RequestInterface $request);
}