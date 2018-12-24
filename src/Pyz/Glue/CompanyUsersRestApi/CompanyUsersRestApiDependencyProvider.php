<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\CompanyUsersRestApi;

use Spryker\Glue\CompanyRolesRestApi\Plugin\CompanyUsersRestApi\CompanyRoleExpanderPlugin;
use Spryker\Glue\CompanyUnitAddressesRestApi\Plugin\CompanyUsersRestApi\CompanyUnitAddressExpanderPlugin;
use Spryker\Glue\CompanyUsersRestApi\CompanyUsersRestApiDependencyProvider as SprykerCompanyUsersRestApiDependencyProvider;

class CompanyUsersRestApiDependencyProvider extends SprykerCompanyUsersRestApiDependencyProvider
{
    /**
     * @return \Spryker\Glue\CompanyUsersRestApiExtension\Dependency\Plugin\CompanyUsersResourceExpanderPluginInterface[]
     */
    protected function getCompanyUsersResourceExpanderPlugins(): array
    {
        return [
            new CompanyRoleExpanderPlugin(),
            new CompanyUnitAddressExpanderPlugin(),
        ];
    }
}
