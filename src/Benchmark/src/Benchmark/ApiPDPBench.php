<?php

namespace Benchmark\Spryker\Benchmark;

use Benchmark\Spryker\Request;

class ApiPDPBench
{
    public function benchApiPDPPage()
    {
        $headers = [
            'Connection' => 'keep-alive',
            'Cache-Control' => 'max-age=0',
            'Accept-Language' => 'en-US,en;q=0.9',
            'Accept-Encoding' => 'gzip, deflate',
        ];

        return Request::get(GLUE_BASE_URL . '/concrete-products/066_23294028?rand=' . uniqid(), $headers, 200);
    }
}
