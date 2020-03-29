EasyHttp 是一个轻量级、语义化、对IDE友好的HTTP客户端，支持常见的HTTP请求、异步请求和并发请求，让你可以快速地使用 HTTP 请求与其他 Web 应用进行通信。

> EasyHttp并不强制依赖于cURL，如果没有安装cURL，EasyHttp会自动选择使用PHP流处理，或者你也可以提供自己的发送HTTP请求的处理方式。

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

$response = Http::get('http://httpbin.org/get?name=gouguoyin');

$response = Http::get('http://httpbin.org/get?name=gouguoyin', ['age' => 18]);

$response = Http::post('http://httpbin.org/post');

$response = Http::post('http://httpbin.org/post', ['name' => 'gouguoyun']);

$response = Http::patch(...);

$response = Http::put(...);

$response = Http::delete(...);

$response = Http::head(...);

$response = Http::options(...);
```

###### 发送 Content-Type 编码请求

```php
// application/json(默认)
$response = Http::asJson()->post(...);

// application/x-www-form-urlencoded
$response = Http::asForm()->post(...);
```

###### 发送 Multipart 表单请求

```php
$response = Http::asMultipart(
    'file_input_name', file_get_contents('photo1.jpg'), 'photo2.jpg'
)->post('http://test.com/attachments');

$response = Http::asMultipart(
    'file_input_name', fopen('photo1.jpg', 'r'), 'photo2.jpg'
)->post(...);

$response = Http::attach(
    'file_input_name', file_get_contents('photo1.jpg'), 'photo2.jpg'
)->post(...);

$response = Http::attach(
    'file_input_name', fopen('photo1.jpg', 'r'), 'photo2.jpg'
)->post('http://test.com/attachments');
```
> 表单enctype属性需要设置成 multipart/form-data

###### 携带请求头的请求

```php
$response = Http::withHeaders([
    'x-powered-by' => 'gouguoyin'
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
// Basic认证
$response = Http::withBasicAuth('username', 'password')->post(...);

// Digest认证(需要被HTTP服务器支持)
$response = Http::withDigestAuth('username', 'password')->post(...);
```

###### 携带 User-Agent 的请求
```php
$response = Http::withUA('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3100.0 Safari/537.36')->post(...);
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
$response = Http::withVersion(1.0)->post(...);
```

###### 携带代理的请求

```php
$response = Http::withProxy('tcp://localhost:8125')->post(...);

$response = Http::withProxy([
    'http'  => 'tcp://localhost:8125', // Use this proxy with "http"
    'https' => 'tcp://localhost:9124', // Use this proxy with "https",
    'no'    => ['.com.cn', 'gouguoyin.cn']    // Don't use a proxy with these
])->post(...);
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
$response = Http::concurrency(10)->promise(...);
```

#### 异步请求

```php
use Gouguoyin\EasyHttp\Response;
use Gouguoyin\EasyHttp\RequestException;

Http::getAsync('http://easyhttp.gouguoyin.cn/api/sleep3.json', [], function (Response $response) {
    echo '异步请求成功，返回内容：' . $response->body() . PHP_EOL;
}, function (RequestException $e) {
    echo '异步请求异常，错误码：' . $e->getCode() . '，错误信息：' . $e->getMessage() . PHP_EOL;
});
echo json_encode(['code' => 200, 'msg' => '请求成功'], JSON_UNESCAPED_UNICODE) . PHP_EOL;

//输出
{"code":200,"msg":"请求成功"}
异步请求成功，返回内容：{"code":200,"msg":"success","second":3}

Http::postAsync(...);

Http::patchAsync(...);

Http::putAsync(...);

Http::deleteAsync(...);

Http::headAsync(...);

Http::optionsAsync(...);
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
$total = floor($etime - $stime);
echo "当前页面执行总时长：{$total} 秒" . PHP_EOL;

//输出
发起第 1 个请求，请求时长：1 秒
发起第 2 个请求，请求时长：2 秒
发起第 0 个请求，请求时长：3 秒
当前页面执行总时长：3 秒
```
> 如果未调用concurrency()方法，并发次数默认为$promises的元素个数

## 使用响应

发起请求后会返回一个 Gouguoyin\EasyHttp\Response $response的实例，该实例提供了以下方法来检查请求的响应：

```php
$response->body() : string;
$response->json() : object;
$response->array() : array;
$response->status() : int;
$response->ok() : bool;
$response->successful() : bool;
$response->serverError() : bool;
$response->clientError() : bool;
$response->headers() : array;
$response->header($header) : string;
```

## 异常处理

请求在发生客户端或服务端错误时会抛出 Gouguoyin\EasyHttp\RequestException 异常，你可以在请求实例上调用 throw 方法：

```php
$response = Http::post(...);

// 在客户端或服务端错误发生时抛出异常
$response->throw();

return $response['user']['id'];
```

Gouguoyin\EasyHttp\RequestException $e 提供了以下方法来返回异常信息：

```php
$e->getCode() : int;
$e->getMessage() : string;
$e->getFile() : string;
$e->getLine() : int;
$e->getTrace() : array;
$e->getTraceAsString() : string;
```
## 更新日志
### 2020-03-28
* 修复部分情况下IDE不能智能提示的BUG
* get()、getAsync()方法支持带参数的url
* 新增withUA()方法
* 新增withStream()方法
* 新增asMultipart()方法，attach()的别名

### 2020-03-20
* 新增异步请求getAsync()方法
* 新增异步请求postAsync()方法
* 新增异步请求patchAsync()方法
* 新增异步请求putAsync()方法
* 新增异步请求deleteAsync()方法
* 新增异步请求headAsync()方法
* 新增异步请求optionsAsync()方法
* 新增并发请求promise()方法