<?php

namespace Benchmark\Spryker\Benchmark;

use Benchmark\Spryker\Request;

class ApiCatalogSearchBench
{

    public function benchApiCatalogSearch()
    {
        $headers = [
            'Connection' => 'keep-alive',
            'Cache-Control' => 'max-age=0',
            'Accept-Language' => 'en-US,en;q=0.9',
            'Accept-Encoding' => 'gzip, deflate',
        ];

        return Request::get(GLUE_BASE_URL . '/catalog-search-suggestions?q=Acer&rand=' . uniqid(), $headers, 200);
    }
}
