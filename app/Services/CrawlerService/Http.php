<?php

namespace App\Services\CrawlerService;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Cookie\SetCookie;
use Psr\Http\Message\ResponseInterface;

class Http
{

    /**
     * @var Client
     */
    private $httpClient;

    /**
     * @var array
     */
    private $headers;

    /**
     * @var CookieJar
     */
    private $cookieJar;

    /**
     * @var boolean
     */
    private $proxy;

    /**
     * @var string
     */
    private $proxyUrl;

    /**
     * Http constructor.
     */
    protected function __construct()
    {
        $this->httpClient = new Client();
        $this->cookieJar = new CookieJar();
        $this->initHeaders();
        $this->proxy = config('weeecrawler.proxy');
        $this->proxyUrl = config('weeecrawler.proxy_url');
    }

    /**
     * GET request
     *
     * @param $url
     * @return ResponseInterface
     */
    protected function get($url)
    {
        $options = [
            'verify' => false,
            'headers' => $this->headers,
            'cookies' => $this->cookieJar,
        ];

        if ($this->proxy) {
            $options['proxy'] = $this->proxyUrl;
            $options['curl'] = [CURLOPT_PROXYTYPE => 7];
        }

        dd($options);

        return $this->httpClient->request('GET', $url, $options);
    }

    /**
     * POST request
     *
     * @param $url
     * @param $formParams
     * @return ResponseInterface
     */
    public function post($url, $formParams)
    {
        $options = [
            'verify' => false,
            'headers' => $this->headers,
            'form_params' => $formParams,
            'cookies' => $this->cookieJar,
        ];

        if ($this->proxy) {
            $options['proxy'] = $this->proxyUrl;
            $options['curl'] = [CURLOPT_PROXYTYPE => 7];
        }

        return $this->httpClient->request('POST', $url, $options);
    }

    /**
     * Init headers
     */
    private function initHeaders()
    {
        $this->headers = [
            'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'Accept-Encoding' => 'gzip, deflate, br',
            'Accept-Language' => 'zh-CN,zh;q=0.9,en-US;q=0.8,en;q=0.7,zh-HK;q=0.6,en-GB;q=0.5',
            'Host' => 'tuangou.grubmarket.com',
            'User-Agent:' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.132 Safari/537.36',
            'Accept-Encoding' => 'gzip, deflate, br',
        ];
    }

    /**
     * Set cookie jar
     *
     * @param CookieJar $cookieJar
     */
    public function setCookieJar(CookieJar $cookieJar)
    {
        $this->cookieJar = $cookieJar;
    }

    /**
     * Get cookie jarCookieJar
     *
     * @return CookieJar
     */
    public function getCookieJar(): CookieJar
    {
        return $this->cookieJar;
    }

    /**
     * Set cookie
     *
     * @param SetCookie $setCookie
     */
    public function setCookie(SetCookie $setCookie)
    {
        $this->cookieJar->setCookie($setCookie);
    }

}
