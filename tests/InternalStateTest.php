<?php

use FastD\Middleware\Dispatcher;
use Tests\Middleware\AfterMiddleware;
use Tests\Middleware\BeforeMiddleware;

class InternalStateTest extends \PHPUnit\Framework\TestCase
{
    public function testSplStackIsResetAfterDispatch()
    {
        $dispatcher = new Dispatcher([new AfterMiddleware()]);
        $initialStackSize = $this->getStackSize($dispatcher);
        
        // Dispatch should process the stack and then reset it
        $dispatcher->dispatch(new \FastD\Http\Request\ServerRequest('GET', '/'));
        
        // Stack should be reset to empty after dispatch
        $finalStackSize = $this->getStackSize($dispatcher);
        
        $this->assertEquals(0, $finalStackSize); // Should be 0 after dispatch
    }
    
    public function testMultipleDispatches()
    {
        // Test first dispatch
        $dispatcher1 = new Dispatcher([new BeforeMiddleware(), new AfterMiddleware()]);
        $response1 = $dispatcher1->dispatch(new \FastD\Http\Request\ServerRequest('GET', '/'));
        $this->assertEquals('before after ending request handler', $response1->getContents());
        
        // Test second dispatch with a new instance (since dispatch clears the stack)
        $dispatcher2 = new Dispatcher([new BeforeMiddleware(), new AfterMiddleware()]);
        $response2 = $dispatcher2->dispatch(new \FastD\Http\Request\ServerRequest('GET', '/'));
        $this->assertEquals('before after ending request handler', $response2->getContents());
    }
    
    private function getStackSize(Dispatcher $dispatcher): int
    {
        $reflection = new \ReflectionClass($dispatcher);
        $property = $reflection->getProperty('splStack');
        $property->setAccessible(true);
        $stack = $property->getValue($dispatcher);
        
        return $stack->count();
    }
}