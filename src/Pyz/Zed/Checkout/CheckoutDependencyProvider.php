<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Zed\Checkout;

use Spryker\Zed\Availability\Communication\Plugin\ProductsAvailableCheckoutPreConditionPlugin;
use Spryker\Zed\CartNote\Communication\Plugin\Checkout\CartNoteSaverPlugin;
use Spryker\Zed\Checkout\CheckoutDependencyProvider as SprykerCheckoutDependencyProvider;
use Spryker\Zed\Customer\Communication\Plugin\Checkout\CustomerAddressSalutationCheckoutPreConditionPlugin;
use Spryker\Zed\Customer\Communication\Plugin\Checkout\CustomerOrderSavePlugin;
use Spryker\Zed\Customer\Communication\Plugin\Checkout\CustomerSalutationCheckoutPreConditionPlugin;
use Spryker\Zed\Customer\Communication\Plugin\CustomerPreConditionCheckerPlugin;
use Spryker\Zed\Discount\Communication\Plugin\Checkout\DiscountOrderSavePlugin;
use Spryker\Zed\Discount\Communication\Plugin\Checkout\VoucherDiscountMaxUsageCheckoutPreConditionPlugin;
use Spryker\Zed\DummyPayment\Communication\Plugin\Checkout\DummyPaymentCheckoutPostSavePlugin;
use Spryker\Zed\DummyPayment\Communication\Plugin\Checkout\DummyPaymentCheckoutPreConditionPlugin;
use Spryker\Zed\GiftCard\Communication\Plugin\Checkout\GiftCardCheckoutDoSaveOrderPlugin;
use Spryker\Zed\GiftCard\Communication\Plugin\Checkout\GiftCardCheckoutPreConditionPlugin;
use Spryker\Zed\GiftCardMailConnector\Communication\Plugin\Checkout\SendEmailToGiftCardUser;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Merchant\Communication\Plugin\Checkout\MerchantCheckoutPreConditionPlugin;
use Spryker\Zed\MerchantProductOption\Communication\Plugin\Checkout\MerchantProductOptionCheckoutPreConditionPlugin;
use Spryker\Zed\MerchantSwitcher\Communication\Plugin\Checkout\SingleMerchantCheckoutPreConditionPlugin;
use Spryker\Zed\Nopayment\Communication\Plugin\Checkout\NopaymentCheckoutPreConditionPlugin;
use Spryker\Zed\Payment\Communication\Plugin\Checkout\PaymentAuthorizationCheckoutPostSavePlugin;
use Spryker\Zed\Payment\Communication\Plugin\Checkout\PaymentConfirmPreOrderPaymentCheckoutPostSavePlugin;
use Spryker\Zed\Payment\Communication\Plugin\Checkout\PaymentMethodValidityCheckoutPreConditionPlugin;
use Spryker\Zed\ProductApproval\Communication\Plugin\Checkout\ProductApprovalCheckoutPreConditionPlugin;
use Spryker\Zed\ProductBundle\Communication\Plugin\Checkout\ProductBundleAvailabilityCheckoutPreConditionPlugin;
use Spryker\Zed\ProductBundle\Communication\Plugin\Checkout\ProductBundleOrderSaverPlugin;
use Spryker\Zed\ProductCartConnector\Communication\Plugin\Checkout\ProductExistsCheckoutPreConditionPlugin;
use Spryker\Zed\ProductConfigurationCart\Communication\Plugin\Checkout\ProductConfigurationCheckoutPreConditionPlugin;
use Spryker\Zed\ProductDiscontinued\Communication\Plugin\Checkout\ProductDiscontinuedCheckoutPreConditionPlugin;
use Spryker\Zed\ProductOffer\Communication\Plugin\Checkout\ProductOfferCheckoutPreConditionPlugin;
use Spryker\Zed\ProductOption\Communication\Plugin\Checkout\ProductOptionOrderSaverPlugin;
use Spryker\Zed\ProductPackagingUnit\Communication\Plugin\Checkout\AmountAvailabilityCheckoutPreConditionPlugin;
use Spryker\Zed\ProductQuantity\Communication\Plugin\Checkout\ProductQuantityRestrictionCheckoutPreConditionPlugin;
use Spryker\Zed\QuoteApproval\Communication\Plugin\Checkout\QuoteApprovalCheckoutPreConditionPlugin;
use Spryker\Zed\QuoteCheckoutConnector\Communication\Plugin\Checkout\DisallowedQuoteCheckoutPreConditionPlugin;
use Spryker\Zed\QuoteCheckoutConnector\Communication\Plugin\Checkout\DisallowQuoteCheckoutPreSavePlugin;
use Spryker\Zed\QuoteRequest\Communication\Plugin\Checkout\CloseQuoteRequestCheckoutPostSaveHookPlugin;
use Spryker\Zed\QuoteRequest\Communication\Plugin\Checkout\QuoteRequestPreCheckPlugin;
use Spryker\Zed\Sales\Communication\Plugin\Checkout\DuplicateOrderCheckoutPreConditionPlugin;
use Spryker\Zed\Sales\Communication\Plugin\Checkout\OrderItemsSaverPlugin;
use Spryker\Zed\Sales\Communication\Plugin\Checkout\OrderSaverPlugin;
use Spryker\Zed\Sales\Communication\Plugin\Checkout\OrderTotalsSaverPlugin;
use Spryker\Zed\Sales\Communication\Plugin\SalesOrderExpanderPlugin;
use Spryker\Zed\SalesOrderAmendmentOms\Communication\Plugin\Checkout\FinishOrderAmendmentCheckoutPostSavePlugin;
use Spryker\Zed\SalesOrderThreshold\Communication\Plugin\Checkout\SalesOrderThresholdCheckoutPreConditionPlugin;
use Spryker\Zed\SalesOrderThreshold\Communication\Plugin\Checkout\SalesOrderThresholdExpenseSavePlugin;
use Spryker\Zed\SalesPayment\Communication\Plugin\Checkout\SalesPaymentCheckoutDoSaveOrderPlugin;
use Spryker\Zed\SalesShipmentType\Communication\Plugin\Checkout\ShipmentTypeCheckoutDoSaveOrderPlugin;
use Spryker\Zed\ServicePointCart\Communication\Plugin\Checkout\ServicePointCheckoutPreConditionPlugin;
use Spryker\Zed\Shipment\Communication\Plugin\Checkout\SalesOrderShipmentSavePlugin;
use Spryker\Zed\ShipmentCheckoutConnector\Communication\Plugin\Checkout\ShipmentCheckoutPreCheckPlugin;
use Spryker\Zed\ShipmentTypeCart\Communication\Plugin\Checkout\ShipmentTypeCheckoutPreConditionPlugin;

class CheckoutDependencyProvider extends SprykerCheckoutDependencyProvider
{
    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return array<\Spryker\Zed\CheckoutExtension\Dependency\Plugin\CheckoutPreConditionPluginInterface>
     */
    protected function getCheckoutPreConditions(Container $container): array // phpcs:ignore SlevomatCodingStandard.Functions.UnusedParameter
    {
        return [
            new DisallowedQuoteCheckoutPreConditionPlugin(),
            new CustomerPreConditionCheckerPlugin(),
            new CustomerSalutationCheckoutPreConditionPlugin(),
            new CustomerAddressSalutationCheckoutPreConditionPlugin(),
            new ProductsAvailableCheckoutPreConditionPlugin(),
            new ProductBundleAvailabilityCheckoutPreConditionPlugin(),
            new GiftCardCheckoutPreConditionPlugin(),
            new NopaymentCheckoutPreConditionPlugin(),
            new DummyPaymentCheckoutPreConditionPlugin(),
            new ShipmentCheckoutPreCheckPlugin(),
            new ProductDiscontinuedCheckoutPreConditionPlugin(), #ProductDiscontinuedFeature
            new AmountAvailabilityCheckoutPreConditionPlugin(),
            new SalesOrderThresholdCheckoutPreConditionPlugin(), #SalesOrderThresholdFeature
            new VoucherDiscountMaxUsageCheckoutPreConditionPlugin(),
            new QuoteRequestPreCheckPlugin(),
            new QuoteApprovalCheckoutPreConditionPlugin(),
            new MerchantCheckoutPreConditionPlugin(),
            new SingleMerchantCheckoutPreConditionPlugin(),
            new PaymentMethodValidityCheckoutPreConditionPlugin(),
            new ProductConfigurationCheckoutPreConditionPlugin(),
            new DuplicateOrderCheckoutPreConditionPlugin(),
            new ProductOfferCheckoutPreConditionPlugin(),
            new MerchantProductOptionCheckoutPreConditionPlugin(),
            new ProductExistsCheckoutPreConditionPlugin(),
            new ProductApprovalCheckoutPreConditionPlugin(),
            new ShipmentTypeCheckoutPreConditionPlugin(),
            new ServicePointCheckoutPreConditionPlugin(),
            new ProductQuantityRestrictionCheckoutPreConditionPlugin(),
        ];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return array<\Spryker\Zed\Checkout\Dependency\Plugin\CheckoutSaveOrderInterface>|array<\Spryker\Zed\CheckoutExtension\Dependency\Plugin\CheckoutDoSaveOrderInterface>
     */
    protected function getCheckoutOrderSavers(Container $container): array // phpcs:ignore SlevomatCodingStandard.Functions.UnusedParameter
    {
        return [
            new CustomerOrderSavePlugin(),
            /*
             * Plugins
             * `OrderSaverPlugin`,
             * `OrderTotalsSaverPlugin`,
             * `SalesOrderShipmentSavePlugin`,
             * `ShipmentTypeCheckoutDoSaveOrderPlugin`
             * `OrderItemsSaverPlugin`,
             * must be enabled in the strict order.
             */
            new OrderSaverPlugin(),
            new OrderTotalsSaverPlugin(),
            new SalesOrderShipmentSavePlugin(),
            new ShipmentTypeCheckoutDoSaveOrderPlugin(),
            new OrderItemsSaverPlugin(),
            new CartNoteSaverPlugin(), #CartNoteFeature
            new ProductOptionOrderSaverPlugin(),
            new DiscountOrderSavePlugin(),
            new ProductBundleOrderSaverPlugin(),
            new SalesPaymentCheckoutDoSaveOrderPlugin(),
            new GiftCardCheckoutDoSaveOrderPlugin(),
            new SalesOrderThresholdExpenseSavePlugin(), #SalesOrderThresholdFeature
        ];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return array<\Spryker\Zed\CheckoutExtension\Dependency\Plugin\CheckoutPostSaveInterface>
     */
    protected function getCheckoutPostHooks(Container $container): array // phpcs:ignore SlevomatCodingStandard.Functions.UnusedParameter
    {
        return [
            new DummyPaymentCheckoutPostSavePlugin(),
            new CloseQuoteRequestCheckoutPostSaveHookPlugin(),
            new SendEmailToGiftCardUser(), #GiftCardFeature
            new PaymentAuthorizationCheckoutPostSavePlugin(),
            new PaymentConfirmPreOrderPaymentCheckoutPostSavePlugin(),
            new FinishOrderAmendmentCheckoutPostSavePlugin(),
        ];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return array<\Spryker\Zed\Checkout\Dependency\Plugin\CheckoutPreSaveHookInterface|\Spryker\Zed\Checkout\Dependency\Plugin\CheckoutPreSaveInterface|\Spryker\Zed\CheckoutExtension\Dependency\Plugin\CheckoutPreSavePluginInterface>
     */
    protected function getCheckoutPreSaveHooks(Container $container): array // phpcs:ignore SlevomatCodingStandard.Functions.UnusedParameter
    {
        return [
            new DisallowQuoteCheckoutPreSavePlugin(),
            new SalesOrderExpanderPlugin(),
        ];
    }
}
