<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Pyz\Zed\CompanyRole\Business;

use Generated\Shared\Transfer\CompanyRoleTransfer;
use Generated\Shared\Transfer\CustomerCollectionTransfer;
use Spryker\Zed\CompanyRole\Business\CompanyRoleFacade as SprykerCompanyRoleFacade;

/**
 * @method \Pyz\Zed\CompanyRole\Business\CompanyRoleBusinessFactory getFactory()
 */
class CompanyRoleFacade extends SprykerCompanyRoleFacade implements CompanyRoleFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerCollectionTransfer
     */
    public function getCustomerCollectionByIdCompanyRole(CompanyRoleTransfer $companyRoleTransfer): CustomerCollectionTransfer
    {
        return $this->getFactory()
            ->createCompanyRoleReader()
            ->getCustomerCollectionByIdCompanyRole($companyRoleTransfer);
    }
}
