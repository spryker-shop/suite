<?php

namespace Benchmark\Spryker\Benchmark;

use Benchmark\Spryker\Request;

class PDPBench
{
    public function benchPDPPage()
    {
        $headers = [
            'Connection' => 'keep-alive',
            'Cache-Control' => 'max-age=0',
            'Accept-Language' => 'en-US,en;q=0.9',
            'Accept-Encoding' => 'gzip, deflate',
        ];

        return Request::get(YVES_BASE_URL . '/en/samsung-galaxy-s5-mini-66?rand=' . uniqid(), $headers, 200);
    }
}
