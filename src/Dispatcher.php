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

/**
 * Class Dispatcher
 * @package FastD\Middleware
 */
class Dispatcher
{
    /**
     * @var Stack
     */
    protected $stack;

    /**
     * Dispatcher constructor.
     * @param $stack
     */
    public function __construct($stack)
    {
        if ($stack instanceof StackInterface) {
            $this->stack = $stack;
        } else {
            $this->stack = new Stack($stack);
        }
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function dispatch(ServerRequestInterface $request)
    {
        $resolved = $this->resolve(0);

        return $resolved($request);
    }

    /**
     * @param int $index middleware stack index
     *
     * @return DelegateInterface
     */
    private function resolve($index)
    {
        if (isset($this->stack[$index])) {
            return new Delegate(function (ServerRequestInterface $request) use ($index) {
                $middleware = $this->stack[$index];

                $result = $middleware->process($request, $this->resolve($index + 1));

                return $result;
            });
        }

        return new Delegate(function () {
            throw new LogicException('unresolved request: middleware stack exhausted with no result');
        });
    }
}