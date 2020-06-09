<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Oms;

use Pyz\Zed\MerchantOms\Communication\Plugin\Oms\CloseMerchantOrderItemCommandPlugin;
use Pyz\Zed\MerchantSalesOrder\Communication\Plugin\Oms\Condition\IsOrderPaidConditionPlugin;
use Pyz\Zed\MerchantSalesOrder\Communication\Plugin\Oms\CreateMerchantOrdersCommandPlugin;
use Spryker\Zed\Availability\Communication\Plugin\AvailabilityHandlerPlugin;
use Spryker\Zed\GiftCard\Communication\Plugin\Oms\Command\CreateGiftCardCommandPlugin;
use Spryker\Zed\GiftCard\Communication\Plugin\Oms\Condition\IsGiftCardConditionPlugin;
use Spryker\Zed\GiftCardMailConnector\Communication\Plugin\Oms\Command\ShipGiftCardByEmailCommandPlugin;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Oms\Communication\Plugin\Oms\Command\SendOrderConfirmationPlugin;
use Spryker\Zed\Oms\Communication\Plugin\Oms\Command\SendOrderShippedPlugin;
use Spryker\Zed\Oms\Dependency\Plugin\Command\CommandCollectionInterface;
use Spryker\Zed\Oms\Dependency\Plugin\Condition\ConditionCollectionInterface;
use Spryker\Zed\Oms\OmsDependencyProvider as SprykerOmsDependencyProvider;
use Spryker\Zed\ProductBundle\Communication\Plugin\Oms\ProductBundleAvailabilityHandlerPlugin;
use Spryker\Zed\ProductPackagingUnit\Communication\Plugin\Oms\ProductPackagingUnitReservationAggregationStrategyPlugin;
use Spryker\Zed\ProductPackagingUnit\Communication\Plugin\Reservation\LeadProductReservationHandlerPlugin;
use Spryker\Zed\SalesReturn\Communication\Plugin\Oms\Command\StartReturnCommandPlugin;
use Spryker\Zed\Shipment\Dependency\Plugin\Oms\ShipmentManualEventGrouperPlugin;
use Spryker\Zed\Shipment\Dependency\Plugin\Oms\ShipmentOrderMailExpanderPlugin;
use SprykerEco\Zed\Payone\Communication\Plugin\Oms\Command\AuthorizeCommandPlugin;
use SprykerEco\Zed\Payone\Communication\Plugin\Oms\Command\CancelCommandPlugin;
use SprykerEco\Zed\Payone\Communication\Plugin\Oms\Command\CaptureCommandPlugin;
use SprykerEco\Zed\Payone\Communication\Plugin\Oms\Command\CaptureWithSettlementCommandPlugin;
use SprykerEco\Zed\Payone\Communication\Plugin\Oms\Command\PartialRefundCommandPlugin;
use SprykerEco\Zed\Payone\Communication\Plugin\Oms\Command\PreAuthorizeCommandPlugin;
use SprykerEco\Zed\Payone\Communication\Plugin\Oms\Command\RefundCommandPlugin;
use SprykerEco\Zed\Payone\Communication\Plugin\Oms\Condition\AuthorizationIsApprovedConditionPlugin;
use SprykerEco\Zed\Payone\Communication\Plugin\Oms\Condition\AuthorizationIsErrorConditionPlugin;
use SprykerEco\Zed\Payone\Communication\Plugin\Oms\Condition\AuthorizationIsRedirectConditionPlugin;
use SprykerEco\Zed\Payone\Communication\Plugin\Oms\Condition\CaptureIsApprovedConditionPlugin;
use SprykerEco\Zed\Payone\Communication\Plugin\Oms\Condition\PartialRefundIsApprovedConditionPlugin;
use SprykerEco\Zed\Payone\Communication\Plugin\Oms\Condition\PartialRefundIsErrorConditionPlugin;
use SprykerEco\Zed\Payone\Communication\Plugin\Oms\Condition\PaymentIsAppointedConditionPlugin;
use SprykerEco\Zed\Payone\Communication\Plugin\Oms\Condition\PaymentIsCaptureConditionPlugin;
use SprykerEco\Zed\Payone\Communication\Plugin\Oms\Condition\PaymentIsOverpaidConditionPlugin;
use SprykerEco\Zed\Payone\Communication\Plugin\Oms\Condition\PaymentIsPaidConditionPlugin;
use SprykerEco\Zed\Payone\Communication\Plugin\Oms\Condition\PaymentIsRefundConditionPlugin;
use SprykerEco\Zed\Payone\Communication\Plugin\Oms\Condition\PaymentIsUnderPaidConditionPlugin;
use SprykerEco\Zed\Payone\Communication\Plugin\Oms\Condition\PreAuthorizationIsApprovedConditionPlugin;
use SprykerEco\Zed\Payone\Communication\Plugin\Oms\Condition\PreAuthorizationIsErrorConditionPlugin;
use SprykerEco\Zed\Payone\Communication\Plugin\Oms\Condition\PreAuthorizationIsRedirectConditionPlugin;
use SprykerEco\Zed\Payone\Communication\Plugin\Oms\Condition\RefundIsApprovedConditionPlugin;
use SprykerEco\Zed\Payone\Communication\Plugin\Oms\Condition\RefundIsPossibleConditionPlugin;

class OmsDependencyProvider extends SprykerOmsDependencyProvider
{
    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->extendCommandPlugins($container);
        $container = $this->extendConditionPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function extendCommandPlugins(Container $container): Container
    {
        $container->extend(self::COMMAND_PLUGINS, function (CommandCollectionInterface $commandCollection) {
            $commandCollection->add(new SendOrderConfirmationPlugin(), 'Oms/SendOrderConfirmation');
            $commandCollection->add(new SendOrderShippedPlugin(), 'Oms/SendOrderShipped');
            $commandCollection->add(new ShipGiftCardByEmailCommandPlugin(), 'GiftCardMailConnector/ShipGiftCard');
            $commandCollection->add(new CreateGiftCardCommandPlugin(), 'GiftCard/CreateGiftCard');
            $commandCollection->add(new CreateMerchantOrdersCommandPlugin(), 'MerchantSalesOrder/CreateOrders');
            $commandCollection->add(new CloseMerchantOrderItemCommandPlugin(), 'MerchantOms/CloseOrderItem');
            $commandCollection->add(new StartReturnCommandPlugin(), 'Return/StartReturn');
            $commandCollection->add(new PreAuthorizeCommandPlugin(), 'Payone/PreAuthorize');
            $commandCollection->add(new AuthorizeCommandPlugin(), 'Payone/Authorize');
            $commandCollection->add(new CancelCommandPlugin(), 'Payone/Cancel');
            $commandCollection->add(new CaptureCommandPlugin(), 'Payone/Capture');
            $commandCollection->add(new CaptureWithSettlementCommandPlugin(), 'Payone/CaptureWithSettlement');
            $commandCollection->add(new RefundCommandPlugin(), 'Payone/Refund');
            $commandCollection->add(new PartialRefundCommandPlugin(), 'Payone/PartialRefund');

            return $commandCollection;
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function extendConditionPlugins(Container $container): Container
    {
        $container->extend(OmsDependencyProvider::CONDITION_PLUGINS, function (ConditionCollectionInterface $conditionCollection) {
            $conditionCollection
                ->add(new IsGiftCardConditionPlugin(), 'GiftCard/IsGiftCard');
            $conditionCollection
                ->add(new IsOrderPaidConditionPlugin(), 'MerchantSalesOrder/IsOrderPaid');
            $conditionCollection->add(new PreAuthorizationIsApprovedConditionPlugin(), 'Payone/PreAuthorizationIsApproved');
            $conditionCollection->add(new AuthorizationIsApprovedConditionPlugin(), 'Payone/AuthorizationIsApproved');
            $conditionCollection->add(new CaptureIsApprovedConditionPlugin(), 'Payone/CaptureIsApproved');
            $conditionCollection->add(new RefundIsApprovedConditionPlugin(), 'Payone/RefundIsApproved');
            $conditionCollection->add(new RefundIsPossibleConditionPlugin(), 'Payone/RefundIsPossible');
            $conditionCollection->add(new PreAuthorizationIsErrorConditionPlugin(), 'Payone/PreAuthorizationIsError');
            $conditionCollection->add(new AuthorizationIsErrorConditionPlugin(), 'Payone/AuthorizationIsError');
            $conditionCollection->add(new PreAuthorizationIsRedirectConditionPlugin(), 'Payone/PreAuthorizationIsRedirect');
            $conditionCollection->add(new AuthorizationIsRedirectConditionPlugin(), 'Payone/AuthorizationIsRedirect');
            $conditionCollection->add(new PaymentIsAppointedConditionPlugin(), 'Payone/PaymentIsAppointed');
            $conditionCollection->add(new PaymentIsCaptureConditionPlugin(), 'Payone/PaymentIsCapture');
            $conditionCollection->add(new PaymentIsPaidConditionPlugin(), 'Payone/PaymentIsPaid');
            $conditionCollection->add(new PaymentIsUnderPaidConditionPlugin(), 'Payone/PaymentIsUnderPaid');
            $conditionCollection->add(new PaymentIsOverpaidConditionPlugin(), 'Payone/PaymentIsOverpaid');
            $conditionCollection->add(new PaymentIsRefundConditionPlugin(), 'Payone/PaymentIsRefund');
            $conditionCollection->add(new PartialRefundIsApprovedConditionPlugin(), 'Payone/PartialRefundIsApproved');
            $conditionCollection->add(new PartialRefundIsErrorConditionPlugin(), 'Payone/PartialRefundIsError');

            return $conditionCollection;
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Oms\Dependency\Plugin\ReservationHandlerPluginInterface[]
     */
    protected function getReservationHandlerPlugins(Container $container): array
    {
        return [
            new AvailabilityHandlerPlugin(),
            new ProductBundleAvailabilityHandlerPlugin(),
            new LeadProductReservationHandlerPlugin(),
        ];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\OmsExtension\Dependency\Plugin\OmsOrderMailExpanderPluginInterface[]
     */
    protected function getOmsOrderMailExpanderPlugins(Container $container): array
    {
        return [
            new ShipmentOrderMailExpanderPlugin(),
        ];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\OmsExtension\Dependency\Plugin\OmsManualEventGrouperPluginInterface[]
     */
    protected function getOmsManualEventGrouperPlugins(Container $container): array
    {
        return [
            new ShipmentManualEventGrouperPlugin(),
        ];
    }

    /**
     * @return \Spryker\Zed\OmsExtension\Dependency\Plugin\ReservationAggregationStrategyPluginInterface[]
     */
    protected function getReservationAggregationStrategyPlugins(): array
    {
        return [
            new ProductPackagingUnitReservationAggregationStrategyPlugin(),
        ];
    }
}
