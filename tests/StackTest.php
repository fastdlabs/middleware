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
        $middleware = new Middleware(function (ServerRequestInterface $serverRequest, DelegateInterface $delegate) {
            return 'hello world';
        });

        $this->stack->withMiddleware($middleware);

        echo $this->stack->count();

        echo gettype($this->stack[0]);
    }
}
