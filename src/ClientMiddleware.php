<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Middleware;

use Exception;
use FastD\Http\Response;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class Middleware
 *
 * @package FastD\Middleware
 */
abstract class ClientMiddleware implements ClientMiddlewareInterface
{
    /**
     * @var callable
     */
    protected $callback;

    /**
     * Process a client request and return a response.
     *
     * Takes the incoming request and optionally modifies it before delegating
     * to the next frame to get a response.
     *
     * @param RequestInterface $request
     * @param DelegateInterface $next
     *
     * @return ResponseInterface
     * @throws Exception
     */
    public function process(RequestInterface $request, DelegateInterface $next)
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

            if ($return !== '' && $body->isWritable()) {
                $body->write($return);
            }

            return $response;
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    /**
     * @param RequestInterface $request
     * @param DelegateInterface $next
     * @return ResponseInterface
     */
    public function __invoke(RequestInterface $request, DelegateInterface $next)
    {
        return $this->process($request, $next);
    }
}