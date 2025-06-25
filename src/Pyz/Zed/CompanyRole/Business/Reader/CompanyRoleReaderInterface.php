<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Pyz\Zed\CompanyRole\Business\Reader;

use Generated\Shared\Transfer\CompanyRoleTransfer;
use Generated\Shared\Transfer\CustomerCollectionTransfer;
use \Spryker\Zed\CompanyRole\Business\Reader\CompanyRoleReaderInterface as SprykerCompanyRoleReaderInterface;

interface CompanyRoleReaderInterface extends SprykerCompanyRoleReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerCollectionTransfer
     */
    public function getCustomerCollectionByIdCompanyRole(CompanyRoleTransfer $companyRoleTransfer): CustomerCollectionTransfer;
}
