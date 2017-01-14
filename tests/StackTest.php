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
        include_once __DIR__ . '/middleware/ServerMiddleware.php';
    }

    public function testStackLogic()
    {
        $middleware = new \ServerMiddleware();

        $this->stack->withMiddleware($middleware);

        $this->assertEquals(1, $this->stack->count());
    }
}
