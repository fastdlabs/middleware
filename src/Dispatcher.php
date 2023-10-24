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
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
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
     * @param array $stack
     */
    public function __construct(array $stack = [])
    {
        $this->stack = new SplStack();

        foreach ($stack as $value) {
            $this->push($value);
        }
    }

    /**
     * @param MiddlewareInterface $middleware
     * @return Dispatcher
     */
    public function unshift(MiddlewareInterface $middleware): Dispatcher
    {
        $this->stack->unshift($middleware);

        return $this;
    }

    /**
     * @return MiddlewareInterface
     */
    public function shift(): MiddlewareInterface
    {
        return $this->stack->shift();
    }

    /**
     * @param MiddlewareInterface $middleware
     * @return Dispatcher
     */
    public function push(MiddlewareInterface $middleware): Dispatcher
    {
        $this->stack->push($middleware);

        return $this;
    }

    /**
     * @return MiddlewareInterface
     */
    public function pop(): MiddlewareInterface
    {
        return $this->stack->pop();
    }

    /**
     * @param ServerRequestInterface $requestHandler
     * @return ResponseInterface
     */
    public function dispatch(ServerRequestInterface $requestHandler): ResponseInterface
    {
        $response = $this->resolve()->handle($requestHandler);

        $this->stack = new SplStack();

        return $response;
    }

    /**
     * @return RequestHandlerInterface
     */
    private function resolve(): RequestHandlerInterface
    {
        return $this->stack->isEmpty() ?
            new RequestHandler(
                function () {
                    throw new LogicException('unresolved request: middleware stack exhausted with no result');
                }
            ) :
            new RequestHandler(
                function (ServerRequestInterface $request) {
                    return $this->stack->shift()->process($request, $this->resolve());
                }
            );
    }
}
