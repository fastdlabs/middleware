<?php
use FastD\Middleware\MiddlewareInvoker;

/**
 *
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */
class MiddlewareTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        include __DIR__ . '/Middlewares/DemoMiddleware.php';
    }

    public function testUsage()
    {
        $middleware = new DemoMiddleware();

        $this->assertEquals('test', $middleware->handle());
    }
}