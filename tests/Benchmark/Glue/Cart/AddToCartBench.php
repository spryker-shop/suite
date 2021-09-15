<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Benchmark\Glue\Cart;

use Benchmark\Glue\Cart\PageObject\CartPage;
use Psr\Http\Message\ResponseInterface;
use SprykerSdk\Glue\Benchmark\Helper\Http\HttpHelperFactory;
use SprykerSdk\Glue\Benchmark\Request\RequestBuilderFactory;
use SprykerSdk\Shared\Benchmark\Request\RequestBuilderInterface;

class AddToCartBench
{
    /**
     * @var string
     */
    protected const PRODUCT_SKU = '035_17360369';

    /**
     * @var \SprykerSdk\Shared\Benchmark\Request\RequestBuilderInterface
     */
    protected $requestBuilder;

    /**
     * @var \SprykerSdk\Shared\Benchmark\Helper\Http\HttpHelperInterface
     */
    protected $httpHelper;

    /**
     * @return void
     */
    public function prepareRequestHelpers(): void
    {
        $this->requestBuilder = RequestBuilderFactory::createRequestBuilder();
        $this->httpHelper = HttpHelperFactory::createHttpHelper();
    }

    /**
     * @BeforeMethods({"prepareRequestHelpers"})
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function benchAddOneItemToCart(): ResponseInterface
    {
        $request = $this->requestBuilder->buildRequest(
            RequestBuilderInterface::METHOD_POST,
            CartPage::ENDPOINT_GUEST_CART,
            [
                'X-Anonymous-Customer-Unique-Id' => uniqid('', true),
            ],
            [
                'data' => [
                    'type' => 'guest-cart-items',
                    'attributes' => [
                        'sku' => static::PRODUCT_SKU,
                        'quantity' => 1,
                    ],
                ],
            ]
        );

        return $this->httpHelper->send($request, [], 201);
    }
}
