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


class DispatcherTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        include_once __DIR__ . '/middleware/Before.php';
        include_once __DIR__ . '/middleware/After.php';
    }

    public function testDispatcher()
    {
        $dispatcher = new Dispatcher([
            new Before(),
        ]);

        $dispatcher->dispatch(new ServerRequest('GET', '/'));

        $this->expectOutputString('before' . PHP_EOL);
    }

    public function testDispatcherSequence()
    {
        $dispatcher = new Dispatcher([
            new Before(),
            new Before(),
            new After(),
        ]);

        $dispatcher->dispatch(new ServerRequest('GET', '/foo'));
        $this->expectOutputString(<<<EOF
before
before
after
EOF
);
    }
}
