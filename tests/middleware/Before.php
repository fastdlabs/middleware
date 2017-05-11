<?php

/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */
class Before extends \FastD\Middleware\Middleware
{
    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \FastD\Middleware\DelegateInterface $next
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(\Psr\Http\Message\ServerRequestInterface $request, \FastD\Middleware\DelegateInterface $next)
    {
        echo 'before' . PHP_EOL;

        if ('/' == $request->getUri()->getPath()) {
            return new \FastD\Http\Response('before');
        }

        return $next->process($request);
    }
}