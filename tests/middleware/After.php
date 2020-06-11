<?php
use FastD\Middleware\DelegateInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2020
 *
 * @link      https://www.github.com/fastdlabs
 * @link      https://www.fastdlabs.com/
 */
class After extends \FastD\Middleware\Middleware
{
    /**
     * @param ServerRequestInterface $request
     * @param DelegateInterface $delegate
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request, DelegateInterface $delegate): ResponseInterface
    {
        echo 'after';
        return (new \FastD\Http\Response())->withContent('after');
    }
}
