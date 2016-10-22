<?php
use FastD\Middleware\Network\Http\RequestMiddleware;
use FastD\Middleware\Stack;

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
        $this->stack->with(function () {
            echo 'logic';
        });

        $this->stack->run();

        $this->expectOutputString('logic');
    }

    public function testStackBefore()
    {
        $this->stack
            ->with(function () {
                echo 'logic';
            })
            ->before(function () {
                echo 'before';
            })
            ->run();

        $this->expectOutputString('beforelogic');
    }

    public function testStackAfter()
    {
        $this->stack
            ->with(function () {
                echo 'logic';
            })
            ->after(function () {
                echo 'after';
            })
            ->run();

        $this->expectOutputString('logicafter');
    }

    public function testStackMixin()
    {
        $this->stack
            ->with(function () {
                echo 'logic';
            })
            ->before(function () {
                echo 'before';
            })
            ->after(function () {
                echo 'after';
            })
            ->run();

        $this->expectOutputString('beforelogicafter');
    }

    public function testStackMultiMixin()
    {
        $this->stack
            ->with(function () {
                echo 'logic';
            })
            ->before(function () {
                echo 'before';
            })
            ->before(function () {
                echo 'b222';
            })
            ->after(function () {
                echo 'after';
            })
            ->run();
    }

    public function testStackMiddlewareObject()
    {
        include_once __DIR__ . '/middleware/Before.php';

        $middleware = new RequestMiddleware();

        $this->stack
            ->with($middleware)
            ->before(new Before());

        $this->stack->run(null, [10]);

        $result = $middleware->getResult();

        $this->assertEquals($result, [10]);
    }
}
