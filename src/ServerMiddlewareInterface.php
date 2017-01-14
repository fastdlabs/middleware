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
 * Interface ServerMiddlewareInterface
 * @package FastD\Middleware
 */
interface ServerMiddlewareInterface extends MiddlewareInterface
{
    /**
     * @param ServerRequestInterface $serverRequest
     * @param DelegateInterface $delegate
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $serverRequest, DelegateInterface $delegate);

    /**
     * @param ServerRequestInterface $request
     * @param DelegateInterface $next
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, DelegateInterface $next);

    /**
     * @param ServerRequestInterface $request
     * @param DelegateInterface $next
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, DelegateInterface $next);
}