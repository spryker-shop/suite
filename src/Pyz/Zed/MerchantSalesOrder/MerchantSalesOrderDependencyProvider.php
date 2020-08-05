<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MerchantSalesOrder;

use Spryker\Zed\Kernel\Container;
use Spryker\Zed\MerchantOms\Communication\Plugin\MerchantSalesOrder\EventTriggerMerchantOrderPostCreatePlugin;
use Spryker\Zed\MerchantOms\Communication\Plugin\MerchantSalesOrder\MerchantOmsMerchantOrderExpanderPlugin;
use Spryker\Zed\MerchantSalesOrder\MerchantSalesOrderDependencyProvider as SprykerMerchantSalesOrderDependencyProvider;

class MerchantSalesOrderDependencyProvider extends SprykerMerchantSalesOrderDependencyProvider
{
    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);
        $container = $this->addSalesFacade($container);

        return $container;
    }

    /**
     * @return \Spryker\Zed\MerchantSalesOrderExtension\Dependency\Plugin\MerchantOrderPostCreatePluginInterface[]
     */
    protected function getMerchantOrderPostCreatePlugins(): array
    {
        return [
            new EventTriggerMerchantOrderPostCreatePlugin(),
        ];
    }

    /**
     * @return \Spryker\Zed\MerchantSalesOrderExtension\Dependency\Plugin\MerchantOrderExpanderPluginInterface[]
     */
    protected function getMerchantOrderExpanderPlugins(): array
    {
        return [
            new MerchantOmsMerchantOrderExpanderPlugin(),
        ];
    }
}
