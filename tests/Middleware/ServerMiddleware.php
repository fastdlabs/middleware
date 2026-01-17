<?php

declare(strict_types=1);

namespace Tests\Middleware;

use FastD\Http\Response\Text as Response;
use FastD\Middleware\Middleware;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ServerMiddleware extends Middleware
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($request->getQueryParams()['foo'] ?? null) {
            return (new Response())->withContents('foo');
        }

        $response = $handler->handle($request);

        return (new Response())->withContents($response->getContents() . ' hello world');
    }
}