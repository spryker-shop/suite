<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Client\Permission;

use Spryker\Client\CompanyBusinessUnitSalesConnector\Plugin\Permission\SeeBusinessUnitOrdersPermissionPlugin;
use Spryker\Client\CompanyRole\Plugin\Permission\CreateCompanyRolesPermissionPlugin;
use Spryker\Client\CompanyRole\Plugin\Permission\DeleteCompanyRolesPermissionPlugin;
use Spryker\Client\CompanyRole\Plugin\Permission\EditCompanyRolesPermissionPlugin;
use Spryker\Client\CompanyRole\Plugin\Permission\SeeCompanyRolesPermissionPlugin;
use Spryker\Client\CompanyRole\Plugin\PermissionStoragePlugin;
use Spryker\Client\CompanySalesConnector\Plugin\Permission\SeeCompanyOrdersPermissionPlugin;
use Spryker\Client\CompanyUser\Plugin\CompanyUserStatusChangePermissionPlugin;
use Spryker\Client\CompanyUser\Plugin\Permission\DeleteCompanyUsersPermissionPlugin;
use Spryker\Client\CompanyUser\Plugin\Permission\EditCompanyUsersPermissionPlugin;
use Spryker\Client\CompanyUser\Plugin\Permission\SeeCompanyUsersPermissionPlugin;
use Spryker\Client\CustomerAccessPermission\Plugin\CustomerAccessPermissionStoragePlugin;
use Spryker\Client\CustomerAccessPermission\Plugin\SeeAddToCartPermissionPlugin;
use Spryker\Client\CustomerAccessPermission\Plugin\SeeOrderPlaceSubmitPermissionPlugin;
use Spryker\Client\CustomerAccessPermission\Plugin\SeePricePermissionPlugin;
use Spryker\Client\CustomerAccessPermission\Plugin\SeeShoppingListPermissionPlugin;
use Spryker\Client\CustomerAccessPermission\Plugin\SeeWishlistPermissionPlugin;
use Spryker\Client\MerchantRelationRequest\Plugin\Permission\CreateMerchantRelationRequestPermissionPlugin;
use Spryker\Client\OauthPermission\Plugin\Permission\OauthPermissionStoragePlugin;
use Spryker\Client\Permission\PermissionDependencyProvider as SprykerPermissionDependencyProvider;
use Spryker\Client\SharedCart\Plugin\ReadSharedCartPermissionPlugin;
use Spryker\Client\SharedCart\Plugin\WriteSharedCartPermissionPlugin;
use Spryker\Client\ShoppingList\Plugin\ReadShoppingListPermissionPlugin;
use Spryker\Client\ShoppingList\Plugin\WriteShoppingListPermissionPlugin;
use Spryker\Shared\CompanyUser\Plugin\AddCompanyUserPermissionPlugin;
use Spryker\Shared\CompanyUserInvitation\Plugin\ManageCompanyUserInvitationPermissionPlugin;
use SprykerFeature\Shared\SspAssetManagement\Plugin\Permission\CreateSspAssetPermissionPlugin;
use SprykerFeature\Shared\SspAssetManagement\Plugin\Permission\UnassignSspAssetPermissionPlugin;
use SprykerFeature\Shared\SspAssetManagement\Plugin\Permission\UpdateSspAssetPermissionPlugin;
use SprykerFeature\Shared\SspAssetManagement\Plugin\Permission\ViewBusinessUnitSspAssetPermissionPlugin;
use SprykerFeature\Shared\SspAssetManagement\Plugin\Permission\ViewCompanySspAssetPermissionPlugin;
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
            new ManageCompanyUserInvitationPermissionPlugin(),
            new AddCompanyUserPermissionPlugin(),
            new CompanyUserStatusChangePermissionPlugin(),
            new SeePricePermissionPlugin(), #CustomerAccessFeature
            new SeeOrderPlaceSubmitPermissionPlugin(), #CustomerAccessFeature
            new SeeAddToCartPermissionPlugin(), #CustomerAccessFeature
            new SeeWishlistPermissionPlugin(), #CustomerAccessFeature
            new SeeShoppingListPermissionPlugin(), #CustomerAccessFeature
            new SeeCompanyOrdersPermissionPlugin(),
            new SeeBusinessUnitOrdersPermissionPlugin(),
            new SeeCompanyUsersPermissionPlugin(),
            new CreateMerchantRelationRequestPermissionPlugin(),
            new DeleteCompanyUsersPermissionPlugin(),
            new EditCompanyUsersPermissionPlugin(),
            new DeleteCompanyRolesPermissionPlugin(),
            new CreateCompanyRolesPermissionPlugin(),
            new EditCompanyRolesPermissionPlugin(),
            new SeeCompanyRolesPermissionPlugin(),
            new CreateSspInquiryPermissionPlugin(), #SspInquiryManagementFeature
            new ViewCompanySspInquiryPermissionPlugin(), #SspInquiryManagementFeature
            new ViewBusinessUnitSspInquiryPermissionPlugin(), #SspInquiryManagementFeature
            new ViewFilesPermissionPlugin(), #SspFileManagementFeature
            new DownloadFilesPermissionPlugin(), #SspFileManagementFeature
            new ViewCompanyUserFilesPermissionPlugin(), #SspFileManagementFeature
            new ViewCompanyBusinessUnitFilesPermissionPlugin(), #SspFileManagementFeature
            new ViewCompanyFilesPermissionPlugin(), #SspFileManagementFeature
            new ViewDashboardPermissionPlugin(), #SspDashboardManagement Feature
            new ViewCompanySspAssetPermissionPlugin(), #SspAssetFeature
            new ViewBusinessUnitSspAssetPermissionPlugin(), #SspAssetFeature
            new CreateSspAssetPermissionPlugin(), #SspAssetFeature
            new UpdateSspAssetPermissionPlugin(), #SspAssetFeature
            new UnassignSspAssetPermissionPlugin(), #SspAssetFeature
        ];
    }
}
