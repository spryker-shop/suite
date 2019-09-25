<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\SearchElasticsearch;

use Spryker\Client\Catalog\Plugin\Config\FacetConfigPlugin;
use Spryker\Client\Catalog\Plugin\Config\PaginationConfigPlugin;
use Spryker\Client\Catalog\Plugin\Config\SortConfigPlugin;
use Spryker\Client\Kernel\Container;
use Spryker\Client\ProductSearchConfigStorage\Plugin\Config\ProductSearchConfigExpanderPlugin;
use Spryker\Client\SearchElasticsearch\SearchElasticsearchDependencyProvider as SprykerSearchElasticsearchDependencyProvider;
use Spryker\Client\SearchExtension\Dependency\Plugin\FacetConfigPluginInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\PaginationConfigPluginInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\SortConfigPluginInterface;

class SearchElasticsearchDependencyProvider extends SprykerSearchElasticsearchDependencyProvider
{
    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\FacetConfigPluginInterface|null
     */
    protected function getFacetSearchConfigBuilderPlugin(Container $container): ?FacetConfigPluginInterface
    {
        return new FacetConfigPlugin();
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\PaginationConfigPluginInterface|null
     */
    protected function getPaginationSearchConfigBuilderPlugin(Container $container): ?PaginationConfigPluginInterface
    {
        return new PaginationConfigPlugin();
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\SortConfigPluginInterface|null
     */
    protected function getSortSearchConfigBuilderPlugin(Container $container): ?SortConfigPluginInterface
    {
        return new SortConfigPlugin();
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\SearchConfigExpanderPluginInterface[]
     */
    protected function getSearchConfigExpanderPlugins(Container $container): array
    {
        return [
            new ProductSearchConfigExpanderPlugin(),
        ];
    }
}
