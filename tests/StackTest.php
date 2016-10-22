<?php
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

    public function testBeforeMiddleware()
    {
        $this->stack->append('hello', function ($handle) {
            return function () use ($handle) {
                echo 'after' . PHP_EOL;
                $handle();
            };
        });

        $this->stack->run(function () {
            echo 'meet' . PHP_EOL;
        });

        $this->expectOutputString('after' . PHP_EOL . 'meet' . PHP_EOL);
    }

    public function testAfterMiddleware()
    {
        $this->stack->append('hello', function ($handle) {
            return function () use ($handle) {
                $handle();
                echo 'after' . PHP_EOL;
            };
        });

        $this->stack->run(function () {
            echo 'meet' . PHP_EOL;
        });

        $this->expectOutputString('meet' . PHP_EOL . 'after' . PHP_EOL);
    }
}
