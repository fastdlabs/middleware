<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Middleware;


use LogicException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SplStack;

/**
 * Class Dispatcher
 * @package FastD\Middleware
 */
class Dispatcher
{
    /**
     * @var SplStack
     */
    protected $stack;

    /**
     * Dispatcher constructor.
     * @param $stack
     */
    public function __construct(array $stack = [])
    {
        $this->stack = new SplStack();

        foreach ($stack as $value) {
            $this->before($value);
        }
    }

    /**
     * @param MiddlewareInterface $middleware
     * @return Dispatcher
     */
    public function after(MiddlewareInterface $middleware)
    {
        $this->stack->unshift($middleware);

        return $this;
    }

    /**
     * @param MiddlewareInterface $middleware
     * @return Dispatcher
     */
    public function before(MiddlewareInterface $middleware)
    {
        $this->stack->push($middleware);

        return $this;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function dispatch(ServerRequestInterface $request)
    {
        $response = $this->resolve()->process($request);

        $this->stack = new SplStack();

        return $response;
    }

    /**
     * @return DelegateInterface
     */
    private function resolve()
    {
        return $this->stack->isEmpty() ?
            new Delegate(
                function () {
                    throw new LogicException('unresolved request: middleware stack exhausted with no result');
                }
            ) :
            new Delegate(
                function (ServerRequestInterface $request) {
                    return $this->stack->shift()->handle($request, $this->resolve());
                }
            );
    }
}