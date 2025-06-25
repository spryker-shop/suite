<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Pyz\Zed\CompanyRole\Persistence;

use Generated\Shared\Transfer\CustomerCollectionTransfer;
use Spryker\Zed\CompanyRole\Persistence\CompanyRoleRepositoryInterface as SprykerCompanyRoleRepositoryInterface;

interface CompanyRoleRepositoryInterface extends SprykerCompanyRoleRepositoryInterface
{
    /**
     * @param int $idCompanyRole
     *
     * @return \Generated\Shared\Transfer\CustomerCollectionTransfer
     */
    public function getCustomerCollectionByIdCompanyRole(int $idCompanyRole): CustomerCollectionTransfer;
}
