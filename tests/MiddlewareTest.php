<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */


use FastD\Middleware\Delegate;
use FastD\Http\ServerRequest;


class MiddlewareTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        include_once __DIR__ . '/middleware/ServerMiddleware.php';
    }

    public function testBaseMiddleware()
    {
        $middleware = new ServerMiddleware();

        $response = $middleware->process(new ServerRequest('GET', '/'), new Delegate(function (ServerRequest $request) {
            return 'world';
        }));

        $response->getBody()->rewind();
        echo $response->getBody()->getContents();
        $this->expectOutputString('hello world');
    }
}
