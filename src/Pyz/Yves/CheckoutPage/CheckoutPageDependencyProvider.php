<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CheckoutPage;

use Spryker\Shared\DummyMarketplacePayment\DummyMarketplacePaymentConfig;
use Spryker\Shared\Nopayment\NopaymentConfig;
use Spryker\Yves\DummyMarketplacePayment\Plugin\StepEngine\DummyMarketplacePaymentHandlerPlugin;
use Spryker\Yves\DummyMarketplacePayment\Plugin\StepEngine\SubForm\DummyMarketplacePaymentInvoiceSubFormPlugin;
use Spryker\Yves\Kernel\Container;
use Spryker\Yves\Kernel\Plugin\Pimple;
use Spryker\Yves\MerchantShipment\Plugin\CheckoutPage\MerchantShipmentCheckoutPageStepEnginePreRenderPlugin;
use Spryker\Yves\Nopayment\Plugin\NopaymentHandlerPlugin;
use Spryker\Yves\Payment\Plugin\PaymentFormFilterPlugin;
use Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface;
use Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginCollection;
use Spryker\Yves\StepEngine\Dependency\Plugin\Handler\StepHandlerPluginCollection;
use Spryker\Yves\StepEngine\Dependency\Plugin\Handler\StepHandlerPluginInterface;
use SprykerShop\Yves\CheckoutPage\CheckoutPageDependencyProvider as SprykerShopCheckoutPageDependencyProvider;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToProductBundleClientInterface;
use SprykerShop\Yves\CompanyPage\Plugin\CheckoutPage\CompanyUnitAddressExpanderPlugin;
use SprykerShop\Yves\CustomerPage\Form\CheckoutAddressCollectionForm;
use SprykerShop\Yves\CustomerPage\Form\CustomerCheckoutForm;
use SprykerShop\Yves\CustomerPage\Form\GuestForm;
use SprykerShop\Yves\CustomerPage\Form\LoginForm;
use SprykerShop\Yves\CustomerPage\Form\RegisterForm;
use SprykerShop\Yves\CustomerPage\Plugin\CheckoutPage\CheckoutAddressFormDataProviderPlugin;
use SprykerShop\Yves\CustomerPage\Plugin\CheckoutPage\CustomerAddressExpanderPlugin;
use SprykerShop\Yves\CustomerPage\Plugin\CustomerStepHandler;
use SprykerShop\Yves\QuoteApprovalWidget\Plugin\CheckoutPage\QuoteApprovalCheckerCheckoutAddressStepEnterPreCheckPlugin;
use SprykerShop\Yves\QuoteApprovalWidget\Plugin\CheckoutPage\QuoteApprovalCheckerCheckoutPaymentStepEnterPreCheckPlugin;
use SprykerShop\Yves\QuoteApprovalWidget\Plugin\CheckoutPage\QuoteApprovalCheckerCheckoutShipmentStepEnterPreCheckPlugin;
use SprykerShop\Yves\QuoteRequestAgentPage\Plugin\CheckoutPage\QuoteRequestAgentCheckoutWorkflowStepResolverStrategyPlugin;
use SprykerShop\Yves\QuoteRequestPage\Plugin\CheckoutPage\QuoteRequestCheckoutWorkflowStepResolverStrategyPlugin;
use SprykerShop\Yves\QuoteRequestPage\Plugin\CheckoutPage\QuoteWithCustomShipmentPriceCheckoutWorkflowStepResolverStrategyPlugin;
use SprykerShop\Yves\SalesOrderThresholdWidget\Plugin\CheckoutPage\SalesOrderThresholdWidgetPlugin;

class CheckoutPageDependencyProvider extends SprykerShopCheckoutPageDependencyProvider
{
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
     * @return string[]
     */
    protected function getSummaryPageWidgetPlugins(): array
    {
        return [
            SalesOrderThresholdWidgetPlugin::class, #SalesOrderThresholdFeature
        ];
    }

    /**
     * @phpstan-return array<int, class-string<\Symfony\Component\Form\FormTypeInterface>|\Symfony\Component\Form\FormInterface>
     *
     * @return \Symfony\Component\Form\FormTypeInterface[]|string[]
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
    protected function getCustomerCheckoutForm($subForm, $blockPrefix)
    {
        return $this->getFormFactory()->createNamed(
            $blockPrefix,
            CustomerCheckoutForm::class,
            null,
            [CustomerCheckoutForm::SUB_FORM_CUSTOMER => $subForm]
        );
    }

    /**
     * @return \Symfony\Component\Form\FormFactory
     */
    protected function getFormFactory()
    {
        return (new Pimple())->getApplication()['form.factory'];
    }

    /**
     * @return string[]
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
     * @return \Spryker\Yves\Checkout\Dependency\Plugin\Form\SubFormFilterPluginInterface[]
     */
    protected function getSubFormFilterPlugins(): array
    {
        return [
            new PaymentFormFilterPlugin(),
        ];
    }

    /**
     * @return \SprykerShop\Yves\CheckoutPageExtension\Dependency\Plugin\CheckoutAddressStepEnterPreCheckPluginInterface[]
     */
    protected function getCheckoutAddressStepEnterPreCheckPlugins(): array
    {
        return [
            new QuoteApprovalCheckerCheckoutAddressStepEnterPreCheckPlugin(),
        ];
    }

    /**
     * @return \SprykerShop\Yves\CheckoutPageExtension\Dependency\Plugin\CheckoutShipmentStepEnterPreCheckPluginInterface[]
     */
    protected function getCheckoutShipmentStepEnterPreCheckPlugins(): array
    {
        return [
            new QuoteApprovalCheckerCheckoutShipmentStepEnterPreCheckPlugin(),
        ];
    }

    /**
     * @return \SprykerShop\Yves\CheckoutPageExtension\Dependency\Plugin\CheckoutPaymentStepEnterPreCheckPluginInterface[]
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
                DummyMarketplacePaymentConfig::PAYMENT_METHOD_DUMMY_MARKETPLACE_PAYMENT_INVOICE
            );

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

            return $paymentSubFormPluginCollection;
        });

        return $container;
    }

    /**
     * @return \SprykerShop\Yves\CheckoutPageExtension\Dependency\Plugin\AddressTransferExpanderPluginInterface[]
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
     * @return \SprykerShop\Yves\CheckoutPageExtension\Dependency\Plugin\CheckoutStepResolverStrategyPluginInterface[]
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
     * @return \SprykerShop\Yves\CheckoutPageExtension\Dependency\Plugin\StepEngine\CheckoutPageStepEnginePreRenderPluginInterface[]
     */
    protected function getCheckoutPageStepEnginePreRenderPlugins(): array
    {
        return [
            new MerchantShipmentCheckoutPageStepEnginePreRenderPlugin(),
        ];
    }
}
