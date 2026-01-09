<?php

declare(strict_types=1);

namespace FastD\Middleware;

use Closure;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class Delegate
 * @package FastD\Middleware
 */
class RequestHandler implements RequestHandlerInterface
{
    public function __construct(protected Closure $callback)
    {
    }

    /**
     * Dispatch the next available middleware and return the response.
     *
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return call_user_func($this->callback, $request);
    }
}
