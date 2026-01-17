<?php

use FastD\Http\Request\ServerRequest;
use FastD\Http\Response\Text as Response;
use FastD\Middleware\Dispatcher;
use FastD\Middleware\Middleware;
use FastD\Middleware\RequestHandler;
use Tests\Middleware\AfterMiddleware;
use Tests\Middleware\BeforeMiddleware;

class EdgeCasesTest extends \PHPUnit\Framework\TestCase
{
    public function testMultipleDispatchCallsOnSameRequest()
    {
        $dispatcher = new Dispatcher([new BeforeMiddleware(), new AfterMiddleware()]);
        
        $request = new ServerRequest('GET', '/test');
        
        // First dispatch
        $response1 = $dispatcher->dispatch(clone $request);
        $this->assertEquals('before after ending request handler', $response1->getContents());
        
        // Second dispatch with fresh dispatcher since the first one clears its stack
        $dispatcher2 = new Dispatcher([new BeforeMiddleware(), new AfterMiddleware()]);
        $response2 = $dispatcher2->dispatch(clone $request);
        $this->assertEquals('before after ending request handler', $response2->getContents());
    }
    
    public function testDeeplyNestedMiddlewareChain()
    {
        $dispatcher = new Dispatcher();
        
        // Add multiple middlewares to test deep nesting
        $dispatcher->push(new BeforeMiddleware());
        $dispatcher->push(new BeforeMiddleware());
        $dispatcher->push(new BeforeMiddleware());
        $dispatcher->push(new BeforeMiddleware());
        $dispatcher->push(new AfterMiddleware());
        
        $response = $dispatcher->dispatch(new ServerRequest('GET', '/'));
        
        $this->assertEquals('before before before before after ending request handler', $response->getContents());
    }
    
    public function testMiddlewareWithComplexRequestData()
    {
        $dispatcher = new Dispatcher([new class extends Middleware {
            public function process(\Psr\Http\Message\ServerRequestInterface $request, \Psr\Http\Server\RequestHandlerInterface $handler): \Psr\Http\Message\ResponseInterface
            {
                // Modify request attributes
                $request = $request->withAttribute('test_attr', 'test_value');
                
                $response = $handler->handle($request);
                
                return $response;
            }
        }, new AfterMiddleware()]);
        
        $response = $dispatcher->dispatch(new ServerRequest('GET', '/')->withQueryParams(['param' => 'value']));
        
        $this->assertStringContainsString('after ending request handler', $response->getContents());
    }
    
    public function testDispatcherWithMixedOperations()
    {
        $dispatcher = new Dispatcher();
        
        $before = new BeforeMiddleware();
        $after = new AfterMiddleware();
        
        // Mix different stack operations
        $dispatcher->push($before);      // Stack: [before]
        $dispatcher->unshift($after);    // Stack: [after, before]
        
        // Shift should return the first item (after)
        $shifted = $dispatcher->shift();
        $this->assertSame($after, $shifted);
        
        // Pop should return the last item (before)
        $popped = $dispatcher->pop();
        $this->assertSame($before, $popped);
    }
    
    public function testEmptyDispatcherAfterClearingAllMiddlewares()
    {
        $dispatcher = new Dispatcher();
        
        $before = new BeforeMiddleware();
        $after = new AfterMiddleware();
        
        // Add middlewares
        $dispatcher->push($before);
        $dispatcher->push($after);
        
        // Remove all middlewares
        $this->assertSame($after, $dispatcher->pop());
        $this->assertSame($before, $dispatcher->pop());
        
        // Should throw exception when trying to dispatch with empty stack
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('unresolved request: middleware stack exhausted with no result');
        
        $dispatcher->dispatch(new ServerRequest('GET', '/'));
    }
}