# FastD Middleware

[![Build Status](https://travis-ci.org/fastdlabs/middleware.svg?branch=master)](https://travis-ci.org/fastdlabs/middleware)
[![Support PSR15](https://img.shields.io/badge/support-psr15-brightgreen.svg)](https://travis-ci.org/fastdlabs/middleware)
[![Latest Stable Version](https://poser.pugx.org/fastd/middleware/v/stable)](https://packagist.org/packages/fastd/middleware)
[![Total Downloads](https://poser.pugx.org/fastd/middleware/downloads)](https://packagist.org/packages/fastd/middleware)
[![License](https://poser.pugx.org/fastd/middleware/license)](https://packagist.org/packages/fastd/middleware)
[![composer.lock](https://poser.pugx.org/fastd/middleware/composerlock)](https://packagist.org/packages/fastd/middleware)

## 简介

FastD Middleware 是一个实现了 PSR-15 HTTP 服务器中间件标准的轻量级中间件库。它基于 SplStack 实现了灵活的中间件栈管理，支持中间件的链式调用，适用于各种 PHP HTTP 应用程序。

## 环境依赖说明

* PHP >= 8.2
* PSR-7 HTTP 消息接口实现
* PSR-15 HTTP 服务器中间件标准

## 基础使用说明

### 安装

```bash
composer require "fastd/middleware" -vvv
```

### 基本用法

```php
use FastD\Middleware\Dispatcher;
use FastD\Middleware\Middleware;
use FastD\Http\Request\ServerRequest;
use FastD\Middleware\RequestHandler;

// 创建一个中间件类
class ExampleMiddleware extends Middleware
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // 在请求处理前执行的逻辑
        $response = $handler->handle($request);
        // 在请求处理后执行的逻辑
        return $response;
    }
}

// 创建调度器并添加中间件
$dispatcher = new Dispatcher([
    new ExampleMiddleware(),
    // 可以添加更多中间件
]);

// 发起请求
$response = $dispatcher->dispatch(new ServerRequest('GET', '/'));
```

### 中间件栈操作

```php
use FastD\Middleware\Dispatcher;

$dispatcher = new Dispatcher();

// 添加中间件到栈顶
$dispatcher->push($middleware);

// 从栈顶移除中间件
$dispatcher->pop();

// 添加中间件到栈底
$dispatcher->unshift($middleware);

// 从栈底移除中间件
$dispatcher->shift();

// 执行中间件链
$response = $dispatcher->dispatch($serverRequest);
```

## 文档详细引导

### 核心组件

FastD Middleware 包含以下核心组件：

1. **Dispatcher**: 负责管理中间件栈，提供 push/pop/unshift/shift 操作方法，并执行中间件链
2. **Middleware**: 抽象中间件基类，实现了 PSR-15 的 MiddlewareInterface 接口
3. **RequestHandler**: 请求处理器，封装回调函数并实现 RequestHandlerInterface 接口

### 中间件执行流程

1. Dispatcher 使用 SplStack 存储中间件
2. 通过递归解析中间件栈构建执行链
3. 从栈底开始依次执行中间件
4. 每个中间件可选择是否调用后续中间件
5. 执行完成后清空中间件栈

### 中间件开发

创建自定义中间件类：

```php
use FastD\Middleware\Middleware;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;

class CustomMiddleware extends Middleware
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // 在请求处理前执行的逻辑
        
        // 调用下一个中间件
        $response = $handler->handle($request);
        
        // 在请求处理后执行的逻辑
        
        return $response;
    }
}
```

## 贡献

欢迎对项目感兴趣、愿意参与其中的开发者共同打造更好的 PHP 生态。

如果你有兴趣参与开发，可以尝试以下方式：

* 在你的项目中使用，将遇到的问题 [反馈](https://github.com/JanHuang/fastD/issues)。
* 提出更好的建议或功能需求。

## License

MIT License