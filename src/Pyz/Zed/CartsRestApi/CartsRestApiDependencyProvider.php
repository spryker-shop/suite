<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CartsRestApi;

use Spryker\Zed\CartsRestApi\CartsRestApiDependencyProvider as SprykerCartsRestApiDependencyProvider;
use Spryker\Zed\CartsRestApiExtension\Dependency\Plugin\QuoteCollectionReaderPluginInterface;
use Spryker\Zed\CartsRestApiExtension\Dependency\Plugin\QuoteCreatorPluginInterface;
use Spryker\Zed\MultiCart\Communication\Plugin\CartsRestApi\QuoteCollectionReader\MultiQuoteCollectionReaderPlugin;
use Spryker\Zed\MultiCart\Communication\Plugin\CartsRestApi\QuoteCreator\MultiQuoteCreatorPlugin;

class CartsRestApiDependencyProvider extends SprykerCartsRestApiDependencyProvider
{
    /**
     * @return \Spryker\Zed\CartsRestApiExtension\Dependency\Plugin\QuoteCollectionReaderPluginInterface
     */
    protected function getQuoteCollectionReaderPlugin(): QuoteCollectionReaderPluginInterface
    {
        return new MultiQuoteCollectionReaderPlugin();
    }

    /**
     * @return \Spryker\Zed\CartsRestApiExtension\Dependency\Plugin\QuoteCreatorPluginInterface
     */
    protected function getQuoteCreatorPlugin(): QuoteCreatorPluginInterface
    {
        return new MultiQuoteCreatorPlugin();
    }
}
