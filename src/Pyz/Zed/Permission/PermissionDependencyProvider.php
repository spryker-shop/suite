<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Zed\Permission;

use Spryker\Zed\CompanyBusinessUnitSalesConnector\Communication\Plugin\Permission\SeeBusinessUnitOrdersPermissionPlugin;
use Spryker\Zed\CompanyRole\Communication\Plugin\PermissionStoragePlugin;
use Spryker\Zed\CompanySalesConnector\Communication\Plugin\Permission\SeeCompanyOrdersPermissionPlugin;
use Spryker\Zed\Permission\PermissionDependencyProvider as SprykerPermissionDependencyProvider;
use Spryker\Zed\SharedCart\Communication\Plugin\QuotePermissionStoragePlugin;
use Spryker\Zed\SharedCart\Communication\Plugin\ReadSharedCartPermissionPlugin;
use Spryker\Zed\SharedCart\Communication\Plugin\WriteSharedCartPermissionPlugin;
use Spryker\Zed\ShoppingList\Communication\Plugin\ReadShoppingListPermissionPlugin;
use Spryker\Zed\ShoppingList\Communication\Plugin\ShoppingListPermissionStoragePlugin;
use Spryker\Zed\ShoppingList\Communication\Plugin\WriteShoppingListPermissionPlugin;
use SprykerFeature\Shared\SspDashboardManagement\Plugin\Permission\ViewDashboardPermissionPlugin;
use SprykerFeature\Shared\SspFileManagement\Plugin\Permission\DownloadFilesPermissionPlugin;
use SprykerFeature\Shared\SspFileManagement\Plugin\Permission\ViewCompanyBusinessUnitFilesPermissionPlugin;
use SprykerFeature\Shared\SspFileManagement\Plugin\Permission\ViewCompanyFilesPermissionPlugin;
use SprykerFeature\Shared\SspFileManagement\Plugin\Permission\ViewCompanyUserFilesPermissionPlugin;
use SprykerFeature\Shared\SspFileManagement\Plugin\Permission\ViewFilesPermissionPlugin;
use SprykerFeature\Shared\SspInquiryManagement\Plugin\Permission\CreateSspInquiryPermissionPlugin;
use SprykerFeature\Shared\SspInquiryManagement\Plugin\Permission\ViewBusinessUnitSspInquiryPermissionPlugin;
use SprykerFeature\Shared\SspInquiryManagement\Plugin\Permission\ViewCompanySspInquiryPermissionPlugin;

class PermissionDependencyProvider extends SprykerPermissionDependencyProvider
{
    /**
     * @return array<\Spryker\Zed\PermissionExtension\Dependency\Plugin\PermissionStoragePluginInterface>
     */
    protected function getPermissionStoragePlugins(): array
    {
        return [
            new QuotePermissionStoragePlugin(), #SharedCartFeature
            new ShoppingListPermissionStoragePlugin(), #ShoppingListFeature
            new PermissionStoragePlugin(),
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
            new SeeBusinessUnitOrdersPermissionPlugin(),
            new SeeCompanyOrdersPermissionPlugin(),
            new ViewFilesPermissionPlugin(), #SspFileManagementFeature
            new DownloadFilesPermissionPlugin(), #SspFileManagementFeature
            new ViewCompanyUserFilesPermissionPlugin(), #SspFileManagementFeature
            new ViewCompanyBusinessUnitFilesPermissionPlugin(), #SspFileManagementFeature
            new ViewCompanyFilesPermissionPlugin(), #SspFileManagementFeature
            new CreateSspInquiryPermissionPlugin(), #SspInquiryManagementFeature
            new ViewCompanySspInquiryPermissionPlugin(), #SspInquiryManagementFeature
            new ViewBusinessUnitSspInquiryPermissionPlugin(), #SspInquiryManagementFeature
            new ViewDashboardPermissionPlugin(), #SspDashboardManagement Feature
        ];
    }
}
