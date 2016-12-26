<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Middleware;

use Iterator;
use Countable;
use ArrayAccess;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface StackInterface extends Iterator, ArrayAccess, Countable
{
    /**
     * Return an instance with the specified middleware added to the stack.
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the stack, and MUST return an instance that contains
     * the specified middleware.
     *
     * @param MiddlewareInterface $middleware
     *
     * @return self
     */
    public function withMiddleware(MiddlewareInterface $middleware);

    /**
     * @param MiddlewareInterface $middleware
     * @return self
     */
    public function withAddMiddleware(MiddlewareInterface $middleware);

    /**
     * Return an instance without the specified middleware.
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the stack, and MUST return an instance that does not
     * contain the specified middleware.
     *
     * @param MiddlewareInterface $middleware
     *
     * @return self
     */
    public function withoutMiddleware(MiddlewareInterface $middleware);

    /**
     * Process the request through middleware and return the response.
     *
     * This method MUST be implemented in such a way as to allow the same
     * stack to be reused for processing multiple requests in sequence.
     *
     * @param RequestInterface $request
     *
     * @return ResponseInterface
     */
    public function process(RequestInterface $request);
}