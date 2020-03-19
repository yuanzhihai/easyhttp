<?php

namespace Gouguoyin\EasyHttp;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Exception\ConnectException;

class Request
{
    /**
     * \GuzzleHttp\Client;
     * @var Client
     */
    protected $client;

    /**
     * @var string
     */
    protected $bodyFormat;

    /**
     * @var array
     */
    protected $options = [];

    /**
     * @var int
     */
    protected $tries = 1;

    public function __construct()
    {
        $this->client  = new Client();
        $this->bodyFormat = 'json';
        $this->options = [
            'http_errors' => false,
        ];
    }

    public function withoutRedirecting()
    {
        $this->options['allow_redirects'] = false;

        return $this;
    }

    public function withoutVerifying()
    {
        $this->options['verify'] = false;

        return $this;
    }

    public function withoutStream()
    {
        $this->options['stream'] = false;

        return $this;
    }

    public function withCert(string $path, string $password)
    {
        $this->options['cert'] = [$path, $password];

        return $this;
    }

    public function withHeaders(array $headers)
    {
        $this->options = array_merge_recursive($this->options, [
            'headers' => $headers,
        ]);

        return $this;
    }

    public function withBasicAuth(string $username, string $password)
    {
        $this->options['auth'] = [$username, $password];

        return $this;
    }

    public function withDigestAuth(string $username, string $password)
    {
        $this->options['auth'] = [$username, $password, 'digest'];

        return $this;
    }

    public function withToken(string $token, string $type = 'Bearer')
    {
        $this->options['headers']['Authorization'] = trim($type.' '.$token);

        return $this;
    }

    public function withCookies(array $cookies, string $domain)
    {
        $this->options = array_merge_recursive($this->options, [
            'cookies' => CookieJar::fromArray($cookies, $domain),
        ]);

        return $this;
    }

    public function withProxy($proxy)
    {
        $this->options['proxy'] = $proxy;

        return $this;
    }

    public function withVersion($version)
    {
        $this->options['version'] = $version;

        return $this;
    }

    public function retry(int $times, int $sleep = 0)
    {
        return $this;
    }

    public function delay($seconds)
    {
        $this->options['delay'] = $seconds * 1000;

        return $this;
    }

    public function timeout(int $seconds)
    {
        $this->options['timeout'] = $seconds;

        return $this;
    }

    public function attach(string $name, string $contents, string $filename = null, array $headers = [])
    {
        $this->options['multipart'] = array_filter([
            'name'     => $name,
            'contents' => $contents,
            'headers'  => $headers,
            'filename' => $filename,
        ]);

        return $this;
    }

    public function asForm()
    {
        $this->bodyFormat = 'form_params';
        $this->withHeaders(['Content-Type' => 'application/x-www-form-urlencoded']);

        return $this;
    }

    public function asMultipart()
    {
        $this->bodyFormat = 'multipart';

        return $this;
    }

    public function asJson()
    {
        $this->bodyFormat = 'json';
        $this->withHeaders(['Content-Type' => 'application/json']);

        return $this;
    }

    public function get(string $url, array $query = [])
    {
        $this->options['query'] = $query;

        return $this->request('GET', $url, $query);
    }

    public function post(string $url, array $data = [])
    {
        $this->options[$this->bodyFormat] = $data;

        return $this->request('POST', $url, $data);
    }

    public function patch(string $url, array $data = [])
    {
        $this->options[$this->bodyFormat] = $data;

        return $this->request('PATCH', $url, $data);
    }

    public function put(string $url, array $data = [])
    {
        $this->options[$this->bodyFormat] = $data;

        return $this->request('PUT', $url, $data);
    }

    public function delete(string $url, array $data = [])
    {
        $this->options[$this->bodyFormat] = $data;

        return $this->request('DELETE', $url, $data);
    }

    public function head(string $url, array $data = [])
    {
        $this->options[$this->bodyFormat] = $data;

        return $this->request('HEAD', $url, $data);
    }

    public function getAsync(string $url, array $query = [])
    {
        $this->options['query'] = $query;

        return $this->requestAsync('GET', $url, $query);
    }

    public function postAsync(string $url, array $data = [])
    {
        $this->options[$this->bodyFormat] = $data;

        return $this->requestAsync('POST', $url, $data);
    }

    public function patchAsync(string $url, array $data = [])
    {
        $this->options[$this->bodyFormat] = $data;

        return $this->requestAsync('PATCH', $url, $data);
    }

    public function putAsync(string $url, array $data = [])
    {
        $this->options[$this->bodyFormat] = $data;

        return $this->requestAsync('PUT', $url, $data);
    }

    public function deleteAsync(string $url, array $data = [])
    {
        $this->options[$this->bodyFormat] = $data;

        return $this->requestAsync('DELETE', $url, $data);
    }

    public function headAsync(string $url, array $data = [])
    {
        $this->options[$this->bodyFormat] = $data;

        return $this->requestAsync('HEAD', $url, $data);
    }

    protected function request(string $method, string $url, array $options = [])
    {
        isset($this->options[$this->bodyFormat]) && $this->options[$this->bodyFormat] = $options;

        try {
            $response = $this->client->request($method, $url, $this->options);
            return $this->response($response);
        } catch (ConnectException $e) {
            throw new ConnectionException($e->getMessage(), 0, $e);
        }
    }

    protected function requestAsync(string $method, string $url, array $options = [])
    {
        isset($this->options[$this->bodyFormat]) && $this->options[$this->bodyFormat] = $options;

        try {
            $promise = $this->client->requestAsync($method, $url, $this->options);
            return $this->response($promise);
        } catch (ConnectException $e) {
            throw new ConnectionException($e->getMessage(), 0, $e);
        }
    }

    protected function response($response)
    {
        return new Response($response);
    }

}
