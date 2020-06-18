<?php

namespace Benchmark\Spryker\Benchmark;

use Benchmark\Spryker\Request;

class CatalogSearchBench
{
    public function benchCatalogSearch()
    {
        $headers = [
            'Accept' => '*/*',
            'Connection' => 'keep-alive',
            'Cache-Control' => 'max-age=0',
            'Content-Type' => 'text/plain;charset=UTF-8',
            'Accept-Language' => 'en-US,en;q=0.9',
            'Upgrade-Insecure-Requests' => '1',
            'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.120 Safari/537.36',
        ];

        return Request::get(YVES_BASE_URL . '/search/suggestion?q=Acer&rand=' . uniqid(), $headers, 200);
    }
}
