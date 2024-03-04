<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CheckoutPage;

use Generated\Shared\Transfer\PaymentTransfer;
use Spryker\Shared\DummyMarketplacePayment\DummyMarketplacePaymentConfig;
use Spryker\Shared\Kernel\Container\GlobalContainer;
use Spryker\Shared\Nopayment\NopaymentConfig;
use Spryker\Yves\DummyMarketplacePayment\Plugin\StepEngine\DummyMarketplacePaymentHandlerPlugin;
use Spryker\Yves\DummyMarketplacePayment\Plugin\StepEngine\SubForm\DummyMarketplacePaymentInvoiceSubFormPlugin;
use Spryker\Yves\Kernel\Container;
use Spryker\Yves\MerchantShipment\Plugin\CheckoutPage\MerchantShipmentCheckoutPageStepEnginePreRenderPlugin;
use Spryker\Yves\Nopayment\Plugin\NopaymentHandlerPlugin;
use Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface;
use Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginCollection;
use Spryker\Yves\StepEngine\Dependency\Plugin\Handler\StepHandlerPluginCollection;
use Spryker\Yves\StepEngine\Dependency\Plugin\Handler\StepHandlerPluginInterface;
use SprykerEco\Yves\Payone\Plugin\PayoneCreditCardSubFormPlugin;
use SprykerEco\Yves\Payone\Plugin\PayoneHandlerPlugin;
use SprykerEco\Yves\Payone\Plugin\PayoneInstantOnlineTransferSubFormPlugin;
use SprykerShop\Yves\CheckoutPage\CheckoutPageDependencyProvider as SprykerShopCheckoutPageDependencyProvider;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToProductBundleClientInterface;
use SprykerShop\Yves\CheckoutPage\Plugin\StepEngine\PaymentForeignHandlerPlugin;
use SprykerShop\Yves\CompanyPage\Plugin\CheckoutPage\CompanyUnitAddressExpanderPlugin;
use SprykerShop\Yves\CustomerPage\Form\CheckoutAddressCollectionForm;
use SprykerShop\Yves\CustomerPage\Form\CustomerCheckoutForm;
use SprykerShop\Yves\CustomerPage\Form\GuestForm;
use SprykerShop\Yves\CustomerPage\Form\LoginForm;
use SprykerShop\Yves\CustomerPage\Form\RegisterForm;
use SprykerShop\Yves\CustomerPage\Plugin\CheckoutPage\CheckoutAddressFormDataProviderPlugin;
use SprykerShop\Yves\CustomerPage\Plugin\CheckoutPage\CustomerAddressExpanderPlugin;
use SprykerShop\Yves\CustomerPage\Plugin\CustomerStepHandler;
use SprykerShop\Yves\PaymentPage\Plugin\PaymentPage\PaymentForeignPaymentCollectionExtenderPlugin;
use SprykerShop\Yves\QuoteApprovalWidget\Plugin\CheckoutPage\QuoteApprovalCheckerCheckoutAddressStepEnterPreCheckPlugin;
use SprykerShop\Yves\QuoteApprovalWidget\Plugin\CheckoutPage\QuoteApprovalCheckerCheckoutPaymentStepEnterPreCheckPlugin;
use SprykerShop\Yves\QuoteApprovalWidget\Plugin\CheckoutPage\QuoteApprovalCheckerCheckoutShipmentStepEnterPreCheckPlugin;
use SprykerShop\Yves\QuoteRequestAgentPage\Plugin\CheckoutPage\QuoteRequestAgentCheckoutWorkflowStepResolverStrategyPlugin;
use SprykerShop\Yves\QuoteRequestPage\Plugin\CheckoutPage\QuoteRequestCheckoutWorkflowStepResolverStrategyPlugin;
use SprykerShop\Yves\QuoteRequestPage\Plugin\CheckoutPage\QuoteWithCustomShipmentPriceCheckoutWorkflowStepResolverStrategyPlugin;
use SprykerShop\Yves\SalesOrderThresholdWidget\Plugin\CheckoutPage\SalesOrderThresholdWidgetPlugin;
use SprykerShop\Yves\ServicePointCartPage\Plugin\CartPage\ServicePointCheckoutAddressStepPostExecutePlugin;
use SprykerShop\Yves\ShipmentTypeWidget\Plugin\CheckoutPage\ShipmentTypeCheckoutPageStepEnginePreRenderPlugin;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;

class CheckoutPageDependencyProvider extends SprykerShopCheckoutPageDependencyProvider
{
    /**
     * @uses \Spryker\Yves\Form\Plugin\Application\FormApplicationPlugin::SERVICE_FORM_FACTORY
     *
     * @var string
     */
    protected const SERVICE_FORM_FACTORY = 'form.factory';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->extendPaymentMethodHandler($container);
        $container = $this->extendSubFormPluginCollection($container);

        return $container;
    }

    /**
     * @return array<string>
     */
    protected function getSummaryPageWidgetPlugins(): array
    {
        return [
            SalesOrderThresholdWidgetPlugin::class, #SalesOrderThresholdFeature
        ];
    }

    /**
     * @phpstan-return array<class-string<\Symfony\Component\Form\FormTypeInterface>|\Symfony\Component\Form\FormInterface>
     *
     * @return array<\Symfony\Component\Form\FormTypeInterface|string>
     */
    protected function getCustomerStepSubForms(): array
    {
        return [
            LoginForm::class,
            $this->getCustomerCheckoutForm(RegisterForm::class, RegisterForm::BLOCK_PREFIX),
            $this->getCustomerCheckoutForm(GuestForm::class, GuestForm::BLOCK_PREFIX),
        ];
    }

    /**
     * @param string $subForm
     * @param string $blockPrefix
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    protected function getCustomerCheckoutForm($subForm, $blockPrefix): FormInterface
    {
        return $this->getFormFactory()->createNamed(
            $blockPrefix,
            CustomerCheckoutForm::class,
            null,
            [CustomerCheckoutForm::SUB_FORM_CUSTOMER => $subForm],
        );
    }

    /**
     * @return \Symfony\Component\Form\FormFactory
     */
    protected function getFormFactory(): FormFactory
    {
        return (new GlobalContainer())->get(static::SERVICE_FORM_FACTORY);
    }

    /**
     * @return array<string>
     */
    protected function getAddressStepSubForms(): array
    {
        return [
            CheckoutAddressCollectionForm::class,
        ];
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToProductBundleClientInterface
     */
    public function getProductBundleClient(Container $container): CheckoutPageToProductBundleClientInterface
    {
        return $container->get(static::CLIENT_PRODUCT_BUNDLE);
    }

    /**
     * @return array<\SprykerShop\Yves\CheckoutPageExtension\Dependency\Plugin\CheckoutAddressStepEnterPreCheckPluginInterface>
     */
    protected function getCheckoutAddressStepEnterPreCheckPlugins(): array
    {
        return [
            new QuoteApprovalCheckerCheckoutAddressStepEnterPreCheckPlugin(),
        ];
    }

    /**
     * @return array<\SprykerShop\Yves\CheckoutPageExtension\Dependency\Plugin\CheckoutShipmentStepEnterPreCheckPluginInterface>
     */
    protected function getCheckoutShipmentStepEnterPreCheckPlugins(): array
    {
        return [
            new QuoteApprovalCheckerCheckoutShipmentStepEnterPreCheckPlugin(),
        ];
    }

    /**
     * @return array<\SprykerShop\Yves\CheckoutPageExtension\Dependency\Plugin\CheckoutPaymentStepEnterPreCheckPluginInterface>
     */
    protected function getCheckoutPaymentStepEnterPreCheckPlugins(): array
    {
        return [
            new QuoteApprovalCheckerCheckoutPaymentStepEnterPreCheckPlugin(),
        ];
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function extendPaymentMethodHandler(Container $container): Container
    {
        $container->extend(static::PAYMENT_METHOD_HANDLER, function (StepHandlerPluginCollection $paymentMethodHandler) {
            $paymentMethodHandler->add(new NopaymentHandlerPlugin(), NopaymentConfig::PAYMENT_PROVIDER_NAME);
            $paymentMethodHandler->add(
                new DummyMarketplacePaymentHandlerPlugin(),
                DummyMarketplacePaymentConfig::PAYMENT_METHOD_DUMMY_MARKETPLACE_PAYMENT_INVOICE,
            );

            // --- Payone
            $paymentMethodHandler->add(new PayoneHandlerPlugin(), PaymentTransfer::PAYONE_CREDIT_CARD);
            $paymentMethodHandler->add(new PayoneHandlerPlugin(), PaymentTransfer::PAYONE_INSTANT_ONLINE_TRANSFER);

            $paymentMethodHandler->add(new PaymentForeignHandlerPlugin(), PaymentTransfer::FOREIGN_PAYMENTS);

            return $paymentMethodHandler;
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function extendSubFormPluginCollection(Container $container): Container
    {
        $container->extend(static::PAYMENT_SUB_FORMS, function (SubFormPluginCollection $paymentSubFormPluginCollection) {
            $paymentSubFormPluginCollection->add(new DummyMarketplacePaymentInvoiceSubFormPlugin());

            // --- Payone
            $paymentSubFormPluginCollection->add(new PayoneCreditCardSubFormPlugin());
            $paymentSubFormPluginCollection->add(new PayoneInstantOnlineTransferSubFormPlugin());

            return $paymentSubFormPluginCollection;
        });

        return $container;
    }

    /**
     * @return array<\SprykerShop\Yves\CheckoutPageExtension\Dependency\Plugin\AddressTransferExpanderPluginInterface>
     */
    protected function getAddressStepExecutorAddressExpanderPlugins(): array
    {
        return [
            new CustomerAddressExpanderPlugin(),
            new CompanyUnitAddressExpanderPlugin(),
        ];
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface
     */
    protected function getCheckoutAddressFormDataProviderPlugin(): StepEngineFormDataProviderInterface
    {
        return new CheckoutAddressFormDataProviderPlugin();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Plugin\Handler\StepHandlerPluginInterface
     */
    protected function getCustomerStepHandler(): StepHandlerPluginInterface
    {
        return new CustomerStepHandler();
    }

    /**
     * @return array<\SprykerShop\Yves\CheckoutPageExtension\Dependency\Plugin\CheckoutStepResolverStrategyPluginInterface>
     */
    protected function getCheckoutStepResolverStrategyPlugins(): array
    {
        return [
            new QuoteRequestCheckoutWorkflowStepResolverStrategyPlugin(),
            new QuoteWithCustomShipmentPriceCheckoutWorkflowStepResolverStrategyPlugin(),
            new QuoteRequestAgentCheckoutWorkflowStepResolverStrategyPlugin(),
        ];
    }

    /**
     * @return array<\SprykerShop\Yves\CheckoutPageExtension\Dependency\Plugin\StepEngine\CheckoutPageStepEnginePreRenderPluginInterface>
     */
    protected function getCheckoutPageStepEnginePreRenderPlugins(): array
    {
        return [
            new MerchantShipmentCheckoutPageStepEnginePreRenderPlugin(),
            new ShipmentTypeCheckoutPageStepEnginePreRenderPlugin(),
        ];
    }

    /**
     * @return array<\SprykerShop\Yves\CheckoutPageExtension\Dependency\Plugin\PaymentCollectionExtenderPluginInterface>
     */
    protected function getPaymentCollectionExtenderPlugins(): array
    {
        return [
            new PaymentForeignPaymentCollectionExtenderPlugin(),
        ];
    }

    /**
     * @return list<\SprykerShop\Yves\CheckoutPageExtension\Dependency\Plugin\CheckoutAddressStepPostExecutePluginInterface>
     */
    protected function getCheckoutAddressStepPostExecutePlugins(): array
    {
        return [
            new ServicePointCheckoutAddressStepPostExecutePlugin(),
        ];
    }
}
