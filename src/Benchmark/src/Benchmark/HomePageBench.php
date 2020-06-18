<?php

namespace Benchmark\Spryker\Benchmark;

use Benchmark\Spryker\Request;

class HomePageBench
{
    public function benchHomePage()
    {
        $headers = [
            'Connection' => 'keep-alive',
            'Cache-Control' => 'max-age=0',
            'Accept-Language' => 'en-US,en;q=0.9',
            'Accept-Encoding' => 'gzip, deflate',
        ];

        return Request::get(YVES_BASE_URL . '?rand=' . uniqid(), $headers, 200);
    }
}

