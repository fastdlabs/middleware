<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2020
 *
 * @link      https://www.github.com/fastdlabs
 * @link      https://www.fastdlabs.com/
 */


use FastD\Http\Response;
use FastD\Http\ServerRequest;
use FastD\Middleware\RequestHandler;
use tests\middleware\ServerMiddleware;


class MiddlewareTest extends \PHPUnit\Framework\TestCase
{
    public function testBaseMiddleware()
    {
        $middleware = new ServerMiddleware();

        $response = $middleware->process(new ServerRequest('GET', '/'), new RequestHandler(function (ServerRequest $request) {
            return new Response('hello world');
        }));

        $response->getBody()->rewind();
        echo $response->getBody()->getContents();
        $this->expectOutputString('hello world');
    }

    public function testBreakMiddleware()
    {
        $middleware = new ServerMiddleware();

        $response = $middleware->process(new ServerRequest('GET', '/?foo=bar'),
            new RequestHandler(function (ServerRequest $request) {
                return (new Response())->withContent('world');
            }));

        echo $response->getBody();
        $this->expectOutputString('foo');
    }
}
