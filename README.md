# middleware

[![Build Status](https://travis-ci.org/JanHuang/middleware.svg?branch=master)](https://travis-ci.org/JanHuang/middleware)
[![Support PSR15](https://img.shields.io/badge/support-psr15-brightgreen.svg)](https://travis-ci.org/JanHuang/middleware)
[![Latest Stable Version](https://poser.pugx.org/fastd/middleware/v/stable)](https://packagist.org/packages/fastd/middleware)
[![Total Downloads](https://poser.pugx.org/fastd/middleware/downloads)](https://packagist.org/packages/fastd/middleware)
[![License](https://poser.pugx.org/fastd/middleware/license)](https://packagist.org/packages/fastd/middleware)
[![composer.lock](https://poser.pugx.org/fastd/middleware/composerlock)](https://packagist.org/packages/fastd/middleware)

Http 中间件，实现PSR15

### requirement

* php >= 5.6

### installation

```
composer require "fastd/middlware" -vvv
```

```php
$middleware = new Middleware(function (RequestInterface $request, DelegateInterface $next) {
    // delegate control to next middleware
    return $next($request);
});

$middleware2 = new Middleware(function (RequestInterface $request, DelegateInterface $next) {
    echo 'world';
});

$dispatcher = new Dispatcher([
    $middleware,
    $middleware2
]);

$response = $dispatcher->dispatch(new ServerRequest('GET', '/'));
```

### License MIT
