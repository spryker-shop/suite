<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Benchmark\Glue\Catalog\PageObject;

class CatalogPage
{
    /**
     * @see \Spryker\Glue\CatalogSearchRestApi\CatalogSearchRestApiConfig::RESOURCE_CATALOG_SEARCH_SUGGESTIONS
     * @var string
     */
    public const ENDPOINT_CATALOG_SEARCH = '/catalog-search-suggestions?q=%s';
}
