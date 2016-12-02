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
     * @param ServerRequestInterface $request
     * @param DelegateInterface $frame
     * @return ResponseInterface
     */
    public function process(
        ServerRequestInterface $request,
        DelegateInterface $frame
    );
}