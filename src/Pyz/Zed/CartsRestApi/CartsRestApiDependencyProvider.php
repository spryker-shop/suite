<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CartsRestApi;

use Spryker\Zed\CartsRestApi\CartsRestApiDependencyProvider as SprykerCartsRestApiDependencyProvider;
use Spryker\Zed\MultiCartsRestApi\Communication\Plugin\CartsRestApi\QuoteCollectionReader\MultipleQuoteCollectionReaderPlugin;
use Spryker\Zed\MultiCartsRestApi\Communication\Plugin\CartsRestApi\QuoteCreator\MultipleQuoteCreatorPlugin;

class CartsRestApiDependencyProvider extends SprykerCartsRestApiDependencyProvider
{
    /**
     * @return \Spryker\Zed\CartsRestApiExtension\Dependency\Plugin\QuoteCollectionReaderPluginInterface[]
     */
    protected function getQuoteCollectionReaderPlugins(): array
    {
        return [
            new MultipleQuoteCollectionReaderPlugin(),
        ];
    }

    /**
     * @return \Spryker\Zed\CartsRestApiExtension\Dependency\Plugin\QuoteCreatorPluginInterface[]
     */
    protected function getQuoteCreatorPlugins(): array
    {
        return [
            new MultipleQuoteCreatorPlugin(),
        ];
    }
}
