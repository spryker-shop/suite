<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CompanyRole\Communication\Controller;

use Generated\Shared\Transfer\CompanyRoleTransfer;
use Generated\Shared\Transfer\CustomerCollectionTransfer;
use Spryker\Zed\CompanyRole\Communication\Controller\GatewayController as SprykerGatewayController;

/**
 * @method \Pyz\Zed\CompanyRole\Business\CompanyRoleFacadeInterface getFacade()
 * @method \Pyz\Zed\CompanyRole\Persistence\CompanyRoleRepositoryInterface getRepository()
 */
class GatewayController extends SprykerGatewayController
{
    /**
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerCollectionTransfer
     */
    public function getCustomerIdsByIdCompanyRoleAction(CompanyRoleTransfer $companyRoleTransfer): CustomerCollectionTransfer
    {
        return $this->getFacade()->getCustomerCollectionByIdCompanyRole($companyRoleTransfer);
    }
}
