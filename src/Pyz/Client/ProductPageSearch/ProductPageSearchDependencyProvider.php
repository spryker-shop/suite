<?php

namespace Pyz\Client\ProductPageSearch;

use Spryker\Client\ProductPageSearch\Plugin\Elasticsearch\ResultFormatter\ProductConcretePageSearchResultFormatterPlugin;
use Spryker\Client\ProductPageSearch\ProductPageSearchDependencyProvider as SprykerProductPageSearchDependencyProvider;

class ProductPageSearchDependencyProvider extends SprykerProductPageSearchDependencyProvider
{
    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\ResultFormatterPluginInterface[]
     */
    protected function getProductConcretePageSearchResultFormatterPlugins(): array
    {
        return [
            new ProductConcretePageSearchResultFormatterPlugin(),
        ];
    }
}
