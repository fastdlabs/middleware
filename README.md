# middleware

[![Build Status](https://travis-ci.org/fastdlabs/middleware.svg?branch=master)](https://travis-ci.org/fastdlabs/middleware)
[![Support PSR15](https://img.shields.io/badge/support-psr15-brightgreen.svg)](https://travis-ci.org/fastdlabs/middleware)
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

### 贡献

非常欢迎感兴趣，愿意参与其中，共同打造更好PHP生态，Swoole生态的开发者。

如果你乐于此，却又不知如何开始，可以试试下面这些事情：

* 在你的系统中使用，将遇到的问题 [反馈](https://github.com/JanHuang/fastD/issues)。
* 有更好的建议？欢迎联系 [bboyjanhuang@gmail.com](mailto:bboyjanhuang@gmail.com) 或 [新浪微博:编码侠](http://weibo.com/ecbboyjan)。

### 联系

如果你在使用中遇到问题，请联系: [bboyjanhuang@gmail.com](mailto:bboyjanhuang@gmail.com). 微博: [编码侠](http://weibo.com/ecbboyjan)

## License MIT
