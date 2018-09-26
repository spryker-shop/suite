<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CompanyUserGui;

use Spryker\Zed\CompanyBusinessUnitGui\Communication\Plugin\CompanyUserBusinessUnitFieldPlugin;
use Spryker\Zed\CompanyRoleGui\Communication\Plugin\CompanyUserRoleFieldPlugin;
use Spryker\Zed\CompanyUserGui\CompanyUserGuiDependencyProvider as SprykerCompanyUserGuiDependencyProvider;

class CompanyUserGuiDependencyProvider extends SprykerCompanyUserGuiDependencyProvider
{
    /**
     * @return \Spryker\Zed\CompanyUserGuiExtension\Communication\Plugin\CompanyUserFormExpanderPluginInterface[]
     */
    protected function getCompanyUserFormExpanderPlugins(): array
    {
        return [
            new CompanyUserBusinessUnitFieldPlugin(),
            new CompanyUserRoleFieldPlugin(),
        ];
    }

    /**
     * @return \Spryker\Zed\CompanyUserGuiExtension\Communication\Plugin\CompanyUserFormExpanderPluginInterface[]
     */
    protected function getCompanyUserEditFormExpanderPlugins(): array
    {
        return [
            new CompanyUserBusinessUnitFieldPlugin(),
            new CompanyUserRoleFieldPlugin(),
        ];
    }
}
