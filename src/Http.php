<?php

namespace Gouguoyin\EasyHttp;

/**
 * @method static \Gouguoyin\EasyHttp\Request asJson()
 * @method static \Gouguoyin\EasyHttp\Request asForm()
 * @method static \Gouguoyin\EasyHttp\Request asMultipart()
 * @method static \Gouguoyin\EasyHttp\Request withoutRedirecting()
 * @method static \Gouguoyin\EasyHttp\Request withoutVerifying()
 * @method static \Gouguoyin\EasyHttp\Request withHeaders(array $headers)
 * @method static \Gouguoyin\EasyHttp\Request withBasicAuth(string $username, string $password)
 * @method static \Gouguoyin\EasyHttp\Request withDigestAuth(string $username, string $password)
 * @method static \Gouguoyin\EasyHttp\Request withToken(string $token, string $type = 'Bearer')
 * @method static \Gouguoyin\EasyHttp\Request withCookies(array $cookies, string $domain)
 * @method static \Gouguoyin\EasyHttp\Request retry(int $times, int $sleep = 0)
 * @method static \Gouguoyin\EasyHttp\Request timeout(int $seconds)
 * @method static \Gouguoyin\EasyHttp\Request attach(string $name, string $contents, string|null $filename = null, array $headers)
 * @method static \Gouguoyin\EasyHttp\Request get(string $url, array $query = [])
 * @method static \Gouguoyin\EasyHttp\Request post(string $url, array $data = [])
 * @method static \Gouguoyin\EasyHttp\Request patch(string $url, array $data = [])
 * @method static \Gouguoyin\EasyHttp\Request put(string $url, array $data = [])
 * @method static \Gouguoyin\EasyHttp\Request delete(string $url, array $data = [])
 * @method static \Gouguoyin\EasyHttp\Request head(string $url, array $data = [])
 * @method static \Gouguoyin\EasyHttp\Request getAsync(string $url, array $query = [])
 * @method static \Gouguoyin\EasyHttp\Request postAsync(string $url, array $data = [])
 * @method static \Gouguoyin\EasyHttp\Request patchAsync(string $url, array $data = [])
 * @method static \Gouguoyin\EasyHttp\Request putAsync(string $url, array $data = [])
 * @method static \Gouguoyin\EasyHttp\Request deleteAsync(string $url, array $data = [])
 * @method static \Gouguoyin\EasyHttp\Request headAsync(string $url, array $data = [])
 *
 * @method static \Illuminate\Http\Client\PendingRequest bodyFormat(string $format)
 * @method static \Illuminate\Http\Client\PendingRequest contentType(string $contentType)
 * @method static \Illuminate\Http\Client\PendingRequest acceptJson()
 * @method static \Illuminate\Http\Client\PendingRequest accept(string $contentType)
 * @method static \Illuminate\Http\Client\PendingRequest withOptions(array $options)
 * @method static \Illuminate\Http\Client\PendingRequest beforeSending(callable $callback)

 */
class Http extends Facade
{
    protected $facade = Request::class;
}
