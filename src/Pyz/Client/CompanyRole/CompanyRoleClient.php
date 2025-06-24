<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Pyz\Client\CompanyRole;

use Generated\Shared\Transfer\CompanyRoleTransfer;
use Generated\Shared\Transfer\CustomerCollectionTransfer;
use Spryker\Client\CompanyRole\CompanyRoleClient as SprykerCompanyRoleClient;

/**
 * @method \Pyz\Client\CompanyRole\CompanyRoleFactory getFactory()
 */
class CompanyRoleClient extends SprykerCompanyRoleClient implements CompanyRoleClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerCollectionTransfer
     */
    public function getCustomerIdsByIdCompanyRole(CompanyRoleTransfer $companyRoleTransfer): CustomerCollectionTransfer
    {
        return $this->getFactory()
            ->createZedCompanyRoleStub()
            ->getCustomerIdsByIdCompanyRole($companyRoleTransfer);
    }
}
