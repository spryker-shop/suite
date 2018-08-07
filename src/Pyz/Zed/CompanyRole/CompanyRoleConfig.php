<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CompanyRole;

use Generated\Shared\Transfer\CompanyRoleTransfer;
use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Generated\Shared\Transfer\PermissionTransfer;
use Spryker\Client\CompanyUser\Plugin\AddCompanyUserPermissionPlugin;
use Spryker\Client\CompanyUserInvitation\Plugin\ManageCompanyUserInvitationPermissionPlugin;
use Spryker\Zed\CompanyRole\CompanyRoleConfig as SprykerCompanyRoleConfig;
use SprykerShop\Client\CheckoutPage\Plugin\PlaceOrderWithAmountUpToPermissionPlugin;
use SprykerShop\Shared\CartPage\Plugin\AddCartItemPermissionPlugin;
use SprykerShop\Shared\CartPage\Plugin\ChangeCartItemPermissionPlugin;
use SprykerShop\Shared\CartPage\Plugin\RemoveCartItemPermissionPlugin;
use SprykerShop\Shared\CompanyPage\Plugin\CompanyUserStatusChangePermissionPlugin;

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
            CompanyUserStatusChangePermissionPlugin::KEY,
        ];
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

    /**
     * @return \Generated\Shared\Transfer\CompanyRoleTransfer[]
     */
    public function getCompanyRoles(): array
    {
        $buyerRoleTransfer = (new CompanyRoleTransfer())
            ->setName(static::BUYER_ROLE_NAME)
            ->setPermissionCollection($this->createPermissionCollectionFromPermissionKeys(
                $this->getPermissionsForBuyerRole()
            ));

        $administratorRoleTransfer = (new CompanyRoleTransfer())
            ->setName(static::DEFAULT_ADMIN_ROLE_NAME)
            ->setIsDefault(true)
            ->setPermissionCollection($this->createPermissionCollectionFromPermissionKeys(
                $this->getAdminRolePermissions()
            ));

        return [
            $buyerRoleTransfer,
            $administratorRoleTransfer,
        ];
    }

    /**
     * @param array $rolePermissionKeys
     *
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    protected function createPermissionCollectionFromPermissionKeys(array $rolePermissionKeys): PermissionCollectionTransfer
    {
        $permissions = new PermissionCollectionTransfer();

        foreach ($rolePermissionKeys as $permissionKey) {
            $permission = (new PermissionTransfer())
                ->setKey($permissionKey);

            $permissions->addPermission($permission);
        }

        return $permissions;
    }
}
