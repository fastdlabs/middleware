<?php

use FastD\Http\Request\ServerRequest;
use FastD\Http\Response\Text as Response;
use FastD\Middleware\CallbackMiddleware;
use FastD\Middleware\RequestHandler;

class CallbackMiddlewareTest extends \PHPUnit\Framework\TestCase
{
    public function testCallbackMiddlewareBasicFunctionality()
    {
        $middleware = new CallbackMiddleware(function (ServerRequest $request, RequestHandler $handler) {
            $response = $handler->handle($request);
            
            return $response;
        });
        
        $request = new ServerRequest('GET', '/');
        $handler = new RequestHandler(function (ServerRequest $request) {
            return (new Response())->withContents('original response');
        });
        
        $response = $middleware->process($request, $handler);
        
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals('original response', $response->getContents());
    }
    
    public function testCallbackMiddlewareCanModifyRequest()
    {
        $middleware = new CallbackMiddleware(function (ServerRequest $request, RequestHandler $handler) {
            // Modify the request before passing it to the next handler
            $modifiedRequest = $request->withAttribute('test_attribute', 'test_value');
            
            return $handler->handle($modifiedRequest);
        });
        
        $request = new ServerRequest('GET', '/');
        $handler = new RequestHandler(function (ServerRequest $request) {
            $attrValue = $request->getAttribute('test_attribute', 'default');
            return (new Response())->withContents("attribute: {$attrValue}");
        });
        
        $response = $middleware->process($request, $handler);
        
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals('attribute: test_value', $response->getContents());
    }
    
    public function testCallbackMiddlewareCanModifyResponse()
    {
        $middleware = new CallbackMiddleware(function (ServerRequest $request, RequestHandler $handler) {
            $response = $handler->handle($request);
            
            // Modify the response before returning
            return (new Response())->withContents("modified: " . $response->getContents());
        });
        
        $request = new ServerRequest('GET', '/');
        $handler = new RequestHandler(function (ServerRequest $request) {
            return (new Response())->withContents('original');
        });
        
        $response = $middleware->process($request, $handler);
        
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals('modified: original', $response->getContents());
    }
    
    public function testCallbackMiddlewareCanShortCircuit()
    {
        $middleware = new CallbackMiddleware(function (ServerRequest $request, RequestHandler $handler) {
            // Don't call the handler, return a response directly
            return (new Response())->withContents('short-circuited response');
        });
        
        $request = new ServerRequest('GET', '/');
        $handler = new RequestHandler(function (ServerRequest $request) {
            return (new Response())->withContents('this should not be reached');
        });
        
        $response = $middleware->process($request, $handler);
        
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals('short-circuited response', $response->getContents());
    }
    
    public function testCallbackMiddlewareIntegrationWithDispatcher()
    {
        $middleware = new CallbackMiddleware(function (ServerRequest $request, RequestHandler $handler) {
            $response = $handler->handle($request);
            
            return (new Response())->withContents($response->getContents() . ' processed by callback');
        });
        
        // Create a final handler that doesn't call the next handler
        $finalHandler = new class extends \FastD\Middleware\Middleware {
            public function process(\Psr\Http\Message\ServerRequestInterface $request, \Psr\Http\Server\RequestHandlerInterface $handler): \Psr\Http\Message\ResponseInterface
            {
                return (new \FastD\Http\Response\Text())->withContents('final');
            }
        };
        
        $dispatcher = new \FastD\Middleware\Dispatcher([$middleware, $finalHandler]);
        
        $response = $dispatcher->dispatch(new ServerRequest('GET', '/'));
        
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals('final processed by callback', $response->getContents());
    }
    
    public function testCallbackMiddlewareWithClosureUsingCallUserFuncArray()
    {
        $callCount = 0;
        
        $middleware = new CallbackMiddleware(function (ServerRequest $request, RequestHandler $handler) use (&$callCount) {
            $callCount++;
            
            return $handler->handle($request);
        });
        
        $request = new ServerRequest('GET', '/');
        $handler = new RequestHandler(function (ServerRequest $request) {
            return (new Response())->withContents('response after callback executed');
        });
        
        $response = $middleware->process($request, $handler);
        
        $this->assertEquals(1, $callCount, 'Callback should be executed exactly once');
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals('response after callback executed', $response->getContents());
    }
}