<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Client\CartNote;

use Spryker\Client\CartNote\CartNoteDependencyProvider as SprykerCartNoteDependencyProvider;
use Spryker\Client\CartNoteExtension\Dependency\Plugin\QuoteItemFinderPluginInterface;
use Spryker\Client\CartNoteProductBundleConnector\Plugin\BundleProductQuoteItemFinderPlugin;

class CartNoteDependencyProvider extends SprykerCartNoteDependencyProvider
{
    /**
     * @return \Spryker\Client\CartNoteExtension\Dependency\Plugin\QuoteItemFinderPluginInterface
     */
    protected function getQuoteItemsFinderPlugin(): QuoteItemFinderPluginInterface
    {
        return new BundleProductQuoteItemFinderPlugin(); #CartNoteFeature
    }
}
