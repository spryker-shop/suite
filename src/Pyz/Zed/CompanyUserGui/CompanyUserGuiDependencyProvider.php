<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CompanyUserGui;

use Spryker\Zed\CompanyBusinessUnitGui\Communication\Plugin\CompanyBusinessUnitCompanyUserTableConfigExpanderPlugin;
use Spryker\Zed\CompanyBusinessUnitGui\Communication\Plugin\CompanyBusinessUnitCompanyUserTablePrepareDataExpanderPlugin;
use Spryker\Zed\CompanyBusinessUnitGui\Communication\Plugin\CompanyUserBusinessUnitFieldPlugin;
use Spryker\Zed\CompanyRoleGui\Communication\Plugin\CompanyRoleCompanyUserTableConfigExpanderPlugin;
use Spryker\Zed\CompanyRoleGui\Communication\Plugin\CompanyRoleCompanyUserTablePrepareDataExpanderPlugin;
use Spryker\Zed\CompanyRoleGui\Communication\Plugin\CompanyUserRoleFieldPlugin;
use Spryker\Zed\CompanyUserGui\CompanyUserGuiDependencyProvider as SprykerCompanyUserGuiDependencyProvider;

class CompanyUserGuiDependencyProvider extends SprykerCompanyUserGuiDependencyProvider
{
    /**
     * @return \Spryker\Zed\CompanyUserGuiExtension\Dependency\Plugin\CompanyUserGui\CompanyUserTableConfigExpanderPluginInterface[]
     */
    protected function getCompanyUserTableConfigExpanderPlugins(): array
    {
        return [
            new CompanyRoleCompanyUserTableConfigExpanderPlugin(),
            new CompanyBusinessUnitCompanyUserTableConfigExpanderPlugin(),
        ];
    }

    /**
     * @return \Spryker\Zed\CompanyUserGuiExtension\Dependency\Plugin\CompanyUserGui\CompanyUserTablePrepareDataExpanderPluginInterface[]
     */
    protected function getCompanyUserTablePrepareDataExpanderPlugins(): array
    {
        return [
            new CompanyRoleCompanyUserTablePrepareDataExpanderPlugin(),
            new CompanyBusinessUnitCompanyUserTablePrepareDataExpanderPlugin(),
        ];
    }

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
