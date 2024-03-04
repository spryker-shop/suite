<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Permission;

use Spryker\Client\CompanyBusinessUnitSalesConnector\Plugin\Permission\SeeBusinessUnitOrdersPermissionPlugin;
use Spryker\Client\CompanyRole\Plugin\PermissionStoragePlugin;
use Spryker\Client\CompanySalesConnector\Plugin\Permission\SeeCompanyOrdersPermissionPlugin;
use Spryker\Client\CompanyUser\Plugin\Permission\SeeCompanyUsersPermissionPlugin;
use Spryker\Client\CustomerAccessPermission\Plugin\CustomerAccessPermissionStoragePlugin;
use Spryker\Client\CustomerAccessPermission\Plugin\SeeAddToCartPermissionPlugin;
use Spryker\Client\CustomerAccessPermission\Plugin\SeeOrderPlaceSubmitPermissionPlugin;
use Spryker\Client\CustomerAccessPermission\Plugin\SeePricePermissionPlugin;
use Spryker\Client\CustomerAccessPermission\Plugin\SeeShoppingListPermissionPlugin;
use Spryker\Client\CustomerAccessPermission\Plugin\SeeWishlistPermissionPlugin;
use Spryker\Client\OauthPermission\Plugin\Permission\OauthPermissionStoragePlugin;
use Spryker\Client\Permission\PermissionDependencyProvider as SprykerPermissionDependencyProvider;
use Spryker\Client\SharedCart\Plugin\ReadSharedCartPermissionPlugin;
use Spryker\Client\SharedCart\Plugin\WriteSharedCartPermissionPlugin;
use Spryker\Client\ShoppingList\Plugin\ReadShoppingListPermissionPlugin;
use Spryker\Client\ShoppingList\Plugin\WriteShoppingListPermissionPlugin;

class PermissionDependencyProvider extends SprykerPermissionDependencyProvider
{
    /**
     * @return array<\Spryker\Client\PermissionExtension\Dependency\Plugin\PermissionStoragePluginInterface>
     */
    protected function getPermissionStoragePlugins(): array
    {
        return [
            new PermissionStoragePlugin(), #SharedCartFeature #ShoppingListFeature
            new CustomerAccessPermissionStoragePlugin(), #CustomerAccessFeature
            new OauthPermissionStoragePlugin(),
        ];
    }

    /**
     * @return array<\Spryker\Shared\PermissionExtension\Dependency\Plugin\PermissionPluginInterface>
     */
    protected function getPermissionPlugins(): array
    {
        return [
            new ReadSharedCartPermissionPlugin(), #SharedCartFeature
            new WriteSharedCartPermissionPlugin(), #SharedCartFeature
            new ReadShoppingListPermissionPlugin(), #ShoppingListFeature
            new WriteShoppingListPermissionPlugin(), #ShoppingListFeature
            new SeePricePermissionPlugin(), #CustomerAccessFeature
            new SeeOrderPlaceSubmitPermissionPlugin(), #CustomerAccessFeature
            new SeeAddToCartPermissionPlugin(), #CustomerAccessFeature
            new SeeWishlistPermissionPlugin(), #CustomerAccessFeature
            new SeeShoppingListPermissionPlugin(), #CustomerAccessFeature
            new SeeCompanyOrdersPermissionPlugin(),
            new SeeBusinessUnitOrdersPermissionPlugin(),
            new SeeCompanyUsersPermissionPlugin(),
        ];
    }
}
