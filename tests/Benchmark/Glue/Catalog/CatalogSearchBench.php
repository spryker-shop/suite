<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Benchmark\Glue\Catalog;

use Benchmark\Glue\Catalog\PageObject\CatalogPage;
use Psr\Http\Message\ResponseInterface;
use SprykerSdk\Glue\Benchmark\Helper\Http\HttpHelperFactory;
use SprykerSdk\Glue\Benchmark\Request\RequestBuilderFactory;
use SprykerSdk\Shared\Benchmark\Request\RequestBuilderInterface;

class CatalogSearchBench
{
    /**
     * @var string
     */
    protected const SEARCH_QUERY_VALUE = 'Acer';

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
    public function benchCatalogSearch(): ResponseInterface
    {
        $request = $this->requestBuilder->buildRequest(
            RequestBuilderInterface::METHOD_GET,
            sprintf(CatalogPage::ENDPOINT_CATALOG_SEARCH, static::SEARCH_QUERY_VALUE),
        );

        return $this->httpHelper->send($request);
    }
}
