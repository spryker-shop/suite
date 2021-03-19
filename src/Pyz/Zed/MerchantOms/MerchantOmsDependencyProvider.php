<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MerchantOms;

use Pyz\Zed\MerchantOms\Communication\Plugin\Oms\DeliverMarketplaceOrderItemCommandPlugin;
use Pyz\Zed\MerchantOms\Communication\Plugin\Oms\MarketplaceRefundCommandPlugin;
use Pyz\Zed\MerchantOms\Communication\Plugin\Oms\MarketplaceStartReturnCommandPlugin;
use Pyz\Zed\MerchantOms\Communication\Plugin\Oms\ShipByMerchantMarketplaceOrderItemCommandPlugin;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\MerchantOms\MerchantOmsDependencyProvider as SprykerMerchantOmsDependencyProvider;

/**
 * @method \Spryker\Zed\MerchantOms\MerchantOmsConfig getConfig()
 */
class MerchantOmsDependencyProvider extends SprykerMerchantOmsDependencyProvider
{
    public const FACADE_OMS = 'FACADE_OMS';

    /**
     * @return \Spryker\Zed\StateMachine\Dependency\Plugin\CommandPluginInterface[]
     */
    protected function getStateMachineCommandPlugins(): array
    {
        return [
            'DummyMarketplacePayment/ShipOrderItem' => new ShipByMerchantMarketplaceOrderItemCommandPlugin(),
            'DummyMarketplacePayment/DeliverOrderItem' => new DeliverMarketplaceOrderItemCommandPlugin(),
            'DummyMarketplacePayment/Refund' => new MarketplaceRefundCommandPlugin(),
            'MarketplaceReturn/StartReturn' => new MarketplaceStartReturnCommandPlugin(),
        ];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);

        $container = $this->addOmsFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function addOmsFacade(Container $container): Container
    {
        $container->set(static::FACADE_OMS, function (Container $container) {
            return $container->getLocator()->oms()->facade();
        });

        return $container;
    }
}
