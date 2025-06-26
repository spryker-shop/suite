<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Zed\CompanyRole\Business;

use Generated\Shared\Transfer\CompanyRoleTransfer;
use Generated\Shared\Transfer\CustomerCollectionTransfer;
use Spryker\Zed\CompanyRole\Business\CompanyRoleFacade as SprykerCompanyRoleFacade;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Pyz\Zed\CompanyRole\Business\CompanyRoleBusinessFactory getFactory()
 * @method \Pyz\Zed\CompanyRole\Persistence\CompanyRoleRepositoryInterface getRepository()
 * @method \Spryker\Zed\CompanyRole\Persistence\CompanyRoleEntityManagerInterface getEntityManager()
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
