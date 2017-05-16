<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Middleware;


use FastD\Http\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class ServerMiddleware
 * @package FastD\Middleware
 */
abstract class Middleware implements MiddlewareInterface
{
    /**
     * @param ServerRequestInterface $request
     * @param DelegateInterface $next
     * @return ResponseInterface
     */
    abstract public function handle(ServerRequestInterface $request, DelegateInterface $next);

    /**
     * @param ServerRequestInterface $request
     * @param DelegateInterface $next
     * @return Response|mixed|ResponseInterface
     * @throws \Exception
     */
    public function process(ServerRequestInterface $request, DelegateInterface $next)
    {
        try {
            $return = call_user_func_array([$this, 'handle'], [$request, $next]);
            if ($return instanceof ResponseInterface) {
                $response = $return;
                $return = '';
            } else {
                $response = new Response();
            }
            $body = $response->getBody();
            if (!empty($return) && $body->isWritable()) {
                $body->write($return);
            }
            return $response;
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    /**
     * @param ServerRequestInterface $request
     * @param DelegateInterface $delegate
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        return $this->process($request, $delegate);
    }
}