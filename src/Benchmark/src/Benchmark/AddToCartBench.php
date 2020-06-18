<?php

namespace Benchmark\Spryker\Benchmark;

use Benchmark\Spryker\Request;
use PhpBench\Benchmark\Metadata\Annotations\AfterMethods;

/**
 * @AfterMethods({"removeItem"})
 */
class AddToCartBench
{
    public $cookie;

    public function setCookie()
    {
        $headers = [
            'Cache-Control' => 'max-age=0',
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Accept-Encoding' => 'gzip, deflate, br',
            'Accept-Language' => 'en-US,en;q=0.9'
        ];

        $body  = 'loginForm%5Bemail%5D=spencor.hopkin%40spryker.com&loginForm%5Bpassword%5D=change123';

        $cookie =  Request::login(YVES_BASE_URL . '/login_check', $headers, $body,302);

        $this->cookie = parse_url(YVES_BASE_URL, PHP_URL_HOST) . '=' . $cookie;
    }

    public function benchAddToCard()
    {
        if (empty($this->cookie)) {
            $this->setCookie();
        }

        $url = YVES_BASE_URL . '/en/cart/add/066_23294028';
        $headers = [
            'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3',
            'Connection' => 'keep-alive',
            'Cache-Control' => 'max-age=0',
            'Accept-Language' => 'en-US,en;q=0.9',
            'Upgrade-Insecure-Requests' => '1',
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Referer' => YVES_BASE_URL . '/en/samsung-galaxy-s5-mini-66',
            'Accept-Encoding' => 'gzip, deflate',
            'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.120 Safari/537.36',
            'Cookie' => $this->cookie
        ];

        return Request::get($url, $headers, 302);
    }

    public function removeItem()
    {
        if (empty($this->cookie)) {
            $this->setCookie();
        }

        $url = YVES_BASE_URL . '/cart/remove/066_23294028/066_23294028';

        $headers = [
            'Connection' => ' keep-alive',
            'Upgrade-Insecure-Requests' => ' 1',
            'User-Agent' => ' Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.120 Safari/537.36',
            'Accept' => ' text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3',
            'Referer' => YVES_BASE_URL . '/en/cart',
            'Accept-Encoding' => ' gzip, deflate',
            'Accept-Language' => ' en-US,en;q=0.9',
            'Cookie' => $this->cookie
        ];

        return Request::get($url, $headers, 302);
    }
}
