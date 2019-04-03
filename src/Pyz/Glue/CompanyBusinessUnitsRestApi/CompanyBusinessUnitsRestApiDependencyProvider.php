<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\CompanyBusinessUnitsRestApi;

use Spryker\Glue\CompanyBusinessUnitAddressesRestApi\Plugin\CompanyBusinessUnit\DefaultBillingAddressMapper;
use Spryker\Glue\CompanyBusinessUnitsRestApi\CompanyBusinessUnitsRestApiDependencyProvider as SprykerCompanyBusinessUnitsRestApiDependencyProvider;

class CompanyBusinessUnitsRestApiDependencyProvider extends SprykerCompanyBusinessUnitsRestApiDependencyProvider
{
    /**
     * @return \Spryker\Glue\CompanyBusinessUnitsRestApiExtension\Dependency\Plugin\CompanyBusinessUnitMapperInterface[]
     */
    protected function getCompanyBusinessUnitMapperPlugins(): array
    {
        return [
            new DefaultBillingAddressMapper(),
        ];
    }
}
