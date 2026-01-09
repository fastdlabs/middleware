<?php

use FastD\Http\Response\Text as Response;
use FastD\Http\Request\ServerRequest;
use FastD\Middleware\RequestHandler;

class RequestHandlerTest extends \PHPUnit\Framework\TestCase
{
    public function testRequestHandler()
    {
        $requestHandler = new RequestHandler(function (ServerRequest $request) {
            return (new Response())->withContents('hello world');
        });

        $response = $requestHandler->handle(new ServerRequest('GET', '/'));

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals('hello world', $response->getContents());
    }

    public function testRequestHandlerWithDifferentRequest()
    {
        $requestHandler = new RequestHandler(function (ServerRequest $request) {
            return (new Response())->withContents($request->getUri()->getPath());
        });

        $response = $requestHandler->handle(new ServerRequest('GET', '/test/path'));

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals('/test/path', $response->getContents());
    }

    public function testRequestHandlerWithException()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('test exception');
        
        $requestHandler = new RequestHandler(function (ServerRequest $request) {
            throw new \Exception('test exception');
        });

        $requestHandler->handle(new ServerRequest('GET', '/'));
    }
    
    public function testRequestHandlerWithDifferentResponseTypes()
    {
        $requestHandler = new RequestHandler(function (ServerRequest $request) {
            $response = (new \FastD\Http\Response\Text())->withContents('different response');
            return $response;
        });
        
        $response = $requestHandler->handle(new ServerRequest('GET', '/different'));
        
        $this->assertInstanceOf(\FastD\Http\Response\Text::class, $response);
        $this->assertEquals('different response', $response->getContents());
    }
    
    public function testRequestHandlerCallbackExecution()
    {
        $executed = false;
        
        $requestHandler = new RequestHandler(function (ServerRequest $request) use (&$executed) {
            $executed = true;
            return (new Response())->withContents('callback executed');
        });
        
        $response = $requestHandler->handle(new ServerRequest('GET', '/'));
        
        $this->assertTrue($executed, 'Callback should be executed when handle is called');
        $this->assertEquals('callback executed', $response->getContents());
    }
}