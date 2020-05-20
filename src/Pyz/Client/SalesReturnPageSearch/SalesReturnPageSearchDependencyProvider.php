<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\SalesReturnPageSearch;

use Spryker\Client\SalesReturnPageSearch\Plugin\Elasticsearch\ResultFormatter\SalesReturnPageSearchResultFormatterPlugin;
use Spryker\Client\SalesReturnPageSearch\SalesReturnPageSearchDependencyProvider as SprykerSalesReturnPageSearchDependencyProvider;
use Spryker\Client\SearchElasticsearch\Plugin\QueryExpander\LocalizedQueryExpanderPlugin;

class SalesReturnPageSearchDependencyProvider extends SprykerSalesReturnPageSearchDependencyProvider
{
    /**
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\ResultFormatterPluginInterface[]
     */
    protected function getReturnReasonSearchResultFormatterPlugins(): array
    {
        return [
            new SalesReturnPageSearchResultFormatterPlugin(),
        ];
    }

    /**
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\QueryExpanderPluginInterface[]
     */
    protected function getReturnReasonSearchQueryExpanderPlugins(): array
    {
        return [
            new LocalizedQueryExpanderPlugin(),
        ];
    }
}
