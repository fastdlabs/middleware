<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2020
 *
 * @link      https://www.github.com/fastdlabs
 * @link      https://www.fastdlabs.com/
 */


use FastD\Middleware\Delegate;
use FastD\Http\ServerRequest;


class MiddlewareTest extends \PHPUnit\Framework\TestCase
{
    public function setUp()
    {
        include_once __DIR__.'/middleware/ServerMiddleware.php';
    }

    public function testBaseMiddleware()
    {
        $middleware = new ServerMiddleware();

        $response = $middleware->handle(new ServerRequest('GET', '/'), new Delegate(function (ServerRequest $request) {
            return (new \FastD\Http\Response())->withContent('world');
        }));

        $response->getBody()->rewind();
        echo $response->getBody()->getContents();
        $this->expectOutputString('hello world');
    }

    public function testBreakMiddleware()
    {
        $middleware = new ServerMiddleware();

        $response = $middleware->handle(new ServerRequest('GET', '/?foo=bar'),
            new Delegate(function (ServerRequest $request) {
                return (new \FastD\Http\Response())->withContent('world');
            }));

        echo $response->getBody();
        $this->expectOutputString('foo');
    }
}
