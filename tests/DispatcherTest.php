<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2020
 *
 * @link      https://www.github.com/fastdlabs
 * @link      https://www.fastdlabs.com/
 */


use FastD\Http\ServerRequest;
use FastD\Http\Stream;
use FastD\Middleware\Dispatcher;
use tests\middleware\After;
use tests\middleware\Before;


class DispatcherTest extends \PHPUnit\Framework\TestCase
{
    public function testDispatcher()
    {
        $dispatcher = new Dispatcher();
        $dispatcher->push(new After());

        $res = $dispatcher->dispatch(new ServerRequest('GET', '/'));

        $this->expectOutputString('after');
        $this->assertEquals('ending request handler', $res->getContents());
    }

    public function testDispatcherSequence()
    {
        $dispatcher = new Dispatcher();
        $dispatcher->push(new Before()); // 先执行，底层运用队列，先进先出
        $dispatcher->push(new After()); // 后执行

        $res = $dispatcher->dispatch(new ServerRequest('GET', '/foo'));
//        echo $res->getBody()->getContents();
        $this->expectOutputString('beforeafter');
        $this->assertEquals('ending request handler', $res->getContents());
    }
}
