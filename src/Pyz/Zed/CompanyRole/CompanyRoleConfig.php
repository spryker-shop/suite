<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CompanyRole;

use Spryker\Zed\CompanyRole\CompanyRoleConfig as SprykerCompanyRoleConfig;
use SprykerShop\Shared\CompanyPage\Plugin\CompanyUserStatusChangePermissionPlugin;

class CompanyRoleConfig extends SprykerCompanyRoleConfig
{
    /**
     * @return \Spryker\Shared\PermissionExtension\Dependency\Plugin\PermissionPluginInterface[]
     */
    public function getAdminRolePermissions(): array
    {
        return [
            new CompanyUserStatusChangePermissionPlugin(),
        ];
    }
}
