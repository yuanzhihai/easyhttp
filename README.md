EasyHttp 是一个轻量级、语义化、对IDE友好的HTTP客户端，支持常见的HTTP请求、异步请求和并发请求，让你可以快速地使用 HTTP 请求与其他 Web 应用进行通信。

> EasyHttp并不强制依赖于cURL，如果没有安装cURL，EasyHttp会自动选择使用PHP流处理，或者你也可以提供自己的发送HTTP请求的处理方式。

<a name="installation"></a>
## 安装说明

#### 环境依赖

- PHP >= 5.5.0
- 如果使用PHP流处理，allow_url_fopen 必须在php.ini中启用。
- 如果使用cURL处理，cURL >= 7.19.4，并且编译了OpenSSL 与 zlib。

#### 一键安装

    composer require gouguoyin/easyhttp

## 发起请求

#### 同步请求

###### 常规请求

```php
$response = Http::get('http://httpbin.org/get');

$response = Http::post('http://httpbin.org/post');

$response = Http::patch('http://httpbin.org/patch');

$response = Http::put('http://httpbin.org/put');

$response = Http::delete('http://httpbin.org/delete');

$response = Http::head('http://httpbin.org/head');
```

###### 发送 URL 编码的请求(默认是JSON请求)

```php
// application/json
$response = Http::asJson()->post(...);

// application/x-www-form-urlencoded
$response = Http::asForm()->post(...);
```

###### 发送 Multipart 请求

```php
$response = Http::attach(
    'input_name', file_get_contents('photo1.jpg'), 'photo2.jpg'
)->post('http://test.com/attachments');
```

###### 携带请求头的请求

```php
$response = Http::withHeaders([
    'X-First'  => 'foo',
    'X-Second' => 'bar'
])->post(...);
```

###### 携带重定向的请求

```php
// 默认
$response = Http::withRedirect(false)->post(...);

$response = Http::withRedirect([
    'max'             => 5,
    'strict'          => false,
    'referer'         => true,
    'protocols'       => ['http', 'https'],
    'track_redirects' => false
])->post(...);
```

###### 携带认证的请求

```php
// Basic 认证
$response = Http::withBasicAuth('username', 'password')->post(...);

// Digest 认证(必须被HTTP服务器支持)
$response = Http::withDigestAuth('username', 'password')->post(...);
```

###### 携带Token令牌的请求

```php
$response = Http::withToken('token')->post(...);
```

###### 携带认证文件的请求

```php
$response = Http::withCert('/path/server.pem', 'password')->post(...);
```

###### 携带SSL证书的请求

```php
// 默认
$response = Http::withVerify(false)->post(...);

$response = Http::withVerify('/path/to/cert.pem')->post(...);
```

###### 携带COOKIE的请求

```php
$response = Http::withCookies(array $cookies, string $domain)->post(...);
```

###### 携带协议版本的请求

```php
$response = Http::withVersion('1.0')->post(...);
```

###### 使用代理的请求

```php
$response = Http::withProxy('tcp://localhost:8125')->post(...);

$response = Http::withProxy(
    [
        'http'  => 'tcp://localhost:8125', // Use this proxy with "http"
        'https' => 'tcp://localhost:9124', // Use this proxy with "https",
        'no' => ['.mit.edu', 'foo.com']    // Don't use a proxy with these
    ]
)->post(...);
```

###### 设置超时时间(单位秒)

```php
$response = Http::timeout(60)->post(...);
```

###### 设置延迟时间(单位秒)

```php
$response = Http::delay(60)->post(...);
```

###### 设置并发次数

```php
$response = Http::concurrency(10)->post(...);
```

#### 异步请求

```php
use Gouguoyin\EasyHttp\Response;
use Gouguoyin\EasyHttp\RequestException;

Http::getAsync('http://easyhttp.gouguoyin.cn/api/sleep3.json', [], function (Response $response) {
    echo '请求成功，返回内容：' . $response->body() . PHP_EOL;
}, function (RequestException $e) {
    echo '请求异常，错误码：' . $e->getCode() . '，错误信息：' . $e->getMessage() . PHP_EOL;
});

Http::postAsync(...);

Http::patchAsync(...);

Http::putAsync(...);

Http::deleteAsync(...);

Http::headAsync(...);
```

#### 并发请求

```php
use Gouguoyin\EasyHttp\Response;
use Gouguoyin\EasyHttp\RequestException;

$stime = microtime(true);

$promises = [
    Http::getAsync('http://easyhttp.gouguoyin.cn/api/sleep3.json'),
    Http::getAsync('http://easyhttp.gouguoyin.cn/api/sleep1.json'),
    Http::getAsync('http://easyhttp.gouguoyin.cn/api/sleep2.json'),
];

Http::concurrency(10)->promise($promises, function (Response $response, $index) {
    echo "发起第 $index 个请求，请求时长：" . $response->json()->second . '秒' . PHP_EOL;
}, function (RequestException $e) {
    echo '请求异常，错误码：' . $e->getCode() . '，错误信息：' . $e->getMessage() . PHP_EOL;
});

$etime = microtime(true);
$total = floor($etime-$stime);
echo "当前页面执行总时长：{$total} 秒" . PHP_EOL;

//输出
发起第 1 个请求，请求时长：1 秒
发起第 2 个请求，请求时长：2 秒
发起第 0 个请求，请求时长：3 秒
当前页面执行总时长：3 秒
```
> 如果为未设置concurrency，并发次数默认为$promises的元素个数

## 使用响应

发起请求后会返回一个 Gouguoyin\EasyHttp\Response 的实例，该实例提供了以下方法来检查请求的响应：

```php
$response->body() : string;
$response->json() : object;
$response->array() : array;
$response->status() : int;
$response->ok() : bool;
$response->successful() : bool;
$response->serverError() : bool;
$response->clientError() : bool;
$response->header($header) : string;
$response->headers() : array;
```

## 异常处理

请求在发生客户端或服务端错误时会抛出 Gouguoyin\EasyHttp\RequestException 异常，你可以在请求实例上调用 throw 方法：

```php
$response = Http::post(...);

// 在客户端或服务端错误发生时抛出异常
$response->throw();

return $response['user']['id'];
```

Gouguoyin\EasyHttp\RequestException 提供了以下方法来返回异常信息：

```php
$response->getCode() : int;
$response->getMessage() : string;
$response->getFile() : string;
$response->getLine() : int;
$response->getTrace() : array;
$response->getTraceAsString() : string;
```