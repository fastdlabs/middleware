<?php

declare(strict_types=1);

namespace tests\middleware;

use FastD\Http\Response\Text as Response;
use FastD\Middleware\Middleware;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Before extends Middleware
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);
        
        return (new Response())->withContents('before ' . $response->getContents());
    }
}