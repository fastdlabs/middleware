<?php

require_once __DIR__ . '/vendor/autoload.php';

use FastD\Http\Request\ServerRequest;
use FastD\Http\Response\Text as Response;
use FastD\Middleware\Dispatcher;
use FastD\Middleware\Middleware;
use FastD\Middleware\RequestHandler;
use FastD\Middleware\CallbackMiddleware;

// 示例1: 基本中间件使用
echo "=== 示例1: 基本中间件使用 ===\n";

// 定义一个简单的日志中间件
class LogMiddleware extends Middleware
{
    public function process(\Psr\Http\Message\ServerRequestInterface $request, \Psr\Http\Server\RequestHandlerInterface $handler): \Psr\Http\Message\ResponseInterface
    {
        echo "日志中间件: 请求开始处理\n";
        
        $response = $handler->handle($request);
        
        echo "日志中间件: 请求处理完成\n";
        
        return $response;
    }
}

// 定义一个简单的认证中间件
class AuthMiddleware extends Middleware
{
    public function process(\Psr\Http\Message\ServerRequestInterface $request, \Psr\Http\Server\RequestHandlerInterface $handler): \Psr\Http\Message\ResponseInterface
    {
        echo "认证中间件: 验证请求\n";
        
        // 在这里可以添加认证逻辑
        $request = $request->withAttribute('user_id', 123);
        
        return $handler->handle($request);
    }
}

// 定义一个最终处理器（也必须是中间件）
class FinalHandler extends Middleware
{
    public function process(\Psr\Http\Message\ServerRequestInterface $request, \Psr\Http\Server\RequestHandlerInterface $handler): \Psr\Http\Message\ResponseInterface
    {
        $userId = $request->getAttribute('user_id', 'anonymous');
        return (new Response())->withContents("最终处理器: 用户ID {$userId}");
    }
}

// 创建调度器并添加中间件
$dispatcher = new Dispatcher();
$dispatcher->push(new FinalHandler());
$dispatcher->unshift(new AuthMiddleware());
$dispatcher->unshift(new LogMiddleware());

// 发起请求
$request = new ServerRequest('GET', '/');
$response = $dispatcher->dispatch($request);

echo "响应内容: " . $response->getContents() . "\n\n";

// 示例2: 使用回调中间件
echo "=== 示例2: 使用回调中间件 ===\n";

$dispatcher2 = new Dispatcher();

// 使用回调中间件简化中间件定义
$dispatcher2->push(new CallbackMiddleware(function ($request, $handler) {
    echo "回调中间件1: 处理请求前\n";
    $response = $handler->handle($request);
    echo "回调中间件1: 处理请求后\n";
    return $response;
}));

$dispatcher2->push(new CallbackMiddleware(function ($request, $handler) {
    echo "回调中间件2: 修改请求属性\n";
    $request = $request->withAttribute('timestamp', time());
    return $handler->handle($request);
}));

// 最终处理器也应该是中间件
$dispatcher2->push(new class extends Middleware {
    public function process(\Psr\Http\Message\ServerRequestInterface $request, \Psr\Http\Server\RequestHandlerInterface $handler): \Psr\Http\Message\ResponseInterface
    {
        $timestamp = $request->getAttribute('timestamp');
        return (new Response())->withContents("回调示例响应: 时间戳 {$timestamp}");
    }
});

$response2 = $dispatcher2->dispatch(new ServerRequest('GET', '/callback-example'));
echo "响应内容: " . $response2->getContents() . "\n\n";

// 示例3: 中间件栈操作
echo "=== 示例3: 中间件栈操作 ===\n";

$dispatcher3 = new Dispatcher();

// 使用 push/unshift 方法添加中间件
$logMiddleware = new LogMiddleware();
$authMiddleware = new AuthMiddleware();

$dispatcher3->push($logMiddleware);    // 栈底
$dispatcher3->unshift($authMiddleware); // 栈顶

// 使用 pop/shift 方法移除中间件
$popped = $dispatcher3->pop(); // 移除栈顶元素 (LogMiddleware)
echo "弹出的中间件类型: " . get_class($popped) . "\n";

$shifted = $dispatcher3->shift(); // 移除栈底元素 (AuthMiddleware)
echo "移出的中间件类型: " . get_class($shifted) . "\n";

// 检查栈中剩余中间件数量（使用反射访问内部栈）
$reflection = new \ReflectionClass($dispatcher3);
$property = $reflection->getProperty('splStack');
$property->setAccessible(true);
$splStack = $property->getValue($dispatcher3);

echo "栈中剩余中间件数量: " . $splStack->count() . "\n\n";

// 示例4: 复杂中间件链
echo "=== 示例4: 复杂中间件链 ===\n";

$dispatcher4 = new Dispatcher();

// 添加多个中间件形成复杂链条
$dispatcher4->push(new class extends Middleware {
    public function process(\Psr\Http\Message\ServerRequestInterface $request, \Psr\Http\Server\RequestHandlerInterface $handler): \Psr\Http\Message\ResponseInterface
    {
        echo "[中间件A] 开始处理\n";
        $request = $request->withAttribute('step_a', true);
        $response = $handler->handle($request);
        echo "[中间件A] 结束处理\n";
        return $response;
    }
});

$dispatcher4->push(new class extends Middleware {
    public function process(\Psr\Http\Message\ServerRequestInterface $request, \Psr\Http\Server\RequestHandlerInterface $handler): \Psr\Http\Message\ResponseInterface
    {
        echo "  [中间件B] 开始处理\n";
        $request = $request->withAttribute('step_b', true);
        $response = $handler->handle($request);
        echo "  [中间件B] 结束处理\n";
        return $response;
    }
});

$dispatcher4->push(new class extends Middleware {
    public function process(\Psr\Http\Message\ServerRequestInterface $request, \Psr\Http\Server\RequestHandlerInterface $handler): \Psr\Http\Message\ResponseInterface
    {
        echo "    [中间件C] 开始处理\n";
        $request = $request->withAttribute('step_c', true);
        $response = $handler->handle($request);
        echo "    [中间件C] 结束处理\n";
        return $response;
    }
});

// 最终处理器
$dispatcher4->push(new class extends Middleware {
    public function process(\Psr\Http\Message\ServerRequestInterface $request, \Psr\Http\Server\RequestHandlerInterface $handler): \Psr\Http\Message\ResponseInterface
    {
        $steps = [];
        if ($request->getAttribute('step_a')) $steps[] = 'A';
        if ($request->getAttribute('step_b')) $steps[] = 'B';
        if ($request->getAttribute('step_c')) $steps[] = 'C';
        
        return (new Response())->withContents("完成步骤: " . implode(', ', $steps));
    }
});

$response4 = $dispatcher4->dispatch(new ServerRequest('GET', '/complex-chain'));
echo "响应内容: " . $response4->getContents() . "\n";

// 示例5: 空栈异常处理
echo "\n=== 示例5: 空栈异常处理 ===\n";

try {
    $emptyDispatcher = new Dispatcher();
    $response = $emptyDispatcher->dispatch(new ServerRequest('GET', '/empty'));
} catch (\LogicException $e) {
    echo "捕获到预期异常: " . $e->getMessage() . "\n";
}

echo "\n示例执行完毕！\n";