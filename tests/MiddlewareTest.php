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
use FastD\Middleware\ServerMiddleware;


class MiddlewareTest extends PHPUnit_Framework_TestCase
{
    public function testBaseMiddleware()
    {
        $middleware = new ServerMiddleware(function (ServerRequest $request, Delegate $next) {
            $response = $next($request);

            $content = 'hello ' . $response;

            echo $content;
        });

        $response = $middleware->process(new ServerRequest(), new Delegate(function (ServerRequest $request) {
            return 'world';
        }));

        $response->getBody()->rewind();
        echo $response->getBody()->getContents();
        $this->expectOutputString('hello world');
    }
}
