<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */


use FastD\Http\ServerRequest;
use FastD\Middleware\Dispatcher;
use FastD\Middleware\ServerMiddleware;


class DispatcherTest extends PHPUnit_Framework_TestCase
{
    public function testDispatcher()
    {
        $dispatcher = new Dispatcher([
            new ServerMiddleware(function (ServerRequest $request, \FastD\Middleware\Delegate $delegate) {
                echo 'hello world';
            })
        ]);

        $dispatcher->dispatch(new ServerRequest());

        $this->expectOutputString('hello world');
    }
}
