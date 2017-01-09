<?php
use FastD\Middleware\DelegateInterface;
use FastD\Middleware\Middleware;
use FastD\Middleware\Stack;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */
class StackTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Stack
     */
    protected $stack;

    public function setUp()
    {
        $this->stack = new Stack();
    }

    public function testStackLogic()
    {
        $middleware = new \FastD\Middleware\ServerMiddleware(function (ServerRequestInterface $serverRequest, DelegateInterface $delegate) {
            return 'hello world';
        });

        $this->stack->withMiddleware($middleware);

        $this->assertEquals(1, $this->stack->count());
    }
}
