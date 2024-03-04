<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CompanyBusinessUnitGui;

use Spryker\Zed\CompanyBusinessUnitGui\CompanyBusinessUnitGuiDependencyProvider as SprykerCompanyBusinessUnitGuiDependencyProvider;
use Spryker\Zed\CompanyGui\Communication\Plugin\CompanyBusinessUnitGui\CompanyToCompanyBusinessUnitFormExpanderPlugin;
use Spryker\Zed\CompanyUnitAddressGui\Communication\Plugin\CompanyBusinessUnitGui\CompanyBusinessUnitAddressFieldPlugin;

class CompanyBusinessUnitGuiDependencyProvider extends SprykerCompanyBusinessUnitGuiDependencyProvider
{
    /**
     * @return array<\Spryker\Zed\CompanyBusinessUnitGuiExtension\Communication\Plugin\CompanyBusinessUnitFormExpanderPluginInterface>
     */
    protected function getCompanyBusinessUnitFormExpanderPlugins(): array
    {
        return [
            new CompanyToCompanyBusinessUnitFormExpanderPlugin(),
        ];
    }

    /**
     * @return array<\Spryker\Zed\CompanyBusinessUnitGuiExtension\Communication\Plugin\CompanyBusinessUnitFormExpanderPluginInterface>
     */
    protected function getCompanyBusinessUnitEditFormExpanderPlugins(): array
    {
        return [
            new CompanyBusinessUnitAddressFieldPlugin(),
        ];
    }
}
