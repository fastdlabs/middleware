<?php

declare(strict_types=1);

namespace FastD\Middleware;

use LogicException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use SplStack;

class Dispatcher
{
    public function __construct(array $stack = [], protected SplStack $splStack = new SplStack())
    {
        foreach ($stack as $value) {
            $this->push($value);
        }
    }

    /**
     * @param MiddlewareInterface $middleware
     * @return Dispatcher
     */
    public function unshift(MiddlewareInterface $middleware): Dispatcher
    {
        $this->splStack->unshift($middleware);

        return $this;
    }

    /**
     * @return MiddlewareInterface
     */
    public function shift(): MiddlewareInterface
    {
        return $this->splStack->shift();
    }

    /**
     * @param MiddlewareInterface $middleware
     * @return Dispatcher
     */
    public function push(MiddlewareInterface $middleware): Dispatcher
    {
        $this->splStack->push($middleware);

        return $this;
    }

    /**
     * @return MiddlewareInterface
     */
    public function pop(): MiddlewareInterface
    {
        return $this->splStack->pop();
    }

    /**
     * @param ServerRequestInterface $serverRequest
     * @return ResponseInterface
     */
    public function dispatch(ServerRequestInterface $serverRequest): ResponseInterface
    {
        $response = $this->resolve()->handle($serverRequest);

        $this->splStack = new SplStack();

        return $response;
    }

    /**
     * @return RequestHandlerInterface
     */
    private function resolve(): RequestHandlerInterface
    {
        return $this->splStack->isEmpty() ?
            new RequestHandler(fn () => throw new LogicException('unresolved request: middleware stack exhausted with no result'))
            : new RequestHandler(fn (ServerRequestInterface $request) => $this->splStack->shift()->process($request, $this->resolve()));
    }
}
