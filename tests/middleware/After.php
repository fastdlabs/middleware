<?php

/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */
class After extends \FastD\Middleware\Middleware
{
    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \FastD\Middleware\DelegateInterface $delegate
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(\Psr\Http\Message\ServerRequestInterface $request, \FastD\Middleware\DelegateInterface $delegate)
    {
        echo 'after';
        return (new \FastD\Http\Response())->withContent('after');
    }
}