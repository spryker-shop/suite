<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\CompanyUsersRestApi;

use Spryker\Glue\CompanyBusinessUnitsRestApi\Plugin\CompanyUsersRestApi\CompanyBusinessUnitMapperPlugin;
use Spryker\Glue\CompanyRolesRestApi\Plugin\CompanyUsersRestApi\CompanyRoleMapperPlugin;
use Spryker\Glue\CompanyUsersRestApi\CompanyUsersRestApiDependencyProvider as SprykerCompanyUsersRestApiDependencyProvider;

class CompanyUsersRestApiDependencyProvider extends SprykerCompanyUsersRestApiDependencyProvider
{
    /**
     * @return \Spryker\Glue\CompanyUsersRestApiExtension\Dependency\Plugin\CompanyUserAttributesMapperPluginInterface[]
     */
    protected function getCompanyUserAttributesMapperPlugins(): array
    {
        return [
            new CompanyRoleMapperPlugin(),
            new CompanyBusinessUnitMapperPlugin(),
        ];
    }
}
