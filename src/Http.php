<?php

namespace Gouguoyin\EasyHttp;

/**
 * @method static \Gouguoyin\EasyHttp\Request asJson()
 * @method static \Gouguoyin\EasyHttp\Request asForm()
 * @method static \Gouguoyin\EasyHttp\Request attach(string $name, string $contents, string|null $filename = null, array $headers)
 *
 * @method static \Gouguoyin\EasyHttp\Request withRedirect(bool|array $redirect)
 * @method static \Gouguoyin\EasyHttp\Request withStream(bool $boolean)
 * @method static \Gouguoyin\EasyHttp\Request withVerify(bool|string $verify)
 * @method static \Gouguoyin\EasyHttp\Request withHeaders(array $headers)
 * @method static \Gouguoyin\EasyHttp\Request withBasicAuth(string $username, string $password)
 * @method static \Gouguoyin\EasyHttp\Request withDigestAuth(string $username, string $password)
 * @method static \Gouguoyin\EasyHttp\Request withToken(string $token, string $type = 'Bearer')
 * @method static \Gouguoyin\EasyHttp\Request withCookies(array $cookies, string $domain)
 * @method static \Gouguoyin\EasyHttp\Request withProxy(string|array $proxy)
 * @method static \Gouguoyin\EasyHttp\Request withVersion(string $version)
 * @method static \Gouguoyin\EasyHttp\Request withOptions(array $options)
 *
 * @method static \Gouguoyin\EasyHttp\Request retry(int $times, int $sleep = 0)
 * @method static \Gouguoyin\EasyHttp\Request timeout(int $seconds)
 * @method static \Gouguoyin\EasyHttp\Request concurrency(int $times)
 * @method static \Gouguoyin\EasyHttp\Request promise(array $promises, callable $success = null, callable $fail = null)
 *
 * @method static \Gouguoyin\EasyHttp\Request get(string $url, array $query = [])
 * @method static \Gouguoyin\EasyHttp\Request post(string $url, array $data = [])
 * @method static \Gouguoyin\EasyHttp\Request patch(string $url, array $data = [])
 * @method static \Gouguoyin\EasyHttp\Request put(string $url, array $data = [])
 * @method static \Gouguoyin\EasyHttp\Request delete(string $url, array $data = [])
 * @method static \Gouguoyin\EasyHttp\Request head(string $url, array $data = [])
 * @method static \Gouguoyin\EasyHttp\Request options(string $url, array $data = [])
 *
 * @method static \Gouguoyin\EasyHttp\Request getAsync(string $url, array $query = [], callable $success = null, callable $fail = null)
 * @method static \Gouguoyin\EasyHttp\Request postAsync(string $url, array $data = [], callable $success = null, callable $fail = null)
 * @method static \Gouguoyin\EasyHttp\Request patchAsync(string $url, array $data = [], callable $success = null, callable $fail = null)
 * @method static \Gouguoyin\EasyHttp\Request putAsync(string $url, array $data = [], callable $success = null, callable $fail = null)
 * @method static \Gouguoyin\EasyHttp\Request deleteAsync(string $url, array $data = [], callable $success = null, callable $fail = null)
 * @method static \Gouguoyin\EasyHttp\Request headAsync(string $url, array $data = [], callable $success = null, callable $fail = null)
 * @method static \Gouguoyin\EasyHttp\Request optionsAsync(string $url, array $data = [], callable $success = null, callable $fail = null)
 */
class Http extends Facade
{
    protected $facade = Request::class;
}
