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
            $this->withAddMiddleware($value);
        }
    }

    /**
     * @param MiddlewareInterface $serverMiddleware
     * @return $this
     */
    public function withAddMiddleware(MiddlewareInterface $serverMiddleware)
    {
        $this->stack->push($serverMiddleware);

        return $this;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function dispatch(ServerRequestInterface $request)
    {
        $resolved = $this->resolve();

        return $resolved($request);
    }

    /**
     * @return DelegateInterface
     */
    private function resolve()
    {
        if (!$this->stack->isEmpty()) {
            return new Delegate(function (ServerRequestInterface $request) {
                $middleware = $this->stack->shift();

                $result = $middleware->process($request, $this->resolve());

                return $result;
            });
        }

        return new Delegate(function () {
            throw new LogicException('unresolved request: middleware stack exhausted with no result');
        });
    }
}