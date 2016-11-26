<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

use FastD\Http\ServerRequest;
use FastD\Middleware\DelegateInterface;
use FastD\Middleware\Middleware;
use FastD\Middleware\Stack;
use Psr\Http\Message\RequestInterface;

include __DIR__ . '/../vendor/autoload.php';

$middleware = new Middleware(function (RequestInterface $request, DelegateInterface $next) {
    // delegate control to next middleware
    return $next($request);
});

$middleware2 = new Middleware(function (RequestInterface $request, DelegateInterface $next) {
    echo 'world';
});

$stack = new Stack();
$stack->withoutMiddleware($middleware);
$stack->withoutMiddleware($middleware2);

echo '<pre>';
$response = $stack->process(new ServerRequest());
echo $response->getBody()->getSize();



