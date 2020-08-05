<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Benchmark\Yves\ProductDetailedPage;

use Benchmark\Yves\ProductDetailedPage\PageObject\ProductDetailedPage;
use Psr\Http\Message\ResponseInterface;
use SprykerSdk\Shared\Benchmark\Request\RequestBuilderInterface;
use SprykerSdk\Yves\Benchmark\Helper\Http\HttpHelperFactory;
use SprykerSdk\Yves\Benchmark\Request\RequestBuilderFactory;

class ProductDetailedPageBench
{
    protected const PRODUCT_ALIAS = 'samsung-galaxy-s5-mini-66';

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
    public function benchProductDetailedPageOpens(): ResponseInterface
    {
        $request = $this->requestBuilder->buildRequest(
            RequestBuilderInterface::METHOD_GET,
            sprintf(ProductDetailedPage::PRODUCT_DETAILED_PAGE_URL, static::PRODUCT_ALIAS)
        );

        return $this->httpHelper->send($request);
    }
}
