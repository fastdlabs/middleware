<?php

/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */
class ServerMiddleware extends \FastD\Middleware\Middleware
{
    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \FastD\Middleware\DelegateInterface $next
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(\Psr\Http\Message\ServerRequestInterface $request, \FastD\Middleware\DelegateInterface $next)
    {
        $query = $request->getQueryParams();

        if (!empty($query)) {
            return new \FastD\Http\Response('foo');
        }

        echo 'hello ';

        return $next->process($request);
    }
}