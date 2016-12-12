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
 * Interface ClientMiddlewareInterface
 * @package FastD\Middleware
 */
interface ClientMiddlewareInterface extends MiddlewareInterface
{
    /**
     * Process a client request and return a response.
     *
     * Takes the incoming request and optionally modifies it before delegating
     * to the next frame to get a response.
     *
     * @param RequestInterface $request
     * @param DelegateInterface $next
     *
     * @return ResponseInterface
     */
    public function process(RequestInterface $request, DelegateInterface $next);

    /**
     * @param RequestInterface $request
     * @param DelegateInterface $next
     * @return ResponseInterface
     */
    public function __invoke(RequestInterface $request, DelegateInterface $next);
}