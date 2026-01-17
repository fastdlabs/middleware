<?php

declare(strict_types=1);

namespace Tests\Middleware;

use FastD\Http\Response\Text as Response;
use FastD\Middleware\Middleware;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AfterMiddleware extends Middleware
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // 直接返回响应，而不是调用下一个处理器
        return (new Response())->withContents('after ending request handler');
    }
}