<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2020
 *
 * @link      https://www.github.com/fastdlabs
 * @link      https://www.fastdlabs.com/
 */


use FastD\Http\Response\Text as Response;
use FastD\Http\Request\ServerRequest;
use FastD\Middleware\RequestHandler;
use tests\middleware\ServerMiddleware;


class MiddlewareTest extends \PHPUnit\Framework\TestCase
{
    public function testBaseMiddleware()
    {
        $middleware = new ServerMiddleware();

        $response = $middleware->process(new ServerRequest('GET', '/'), new RequestHandler(function (ServerRequest $request) {
            return (new Response())->withContents('world');
        }));

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals('world hello world', $response->getContents());
    }

    public function testBreakMiddleware()
    {
        $middleware = new ServerMiddleware();

        $request = (new ServerRequest('GET', '/'))->withQueryParams(['foo' => 'bar']);
        $response = $middleware->process($request,
            new RequestHandler(function (ServerRequest $request) {
                return (new Response())->withContents('world');
            }));

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals('foo', $response->getContents());
    }

    public function testInvokeMiddleware()
    {
        $middleware = new ServerMiddleware();

        $response = $middleware(new ServerRequest('GET', '/'), new RequestHandler(function (ServerRequest $request) {
            return (new Response())->withContents('test');
        }));

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals('test hello world', $response->getContents());
    }

    public function testMiddlewareHandlesException()
    {
        $middleware = new class extends \FastD\Middleware\Middleware {
            public function process(\Psr\Http\Message\ServerRequestInterface $request, \Psr\Http\Server\RequestHandlerInterface $handler): \Psr\Http\Message\ResponseInterface
            {
                throw new \Exception('middleware exception');
            }
        };

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('middleware exception');
        
        $middleware->process(new ServerRequest('GET', '/'), new RequestHandler(function (ServerRequest $request) {
            return (new Response())->withContents('should not reach here');
        }));
    }
    
    public function testMiddlewareInvokeMethod()
    {
        $middleware = new ServerMiddleware();
        
        // Test the __invoke magic method
        $response = $middleware(new \FastD\Http\Request\ServerRequest('GET', '/'), new \FastD\Middleware\RequestHandler(function (\FastD\Http\Request\ServerRequest $request) {
            return (new \FastD\Http\Response\Text())->withContents('invoke test');
        }));
        
        $this->assertInstanceOf(\FastD\Http\Response\Text::class, $response);
        $this->assertEquals('invoke test hello world', $response->getContents());
    }
}
