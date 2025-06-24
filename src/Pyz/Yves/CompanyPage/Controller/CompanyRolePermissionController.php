<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Pyz\Yves\CompanyPage\Controller;

use Generated\Shared\Transfer\CompanyRoleTransfer;
use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Spryker\Yves\Kernel\PermissionAwareTrait;
use SprykerShop\Yves\CompanyPage\Controller\CompanyRolePermissionController as SprykerCompanyRolePermissionController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \Pyz\Yves\CompanyPage\CompanyPageFactory getFactory()
 */
class CompanyRolePermissionController extends SprykerCompanyRolePermissionController
{
    use PermissionAwareTrait;

    /**
     * @param int $idCompanyRole
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PermissionTransfer> $permissions
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return void
     */
    protected function saveCompanyRolePermissions(int $idCompanyRole, $permissions): void
    {
        parent::saveCompanyRolePermissions($idCompanyRole, $permissions);

        $this->addCustomersToForceLogoutListByCompanyRoleId(
            (new CompanyRoleTransfer())->setIdCompanyRole($idCompanyRole)
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleTransfer
     *
     * @return void
     */
    protected function addCustomersToForceLogoutListByCompanyRoleId(CompanyRoleTransfer $companyRoleTransfer): void
    {
        $customerCollectionTransfer = $this->getFactory()
            ->getCompanyRoleClient()
            ->getCustomerIdsByIdCompanyRole($companyRoleTransfer);

        if ($customerCollectionTransfer->getCustomers()->count() === 0) {
            return;
        }

        $redisKey = 'force_logout_customers';
        $customerIdsToLogout = $this->getFactory()->getRedisClient()->get(
            'SESSION_YVES',
            $redisKey,
        );

        $customerIdsToLogout = json_decode($customerIdsToLogout, true) ?: [];
        $customerIdsPerRole = [];

        foreach ($customerCollectionTransfer->getCustomers() as $customerTransfer) {
            if ($this->getFactory()->getCustomerClient()->getCustomer() &&
                $this->getFactory()->getCustomerClient()->getCustomer()->getIdCustomer() === $customerTransfer->getIdCustomer()
            ) {
                continue;
            }
            $customerIdsPerRole[] = $customerTransfer->getIdCustomer();
        }

        $mergedCustomerIds = array_unique(array_merge($customerIdsPerRole, $customerIdsToLogout));

        $this->getFactory()->getRedisClient()->set(
            'SESSION_YVES',
            $redisKey,
            json_encode($mergedCustomerIds),
        );
    }
}
