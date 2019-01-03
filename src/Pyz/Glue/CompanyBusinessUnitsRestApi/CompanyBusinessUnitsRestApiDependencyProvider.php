<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\CompanyBusinessUnitsRestApi;

use Spryker\Glue\CompanyBusinessUnitsRestApi\CompanyBusinessUnitsRestApiDependencyProvider as SprykerCompanyBusinessUnitsRestApiDependencyProvider;
use Spryker\Glue\CompanyUnitAddressesRestApi\Plugin\CompanyBusinessUnitsRestApi\CompanyBusinessUnitAddressAttributesMapperPlugin;

class CompanyBusinessUnitsRestApiDependencyProvider extends SprykerCompanyBusinessUnitsRestApiDependencyProvider
{
    /**
     * @return \Spryker\Glue\CompanyBusinessUnitsRestApiExtension\Dependency\Plugin\CompanyBusinessUnitAttributesMapperPluginInterface[]
     */
    protected function getCompanyBusinessUnitAttributesMapperPlugins(): array
    {
        return [
            new CompanyBusinessUnitAddressAttributesMapperPlugin(),
        ];
    }
}
