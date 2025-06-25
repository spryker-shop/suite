<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Yves\CustomerPage;

use Spryker\Yves\MerchantShipment\Plugin\CustomerPage\MerchantShipmentCheckoutAddressStepPreGroupItemsByShipmentPlugin;
use Spryker\Yves\MultiFactorAuth\Plugin\AuthenticationHandler\Customer\CustomerMultiFactorAuthenticationHandlerPlugin;
use SprykerShop\Yves\AgentPage\Plugin\Security\UpdateAgentTokenAfterCustomerAuthenticationSuccessPlugin;
use SprykerShop\Yves\ClickAndCollectPageExample\Plugin\CustomerPage\ClickAndCollectServiceTypeCheckoutAddressCollectionFormExpanderPlugin;
use SprykerShop\Yves\ClickAndCollectPageExample\Plugin\CustomerPage\ClickAndCollectServiceTypeCheckoutMultiShippingAddressesFormExpanderPlugin;
use SprykerShop\Yves\CompanyPage\Plugin\CustomerPage\BusinessOnBehalfCompanyUserRedirectAfterLoginStrategyPlugin;
use SprykerShop\Yves\CompanyPage\Plugin\CustomerPage\CompanyBusinessUnitOrderSearchFormExpanderPlugin;
use SprykerShop\Yves\CompanyPage\Plugin\CustomerPage\CompanyBusinessUnitOrderSearchFormHandlerPlugin;
use SprykerShop\Yves\CompanyUserInvitationPage\Plugin\CompanyUserInvitationPreRegistrationCustomerTransferExpanderPlugin;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\CustomerPage\CustomerPageDependencyProvider as SprykerShopCustomerPageDependencyProvider;
use SprykerShop\Yves\CustomerPage\Plugin\CustomerPage\RedirectUriCustomerRedirectStrategyPlugin;
use Pyz\Yves\CustomerPage\Plugin\Provider\CustomerAuthenticationSuccessHandler;
use SprykerShop\Yves\MerchantSwitcherWidget\Plugin\CustomerPage\MerchantSwitchCartAfterCustomerAuthenticationSuccessPlugin;
use SprykerShop\Yves\ServicePointWidget\Plugin\CustomerPage\ServicePointAddressCheckoutAddressCollectionFormExpanderPlugin;
use SprykerShop\Yves\ServicePointWidget\Plugin\CustomerPage\ServicePointCheckoutAddressCollectionFormExpanderPlugin;
use SprykerShop\Yves\ServicePointWidget\Plugin\CustomerPage\ServicePointCheckoutMultiShippingAddressesFormExpanderPlugin;
use SprykerShop\Yves\SessionAgentValidation\Plugin\CustomerPage\UpdateAgentSessionAfterCustomerAuthenticationSuccessPlugin;
use SprykerShop\Yves\ShipmentTypeWidget\Plugin\CustomerPage\ShipmentTypeCheckoutAddressCollectionFormExpanderPlugin;
use SprykerShop\Yves\ShipmentTypeWidget\Plugin\CustomerPage\ShipmentTypeCheckoutAddressStepPreGroupItemsByShipmentPlugin;
use SprykerShop\Yves\ShipmentTypeWidget\Plugin\CustomerPage\ShipmentTypeCheckoutMultiShippingAddressesFormExpanderPlugin;

class CustomerPageDependencyProvider extends SprykerShopCustomerPageDependencyProvider
{
    public const CLIENT_REDIS = 'CLIENT_REDIS';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addRedisClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addRedisClient(Container $container): Container
    {
        $container->set(static::CLIENT_REDIS, function (Container $container) {
            return $container->getLocator()->redis()->client();
        });

        return $container;
    }
    /**
     * @return array<\SprykerShop\Yves\CustomerPageExtension\Dependency\Plugin\PreRegistrationCustomerTransferExpanderPluginInterface>
     */
    protected function getPreRegistrationCustomerTransferExpanderPlugins(): array
    {
        return [
            new CompanyUserInvitationPreRegistrationCustomerTransferExpanderPlugin(), #BulkImportCompanyUserInvitationsFeature
        ];
    }

    /**
     * @return array<\SprykerShop\Yves\CustomerPageExtension\Dependency\Plugin\CustomerRedirectStrategyPluginInterface>
     */
    protected function getAfterLoginCustomerRedirectPlugins(): array
    {
        return [
            new RedirectUriCustomerRedirectStrategyPlugin(),
            new BusinessOnBehalfCompanyUserRedirectAfterLoginStrategyPlugin(), #BusinessOnBehalfFeature
        ];
    }

    /**
     * @return array<\SprykerShop\Yves\CustomerPageExtension\Dependency\Plugin\AfterCustomerAuthenticationSuccessPluginInterface>
     */
    protected function getAfterCustomerAuthenticationSuccessPlugins(): array
    {
        return [
            new UpdateAgentTokenAfterCustomerAuthenticationSuccessPlugin(),
            new MerchantSwitchCartAfterCustomerAuthenticationSuccessPlugin(),
            new UpdateAgentSessionAfterCustomerAuthenticationSuccessPlugin(),
        ];
    }

    /**
     * @return array<\SprykerShop\Yves\CustomerPageExtension\Dependency\Plugin\CheckoutAddressStepPreGroupItemsByShipmentPluginInterface>
     */
    protected function getCheckoutAddressStepPreGroupItemsByShipmentPlugins(): array
    {
        return [
            new MerchantShipmentCheckoutAddressStepPreGroupItemsByShipmentPlugin(),
            new ShipmentTypeCheckoutAddressStepPreGroupItemsByShipmentPlugin(),
        ];
    }

    /**
     * @return array<\SprykerShop\Yves\CustomerPageExtension\Dependency\Plugin\OrderSearchFormExpanderPluginInterface>
     */
    protected function getOrderSearchFormExpanderPlugins(): array
    {
        return [
            new CompanyBusinessUnitOrderSearchFormExpanderPlugin(),
        ];
    }

    /**
     * @return array<\SprykerShop\Yves\CustomerPageExtension\Dependency\Plugin\OrderSearchFormHandlerPluginInterface>
     */
    protected function getOrderSearchFormHandlerPlugins(): array
    {
        return [
            new CompanyBusinessUnitOrderSearchFormHandlerPlugin(),
        ];
    }

    /**
     * @return list<\SprykerShop\Yves\CustomerPageExtension\Dependency\Plugin\CheckoutAddressCollectionFormExpanderPluginInterface>
     */
    protected function getCheckoutAddressCollectionFormExpanderPlugins(): array
    {
        return [
            new ShipmentTypeCheckoutAddressCollectionFormExpanderPlugin(),
            new ServicePointCheckoutAddressCollectionFormExpanderPlugin(),
            new ServicePointAddressCheckoutAddressCollectionFormExpanderPlugin(),
            new ClickAndCollectServiceTypeCheckoutAddressCollectionFormExpanderPlugin(),
        ];
    }

    /**
     * @return list<\SprykerShop\Yves\CustomerPageExtension\Dependency\Plugin\CheckoutMultiShippingAddressesFormExpanderPluginInterface>
     */
    protected function getCheckoutMultiShippingAddressesFormExpanderPlugins(): array
    {
        return [
            new ShipmentTypeCheckoutMultiShippingAddressesFormExpanderPlugin(),
            new ServicePointCheckoutMultiShippingAddressesFormExpanderPlugin(),
            new ClickAndCollectServiceTypeCheckoutMultiShippingAddressesFormExpanderPlugin(),
        ];
    }

    /**
     * @return array<\SprykerShop\Yves\CustomerPageExtension\Dependency\Plugin\AuthenticationHandlerPluginInterface>
     */
    protected function getCustomerAuthenticationHandlerPlugins(): array
    {
        return [
            new CustomerMultiFactorAuthenticationHandlerPlugin(),
        ];
    }
}
