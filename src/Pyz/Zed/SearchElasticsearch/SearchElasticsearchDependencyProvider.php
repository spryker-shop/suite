<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\SearchElasticsearch;

use Spryker\Zed\CategoryPageSearch\Communication\Plugin\Search\Elasticsearch\CategoryNodeDataPageMapBuilder;
use Spryker\Zed\CmsPageSearch\Communication\Plugin\Search\Elasticsearch\CmsDataPageMapBuilder;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Search\Elasticsearch\ProductConcretePageMapPlugin;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Search\Elasticsearch\ProductPageMapPlugin;
use Spryker\Zed\ProductSetPageSearch\Communication\Plugin\Search\Elasticsearch\ProductSetPageMapPlugin;
use Spryker\Zed\SearchElasticsearch\Communication\Plugin\Search\PageDataMapperPlugin;
use Spryker\Zed\SearchElasticsearch\SearchElasticsearchDependencyProvider as SprykerSearchElasticsearchDependencyProvider;

class SearchElasticsearchDependencyProvider extends SprykerSearchElasticsearchDependencyProvider
{
    /**
     * @return \Spryker\Zed\SearchElasticsearchExtension\Dependency\Plugin\PageMapPluginInterface[]
     */
    protected function getPageDataMapperPlugins(): array
    {
        return [
            new ProductPageMapPlugin(),
            new ProductConcretePageMapPlugin(),
            new ProductSetPageMapPlugin(),
            new CmsDataPageMapBuilder(),
            new CategoryNodeDataPageMapBuilder(),
        ];
    }

    /**
     * @return \Spryker\Zed\SearchElasticsearchExtension\Dependency\Plugin\ResourceDataMapperPluginInterface[]
     */
    protected function getResourceDataMapperPlugins(): array
    {
        return [
            new PageDataMapperPlugin(),
        ];
    }
}
