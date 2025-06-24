<?php

/**
 * Copyright 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Pyz\Zed\CompanyRole\Persistence;

use Generated\Shared\Transfer\CustomerCollectionTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;
use Spryker\Zed\CompanyRole\Persistence\CompanyRoleRepository as SprykerCompanyRoleRepository;

/**
 * @method \Pyz\Zed\CompanyRole\Persistence\CompanyRolePersistenceFactory getFactory()
 */
class CompanyRoleRepository extends SprykerCompanyRoleRepository implements CompanyRoleRepositoryInterface
{
    /**
     * @param int $idCompanyRole
     *
     * @return \Generated\Shared\Transfer\CustomerCollectionTransfer
     */
    public function getCustomerCollectionByIdCompanyRole(int $idCompanyRole): CustomerCollectionTransfer
    {
        $customerIds = $this->getFactory()
            ->createCompanyRoleToCompanyUserQuery()
            ->filterByFkCompanyRole($companyRoleTransfer->getIdCompanyRoleOrFail())
            ->joinCompanyUser()
            ->select([SpyCompanyUserTableMap::COL_FK_CUSTOMER])
            ->distinct()
            ->find()
            ->getData();

        $customerCollectionTransfer = new CustomerCollectionTransfer();
        foreach ($customerIds as $idCustomer) {
            $customerCollectionTransfer->addCustomer(
                (new CustomerTransfer())->setIdCustomer($idCustomer)
            );
        }

        return $customerCollectionTransfer;
    }
}
