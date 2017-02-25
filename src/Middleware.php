<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Middleware;


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
     * @param DelegateInterface $delegate
     * @return ResponseInterface
     * @throws \Exception
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        try {
            $response = call_user_func_array([$this, 'handle'], [$request, $delegate]);

            if (!($response instanceof ResponseInterface)) {
                throw new \RuntimeException('Middleware must be return Psr\Http\Message\ResponseInterface');
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