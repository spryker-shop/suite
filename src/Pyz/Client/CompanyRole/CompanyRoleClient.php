<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

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
