<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MerchantSalesOrder;

use Spryker\Zed\DiscountMerchantSalesOrder\Communication\Plugin\MerchantSalesOrder\DiscountMerchantOrderFilterPlugin;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\MerchantOms\Communication\Plugin\MerchantSalesOrder\EventTriggerMerchantOrderPostCreatePlugin;
use Spryker\Zed\MerchantOms\Communication\Plugin\MerchantSalesOrder\MerchantOmsMerchantOrderExpanderPlugin;
use Spryker\Zed\MerchantSalesOrder\MerchantSalesOrderDependencyProvider as SprykerMerchantSalesOrderDependencyProvider;
use Spryker\Zed\MerchantSalesOrderSalesMerchantCommission\Communication\Plugin\MerchantSalesOrder\UpdateMerchantCommissionTotalsMerchantOrderPostCreatePlugin;

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
     * @return array<\Spryker\Zed\MerchantSalesOrderExtension\Dependency\Plugin\MerchantOrderPostCreatePluginInterface>
     */
    protected function getMerchantOrderPostCreatePlugins(): array
    {
        return [
            new UpdateMerchantCommissionTotalsMerchantOrderPostCreatePlugin(),
            new EventTriggerMerchantOrderPostCreatePlugin(),
        ];
    }

    /**
     * @return array<\Spryker\Zed\MerchantSalesOrderExtension\Dependency\Plugin\MerchantOrderExpanderPluginInterface>
     */
    protected function getMerchantOrderExpanderPlugins(): array
    {
        return [
            new MerchantOmsMerchantOrderExpanderPlugin(),
        ];
    }

    /**
     * @return array<\Spryker\Zed\MerchantSalesOrderExtension\Dependency\Plugin\MerchantOrderFilterPluginInterface>
     */
    protected function getMerchantOrderFilterPlugins(): array
    {
        return [
            new DiscountMerchantOrderFilterPlugin(),
        ];
    }
}
