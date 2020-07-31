<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Benchmark\Yves\Catalog;

use Benchmark\Yves\Catalog\PageObject\CatalogPage;
use Psr\Http\Message\ResponseInterface;
use SprykerSdk\Shared\Benchmark\Request\RequestBuilderInterface;
use SprykerSdk\Yves\Benchmark\Helper\Http\HttpHelperFactory;
use SprykerSdk\Yves\Benchmark\Request\RequestBuilderFactory;

class CatalogSearchBench
{
    protected const SEARCH_QUERY = 'Acer';

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
    public function prepareRequestBuilder(): void
    {
        $this->requestBuilder = RequestBuilderFactory::createRequestBuilder();
        $this->httpHelper = HttpHelperFactory::createHttpHelper();
    }

    /**
     * @BeforeMethods({"prepareRequestBuilder"})
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function benchCatalogPageSearchQuery(): ResponseInterface
    {
        $request = $this->requestBuilder->buildRequest(
            RequestBuilderInterface::METHOD_GET,
            sprintf(CatalogPage::CATALOG_PAGE_SEARCH, static::SEARCH_QUERY)
        );

        return $this->httpHelper->send($request);
    }
}
