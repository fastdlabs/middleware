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
     * @deprecated remove the 2.0
     * @param MiddlewareInterface $middleware
     * @return $this
     */
    public function withAddMiddleware(MiddlewareInterface $middleware)
    {
        $this->stack->push($middleware);

        return $this;
    }

    /**
     * @param MiddlewareInterface $middleware
     * @return $this
     */
    public function after(MiddlewareInterface $middleware)
    {
        $this->stack->unshift($middleware);

        return $this;
    }

    /**
     * @param MiddlewareInterface $middleware
     * @return $this
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
        return $this->resolve()->process($request);
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
                    return $this->stack->shift()->process($request, $this->resolve());
                }
            );
    }
}