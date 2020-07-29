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


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Interface MiddlewareInterface
 *
 * @package FastD\Middleware
 */
interface MiddlewareInterface
{
    /**
     * @param ServerRequestInterface $request
     * @param DelegateInterface $next
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request, DelegateInterface $next): ResponseInterface;
}
