<?php
use FastD\Middleware\DelegateInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

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
     * @param ServerRequestInterface $request
     * @param DelegateInterface $delegate
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        echo 'before' . PHP_EOL;

        if ('/' == $request->getUri()->getPath()) {
            return (new \FastD\Http\Response())->withContent('before');
        }

        return $delegate->process($request);
    }
}