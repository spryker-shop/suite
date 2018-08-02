<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CompanyRole;

use Spryker\Client\CompanyUser\Plugin\AddCompanyUserPermissionPlugin;
use Spryker\Client\CompanyUserInvitation\Plugin\ManageCompanyUserInvitationPermissionPlugin;
use Spryker\Zed\CompanyRole\CompanyRoleConfig as SprykerCompanyRoleConfig;
use SprykerShop\Client\CheckoutPage\Plugin\PlaceOrderWithAmountUpToPermissionPlugin;
use SprykerShop\Shared\CartPage\Plugin\AddCartItemPermissionPlugin;
use SprykerShop\Shared\CartPage\Plugin\ChangeCartItemPermissionPlugin;
use SprykerShop\Shared\CartPage\Plugin\RemoveCartItemPermissionPlugin;

class CompanyRoleConfig extends SprykerCompanyRoleConfig
{
    protected const BUYER_ROLE_NAME = 'Buyer';

    /**
     * @return string[]
     */
    public function getAdminRolePermissions(): array
    {
        return [
            AddCompanyUserPermissionPlugin::KEY,
            ManageCompanyUserInvitationPermissionPlugin::KEY,
        ];
    }

    /**
     * @return array
     */
    public function getCompanyRoles(): array
    {
        return [static::BUYER_ROLE_NAME => $this->getPermissionsForBuyerRole()];
    }

    /**
     * @return string[]
     */
    protected function getPermissionsForBuyerRole(): array
    {
        return [
            AddCartItemPermissionPlugin::KEY,
            ChangeCartItemPermissionPlugin::KEY,
            RemoveCartItemPermissionPlugin::KEY,
            PlaceOrderWithAmountUpToPermissionPlugin::KEY,
        ];
    }
}
