<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\ProductSetPageSearch;

use Spryker\Client\ProductSetPageSearch\Plugin\Elasticsearch\ResultFormatter\ProductSetPageSearchListResultFormatterPlugin;
use Spryker\Client\ProductSetPageSearch\ProductSetPageSearchDependencyProvider as SprykerProductSetPageSearchDependencyProvider;
use Spryker\Client\Search\Plugin\Elasticsearch\QueryExpander\LocalizedQueryExpanderPlugin;
use Spryker\Client\Search\Plugin\Elasticsearch\QueryExpander\StoreQueryExpanderPlugin;

class ProductSetPageSearchDependencyProvider extends SprykerProductSetPageSearchDependencyProvider
{
    /**
     * @return array<\Spryker\Client\SearchExtension\Dependency\Plugin\ResultFormatterPluginInterface|\Spryker\Client\Search\Dependency\Plugin\ResultFormatterPluginInterface>
     */
    protected function getProductSetListResultFormatterPlugins(): array
    {
        return [
            new ProductSetPageSearchListResultFormatterPlugin(),
        ];
    }

    /**
     * @return array<\Spryker\Client\Kernel\AbstractPlugin>
     */
    protected function getProductSetListQueryExpanderPlugins(): array
    {
        return [
            new LocalizedQueryExpanderPlugin(),
            new StoreQueryExpanderPlugin(),
        ];
    }
}
