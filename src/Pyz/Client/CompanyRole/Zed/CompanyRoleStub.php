<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Pyz\Client\CompanyRole\Zed;

use Generated\Shared\Transfer\CompanyRoleTransfer;
use Generated\Shared\Transfer\CustomerCollectionTransfer;
use Spryker\Client\CompanyRole\Dependency\Client\CompanyRoleToZedRequestClientInterface;
use Spryker\Client\CompanyRole\Zed\CompanyRoleStub as SprykerCompanyRoleStub;
use Spryker\Client\CompanyRole\Zed\CompanyRoleStubInterface;

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
            null,
            null
        );

        return $customerCollectionTransfer;
    }
}
