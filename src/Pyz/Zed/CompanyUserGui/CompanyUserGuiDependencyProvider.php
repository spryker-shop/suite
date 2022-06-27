<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CompanyUserGui;

use Spryker\Zed\BusinessOnBehalfGui\Communication\Plugin\CompanyUserGui\BusinessOnBehalfCompanyUserTableDeleteActionPlugin;
use Spryker\Zed\BusinessOnBehalfGui\Communication\Plugin\CompanyUserGui\CompanyUserTableAttachToBusinessUnitActionLinksExpanderPlugin;
use Spryker\Zed\CompanyBusinessUnitGui\Communication\Plugin\CompanyUserGui\CompanyBusinessUnitCompanyUserTableConfigExpanderPlugin;
use Spryker\Zed\CompanyBusinessUnitGui\Communication\Plugin\CompanyUserGui\CompanyBusinessUnitCompanyUserTablePrepareDataExpanderPlugin;
use Spryker\Zed\CompanyBusinessUnitGui\Communication\Plugin\CompanyUserGui\CompanyBusinessUnitToCompanyUserAttachCustomerFormExpanderPlugin;
use Spryker\Zed\CompanyBusinessUnitGui\Communication\Plugin\CompanyUserGui\CompanyBusinessUnitToCompanyUserFormExpanderPlugin;
use Spryker\Zed\CompanyGui\Communication\Plugin\CompanyUserGui\CompanyFieldToCompanyUserFormExpanderPlugin;
use Spryker\Zed\CompanyGui\Communication\Plugin\CompanyUserGui\CompanyToCompanyUserAttachCustomerFormExpanderPlugin;
use Spryker\Zed\CompanyRoleGui\Communication\Plugin\CompanyUserGui\CompanyRoleAttachCustomerFormExpanderPlugin;
use Spryker\Zed\CompanyRoleGui\Communication\Plugin\CompanyUserGui\CompanyRoleCompanyUserTableConfigExpanderPlugin;
use Spryker\Zed\CompanyRoleGui\Communication\Plugin\CompanyUserGui\CompanyRoleCompanyUserTablePrepareDataExpanderPlugin;
use Spryker\Zed\CompanyRoleGui\Communication\Plugin\CompanyUserGui\CompanyRoleFormExpanderPlugin;
use Spryker\Zed\CompanyUserGui\CompanyUserGuiDependencyProvider as SprykerCompanyUserGuiDependencyProvider;
use Spryker\Zed\CompanyUserGuiExtension\Dependency\Plugin\CompanyUserTableDeleteActionPluginInterface;

class CompanyUserGuiDependencyProvider extends SprykerCompanyUserGuiDependencyProvider
{
    /**
     * @return array<\Spryker\Zed\CompanyUserGuiExtension\Dependency\Plugin\CompanyUserTableConfigExpanderPluginInterface>
     */
    protected function getCompanyUserTableConfigExpanderPlugins(): array
    {
        return [
            new CompanyRoleCompanyUserTableConfigExpanderPlugin(),
            new CompanyBusinessUnitCompanyUserTableConfigExpanderPlugin(),
        ];
    }

    /**
     * @return array<\Spryker\Zed\CompanyUserGuiExtension\Dependency\Plugin\CompanyUserTablePrepareDataExpanderPluginInterface>
     */
    protected function getCompanyUserTablePrepareDataExpanderPlugins(): array
    {
        return [
            new CompanyRoleCompanyUserTablePrepareDataExpanderPlugin(),
            new CompanyBusinessUnitCompanyUserTablePrepareDataExpanderPlugin(),
        ];
    }

    /**
     * @return array<\Spryker\Zed\CompanyUserGuiExtension\Dependency\Plugin\CompanyUserFormExpanderPluginInterface>
     */
    protected function getCompanyUserFormExpanderPlugins(): array
    {
        return [
            new CompanyFieldToCompanyUserFormExpanderPlugin(),
            new CompanyBusinessUnitToCompanyUserFormExpanderPlugin(),
            new CompanyRoleFormExpanderPlugin(),
        ];
    }

    /**
     * @return array<\Spryker\Zed\CompanyUserGuiExtension\Dependency\Plugin\CompanyUserAttachCustomerFormExpanderPluginInterface>
     */
    protected function getCompanyUserAttachCustomerFormExpanderPlugins(): array
    {
        return [
            new CompanyBusinessUnitToCompanyUserAttachCustomerFormExpanderPlugin(),
            new CompanyRoleAttachCustomerFormExpanderPlugin(),
            new CompanyToCompanyUserAttachCustomerFormExpanderPlugin(),
        ];
    }

    /**
     * @return array<\Spryker\Zed\CompanyUserGuiExtension\Dependency\Plugin\CompanyUserTableActionExpanderPluginInterface>
     */
    protected function getCompanyUserTableActionExpanderPlugins(): array
    {
        return [
            new CompanyUserTableAttachToBusinessUnitActionLinksExpanderPlugin(),
        ];
    }

    /**
     * @return \Spryker\Zed\CompanyUserGuiExtension\Dependency\Plugin\CompanyUserTableDeleteActionPluginInterface|null
     */
    protected function getCompanyUserTableDeleteActionPlugin(): ?CompanyUserTableDeleteActionPluginInterface
    {
        return new BusinessOnBehalfCompanyUserTableDeleteActionPlugin();
    }
}
