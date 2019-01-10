<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\QuickOrder;

use Spryker\Client\PriceProductStorage\Plugin\QuickOrder\QuickOrderPriceValidationPlugin;
use Spryker\Client\ProductDiscontinuedStorage\Plugin\QuickOrder\QuickOrderDiscontinuedValidationPlugin;
use Spryker\Client\ProductMeasurementUnitStorage\Plugin\QuickOrder\ProductConcreteTransferBaseMeasurementUnitExpanderPlugin;
use Spryker\Client\ProductQuantityStorage\Plugin\QuickOrder\QuickOrderQuantityValidationPlugin;
use Spryker\Client\QuickOrder\QuickOrderDependencyProvider as SprykerQuickOrderDependencyProvider;

class QuickOrderDependencyProvider extends SprykerQuickOrderDependencyProvider
{
    /**
     * @return \Spryker\Client\QuickOrderExtension\Dependency\Plugin\ProductConcreteExpanderPluginInterface[]
     */
    protected function getProductConcreteExpanderPlugins(): array
    {
        return [
            new ProductConcreteTransferBaseMeasurementUnitExpanderPlugin(),
        ];
    }

    /**
     * @return \Spryker\Client\QuickOrderExtension\Dependency\Plugin\QuickOrderValidatorPluginInterface[]
     */
    protected function getQuickOrderValidationPlugins(): array
    {
        return [
            new QuickOrderDiscontinuedValidationPlugin(),
            new QuickOrderPriceValidationPlugin(),
            new QuickOrderQuantityValidationPlugin(),
        ];
    }
}
