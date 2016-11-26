<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */


use FastD\Middleware\Delegate;
use FastD\Middleware\Middleware;
use FastD\Http\ServerRequest;


class MiddlewareTest extends PHPUnit_Framework_TestCase
{
    public function testBaseMiddleware()
    {
        $middleware = new Middleware(function (ServerRequest $request, Delegate $next) {
            return 'hello' . $next($request);
        });

        $response = $middleware->process(new ServerRequest(), new Delegate(function (ServerRequest $request) {
            return 'world';
        }));

        $response->getBody()->rewind();

        echo $response->getBody()->getContents();
    }
}
