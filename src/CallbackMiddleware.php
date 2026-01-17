<?php

declare(strict_types=1);

namespace FastD\Middleware;

use Closure;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CallbackMiddleware extends Middleware
{
    public function __construct(private Closure $callback)
    {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return call_user_func_array($this->callback, [$request, $handler]);
    }
}