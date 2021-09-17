<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MerchantOms;

use Pyz\Zed\MerchantOms\Communication\Plugin\Oms\CancelMarketplaceOrderItemCommandPlugin;
use Pyz\Zed\MerchantOms\Communication\Plugin\Oms\CancelReturnMarketplaceOrderItemCommandPlugin;
use Pyz\Zed\MerchantOms\Communication\Plugin\Oms\DeliverMarketplaceOrderItemCommandPlugin;
use Pyz\Zed\MerchantOms\Communication\Plugin\Oms\DeliverReturnMarketplaceOrderItemCommandPlugin;
use Pyz\Zed\MerchantOms\Communication\Plugin\Oms\ExecuteReturnMarketplaceOrderItemCommandPlugin;
use Pyz\Zed\MerchantOms\Communication\Plugin\Oms\RefundMarketplaceOrderItemCommandPlugin;
use Pyz\Zed\MerchantOms\Communication\Plugin\Oms\ShipByMerchantMarketplaceOrderItemCommandPlugin;
use Pyz\Zed\MerchantOms\Communication\Plugin\Oms\ShipReturnMarketplaceOrderItemCommandPlugin;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\MerchantOms\MerchantOmsDependencyProvider as SprykerMerchantOmsDependencyProvider;

/**
 * @method \Spryker\Zed\MerchantOms\MerchantOmsConfig getConfig()
 */
class MerchantOmsDependencyProvider extends SprykerMerchantOmsDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_OMS = 'FACADE_OMS';
    /**
     * @var string
     */
    public const FACADE_SALES_RETURN = 'FACADE_SALES_RETURN';

    /**
     * @return array<\Spryker\Zed\StateMachine\Dependency\Plugin\CommandPluginInterface>
     */
    protected function getStateMachineCommandPlugins(): array
    {
        return [
            'MarketplaceOrder/ShipOrderItem' => new ShipByMerchantMarketplaceOrderItemCommandPlugin(),
            'MarketplaceOrder/DeliverOrderItem' => new DeliverMarketplaceOrderItemCommandPlugin(),
            'MarketplaceOrder/Refund' => new RefundMarketplaceOrderItemCommandPlugin(),
            'MarketplaceOrder/CancelOrderItem' => new CancelMarketplaceOrderItemCommandPlugin(),
            'MarketplaceReturn/CancelReturnForOrderItem' => new CancelReturnMarketplaceOrderItemCommandPlugin(),
            'MarketplaceReturn/DeliverReturnForOrderItem' => new DeliverReturnMarketplaceOrderItemCommandPlugin(),
            'MarketplaceReturn/ExecuteReturnForOrderItem' => new ExecuteReturnMarketplaceOrderItemCommandPlugin(),
            'MarketplaceReturn/ShipReturnForOrderItem' => new ShipReturnMarketplaceOrderItemCommandPlugin(),
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
        $container = $this->addSalesReturnFacade($container);

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

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function addSalesReturnFacade(Container $container): Container
    {
        $container->set(static::FACADE_SALES_RETURN, function (Container $container) {
            return $container->getLocator()->salesReturn()->facade();
        });

        return $container;
    }
}
