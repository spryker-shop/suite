<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ServicePointCart;

use Spryker\Zed\ClickAndCollectExample\Communication\Plugin\ServicePointCart\ClickAndCollectExampleDeliveryServicePointQuoteItemReplaceStrategyPlugin;
use Spryker\Zed\ClickAndCollectExample\Communication\Plugin\ServicePointCart\ClickAndCollectExamplePickupServicePointQuoteItemReplaceStrategyPlugin;
use Spryker\Zed\ServicePointCart\ServicePointCartDependencyProvider as SprykerServicePointCartDependencyProvider;

class ServicePointCartDependencyProvider extends SprykerServicePointCartDependencyProvider
{
    /**
     * @return list<\Spryker\Zed\ServicePointCartExtension\Dependency\Plugin\ServicePointQuoteItemReplaceStrategyPluginInterface>
     */
    protected function getServicePointQuoteItemReplaceStrategyPlugins(): array
    {
        return [
            new ClickAndCollectExampleDeliveryServicePointQuoteItemReplaceStrategyPlugin(),
            new ClickAndCollectExamplePickupServicePointQuoteItemReplaceStrategyPlugin(),
        ];
    }
}
