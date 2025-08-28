<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

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
