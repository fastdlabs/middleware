<?php

use FastD\Http\Response\Text as Response;
use FastD\Http\Request\ServerRequest;
use FastD\Http\Stream;
use FastD\Middleware\Dispatcher;
use Tests\Middleware\AfterMiddleware;
use Tests\Middleware\BeforeMiddleware;

class DispatcherTest extends \PHPUnit\Framework\TestCase
{
    protected function createDefaultHandler(): RequestHandler
    {
        return new RequestHandler(function (ServerRequest $request) {
            return (new Response())->withContents('default response');
        });
    }

    public function testDispatcher()
    {
        $dispatcher = new Dispatcher([new AfterMiddleware()]);
        // 修改 After 中间件，让它在链的最后提供响应
        $res = $dispatcher->dispatch(new ServerRequest('GET', '/'));

        $this->assertEquals('after ending request handler', $res->getContents());
    }

    public function testDispatcherSequence()
    {
        $dispatcher = new Dispatcher();
        $dispatcher->push(new BeforeMiddleware()); // 先执行，底层运用队列，先进先出
        $dispatcher->push(new AfterMiddleware()); // 后执行

        $res = $dispatcher->dispatch(new ServerRequest('GET', '/foo'));
        $this->assertEquals('before after ending request handler', $res->getContents());
    }

    public function testEmptyStackDispatch()
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('unresolved request: middleware stack exhausted with no result');
        
        $dispatcher = new Dispatcher();
        $dispatcher->dispatch(new ServerRequest('GET', '/'));
    }

    public function testPushAndPop()
    {
        $dispatcher = new Dispatcher();
        $middleware = new AfterMiddleware();
        
        $result = $dispatcher->push($middleware);
        $this->assertSame($dispatcher, $result);
        
        $popped = $dispatcher->pop();
        $this->assertSame($middleware, $popped);
    }

    public function testUnshiftAndShift()
    {
        $dispatcher = new Dispatcher();
        $middleware = new BeforeMiddleware();
        
        $result = $dispatcher->unshift($middleware);
        $this->assertSame($dispatcher, $result);
        
        $shifted = $dispatcher->shift();
        $this->assertSame($middleware, $shifted);
    }

    public function testStackOperationsOrder()
    {
        $dispatcher = new Dispatcher();
        
        $first = new BeforeMiddleware();
        $second = new AfterMiddleware();
        
        // Test push/pop (LIFO)
        $dispatcher->push($first);
        $dispatcher->push($second);
        
        // Pop should return the last pushed item
        $this->assertSame($second, $dispatcher->pop());
        $this->assertSame($first, $dispatcher->pop());
        
        // Test unshift/shift (FIFO for beginning)
        $dispatcher->unshift($first);
        $dispatcher->unshift($second);
        
        // Shift should return the first unshifted item
        $this->assertSame($second, $dispatcher->shift());
        $this->assertSame($first, $dispatcher->shift());
    }

    public function testMixedStackOperations()
    {
        $dispatcher = new Dispatcher();
        
        $middleware1 = new BeforeMiddleware();
        $middleware2 = new AfterMiddleware();
        
        $dispatcher->push($middleware1); // [middleware1]
        $dispatcher->unshift($middleware2); // [middleware2, middleware1]
        
        // Shift should return middleware2
        $this->assertSame($middleware2, $dispatcher->shift());
        // Pop should return middleware1
        $this->assertSame($middleware1, $dispatcher->pop());
    }

    public function testConstructorWithInitialStack()
    {
        $initialMiddlewares = [new BeforeMiddleware(), new AfterMiddleware()];
        $dispatcher = new Dispatcher($initialMiddlewares);
        
        // When we pop, we should get the last item pushed initially
        $this->assertInstanceOf(AfterMiddleware::class, $dispatcher->pop());
        $this->assertInstanceOf(BeforeMiddleware::class, $dispatcher->pop());
    }
    
    public function testConstructWithEmptyArray()
    {
        $dispatcher = new Dispatcher([]);
        
        // Should have an empty stack initially
        $this->assertTrue($this->isStackEmpty($dispatcher));
    }
    
    public function testNestedMiddlewareChain()
    {
        $dispatcher = new Dispatcher();
        $dispatcher->push(new BeforeMiddleware());
        $dispatcher->push(new BeforeMiddleware()); // Add another Before to test nesting
        $dispatcher->push(new AfterMiddleware());
        
        $res = $dispatcher->dispatch(new ServerRequest('GET', '/'));
        $this->assertEquals('before before after ending request handler', $res->getContents());
    }
    
    private function isStackEmpty(Dispatcher $dispatcher): bool
    {
        $reflection = new \ReflectionClass($dispatcher);
        $property = $reflection->getProperty('splStack');
        $property->setAccessible(true);
        $stack = $property->getValue($dispatcher);
        
        return $stack->isEmpty();
    }
}
