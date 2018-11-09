<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\CartsRestApi;

use Spryker\Client\CartsRestApi\CartsRestApiDependencyProvider as SprykerCartsRestApiDependencyProvider;
use Spryker\Client\CartsRestApi\Plugin\QuoteCollectionReader\CartQuoteCollectionReaderPlugin;
use Spryker\Client\CartsRestApiExtension\Dependency\Plugin\QuoteCollectionReaderPluginInterface;

class CartsRestApiDependencyProvider extends SprykerCartsRestApiDependencyProvider
{
    /**
     * @return \Spryker\Client\CartsRestApiExtension\Dependency\Plugin\QuoteCollectionReaderPluginInterface
     */
    protected function getQuoteCollectionReaderPlugin(): QuoteCollectionReaderPluginInterface
    {
        return new CartQuoteCollectionReaderPlugin();
    }
}
