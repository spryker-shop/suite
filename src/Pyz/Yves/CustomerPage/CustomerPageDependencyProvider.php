<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CustomerPage;

use Spryker\Yves\Kernel\Container;
use Spryker\Yves\MerchantShipment\Plugin\CustomerPage\MerchantShipmentCheckoutAddressStepPreGroupItemsByShipmentPlugin;
use SprykerShop\Yves\AgentPage\Plugin\FixAgentTokenAfterCustomerAuthenticationSuccessPlugin;
use SprykerShop\Yves\CompanyPage\Plugin\CustomerPage\BusinessOnBehalfCompanyUserRedirectAfterLoginStrategyPlugin;
use SprykerShop\Yves\CompanyPage\Plugin\CustomerPage\CompanyBusinessUnitOrderSearchFormExpanderPlugin;
use SprykerShop\Yves\CompanyPage\Plugin\CustomerPage\CompanyBusinessUnitOrderSearchFormHandlerPlugin;
use SprykerShop\Yves\CompanyUserInvitationPage\Plugin\CompanyUserInvitationPreRegistrationCustomerTransferExpanderPlugin;
use SprykerShop\Yves\CustomerPage\CustomerPageDependencyProvider as SprykerShopCustomerPageDependencyProvider;
use SprykerShop\Yves\CustomerPage\Plugin\CustomerPage\RedirectUriCustomerRedirectStrategyPlugin;
use SprykerShop\Yves\CustomerReorderWidget\Plugin\CustomerPage\CustomerReorderWidgetPlugin;
use SprykerShop\Yves\MerchantSwitcherWidget\Plugin\CustomerPage\MerchantSwitchCartAfterCustomerAuthenticationSuccessPlugin;

class CustomerPageDependencyProvider extends SprykerShopCustomerPageDependencyProvider
{
    public const CLIENT_PAYONE = 'CLIENT_PAYONE';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container[static::CLIENT_PAYONE] = function (Container $container) {
            return $container->getLocator()->payone()->client();
        };
        return parent::provideDependencies($container);
    }

    /**
     * @return string[]
     */
    protected function getCustomerOverviewWidgetPlugins(): array
    {
        return [
            CustomerReorderWidgetPlugin::class,
        ];
    }

    /**
     * @return string[]
     */
    protected function getCustomerOrderListWidgetPlugins(): array
    {
        return [
            CustomerReorderWidgetPlugin::class,
        ];
    }

    /**
     * @return string[]
     */
    protected function getCustomerOrderViewWidgetPlugins(): array
    {
        return [
            CustomerReorderWidgetPlugin::class,
        ];
    }

    /**
     * @return \SprykerShop\Yves\CustomerPageExtension\Dependency\Plugin\PreRegistrationCustomerTransferExpanderPluginInterface[]
     */
    protected function getPreRegistrationCustomerTransferExpanderPlugins(): array
    {
        return [
            new CompanyUserInvitationPreRegistrationCustomerTransferExpanderPlugin(), #BulkImportCompanyUserInvitationsFeature
        ];
    }

    /**
     * @return \SprykerShop\Yves\CustomerPageExtension\Dependency\Plugin\CustomerRedirectStrategyPluginInterface[]
     */
    protected function getAfterLoginCustomerRedirectPlugins(): array
    {
        return [
            new RedirectUriCustomerRedirectStrategyPlugin(),
            new BusinessOnBehalfCompanyUserRedirectAfterLoginStrategyPlugin(), #BusinessOnBehalfFeature
        ];
    }

    /**
     * @return \SprykerShop\Yves\CustomerPageExtension\Dependency\Plugin\AfterCustomerAuthenticationSuccessPluginInterface[]
     */
    protected function getAfterCustomerAuthenticationSuccessPlugins(): array
    {
        return [
            new FixAgentTokenAfterCustomerAuthenticationSuccessPlugin(),
            new MerchantSwitchCartAfterCustomerAuthenticationSuccessPlugin(),
        ];
    }

    /**
     * @return \SprykerShop\Yves\CustomerPageExtension\Dependency\Plugin\CheckoutAddressStepPreGroupItemsByShipmentPluginInterface[]
     */
    protected function getCheckoutAddressStepPreGroupItemsByShipmentPlugins(): array
    {
        return [
            new MerchantShipmentCheckoutAddressStepPreGroupItemsByShipmentPlugin(),
        ];
    }

    /**
     * @return \SprykerShop\Yves\CustomerPageExtension\Dependency\Plugin\OrderSearchFormExpanderPluginInterface[]
     */
    protected function getOrderSearchFormExpanderPlugins(): array
    {
        return [
            new CompanyBusinessUnitOrderSearchFormExpanderPlugin(),
        ];
    }

    /**
     * @return \SprykerShop\Yves\CustomerPageExtension\Dependency\Plugin\OrderSearchFormHandlerPluginInterface[]
     */
    protected function getOrderSearchFormHandlerPlugins(): array
    {
        return [
            new CompanyBusinessUnitOrderSearchFormHandlerPlugin(),
        ];
    }
}
