<?php
declare(strict_types=1);
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2020
 *
 * @link      https://www.github.com/fastdlabs
 * @link      https://www.fastdlabs.com/
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
    protected SplStack $stack;

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
    public function after(MiddlewareInterface $middleware): Dispatcher
    {
        $this->stack->unshift($middleware);

        return $this;
    }

    /**
     * @param MiddlewareInterface $middleware
     * @return Dispatcher
     */
    public function before(MiddlewareInterface $middleware): Dispatcher
    {
        $this->stack->push($middleware);

        return $this;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function dispatch(ServerRequestInterface $request): ResponseInterface
    {
        $response = $this->resolve()->handle($request);

        $this->stack = new SplStack();

        return $response;
    }

    /**
     * @return DelegateInterface
     */
    private function resolve(): DelegateInterface
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
