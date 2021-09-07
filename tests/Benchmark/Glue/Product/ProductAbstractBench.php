<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Benchmark\Glue\Product;

use Benchmark\Glue\Product\PageObject\ProductPage;
use Psr\Http\Message\ResponseInterface;
use SprykerSdk\Glue\Benchmark\Helper\Http\HttpHelperFactory;
use SprykerSdk\Glue\Benchmark\Request\RequestBuilderFactory;
use SprykerSdk\Shared\Benchmark\Request\RequestBuilderInterface;

class ProductAbstractBench
{
    /**
     * @var string
     */
    protected const PRODUCT_SKU = '066';

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
    public function benchRetrieveOfSingleAbstractProduct(): ResponseInterface
    {
        $request = $this->requestBuilder->buildRequest(
            RequestBuilderInterface::METHOD_GET,
            sprintf(ProductPage::ENDPOINT_PRODUCT_ABSTRACT, static::PRODUCT_SKU)
        );

        return $this->httpHelper->send($request);
    }
}
