<?php

namespace Benchmark\Spryker\Benchmark;

use Benchmark\Spryker\Request;

class ApiAddToCartBench
{
    public function benchApiAddToCard()
    {
        $headers = [
            'Cache-Control' => 'max-age=0',
            'Accept-Language' => 'en-US,en;q=0.9',
            'Content-Type' => 'application/json',
            'X-Anonymous-Customer-Unique-Id' => uniqid('', true)
        ];

        $requestData = [
            'data' => [
                'type' => 'guest-cart-items',
                'attributes' => [
                    'sku' => '066_23294028',
                    'quantity' => 1
                ]
            ]
        ];

        return Request::post(GLUE_BASE_URL . '/guest-cart-items', $headers, $requestData, 201);
    }
}
