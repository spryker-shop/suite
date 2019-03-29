<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\CompaniesRestApi;

use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyRoleTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Spryker\Glue\CompaniesRestApi\CompaniesRestApiConfig as SprykerCompaniesRestApiConfig;

class CompaniesRestApiConfig extends SprykerCompaniesRestApiConfig
{
    protected const EXTENDABLE_RESOURCE_TRANSFERS = [
        CompanyUserTransfer::class,
        CompanyRoleTransfer::class,
        CompanyBusinessUnitTransfer::class,
    ];
}
