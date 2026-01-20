<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Client\CompanyRole\Zed;

use Generated\Shared\Transfer\CompanyRoleTransfer;
use Generated\Shared\Transfer\CustomerCollectionTransfer;
use Spryker\Client\CompanyRole\Zed\CompanyRoleStub as SprykerCompanyRoleStub;

class CompanyRoleStub extends SprykerCompanyRoleStub implements CompanyRoleStubInterface
{
    /**
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerCollectionTransfer
     */
    public function getCustomerIdsByIdCompanyRole(CompanyRoleTransfer $companyRoleTransfer): CustomerCollectionTransfer
    {
        /** @var \Generated\Shared\Transfer\CustomerCollectionTransfer $customerCollectionTransfer */
        $customerCollectionTransfer = $this->zedRequestClient->call(
            '/company-role/gateway/get-customer-ids-by-id-company-role',
            $companyRoleTransfer,
        );

        return $customerCollectionTransfer;
    }
}
