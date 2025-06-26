<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CompanyPage\Controller;

use Generated\Shared\Transfer\CompanyRoleTransfer;
use Spryker\Yves\Kernel\PermissionAwareTrait;
use SprykerShop\Yves\CompanyPage\Controller\CompanyRolePermissionController as SprykerCompanyRolePermissionController;

/**
 * @method \Pyz\Yves\CompanyPage\CompanyPageFactory getFactory()
 */
class CompanyRolePermissionController extends SprykerCompanyRolePermissionController
{
    use PermissionAwareTrait;

    /**
     * @var string
     */
    protected const REDIS_CONNECTION = 'SESSION_YVES';

    /**
     * @var string
     */
    protected const REDIS_KEY = 'force_logout_customers';

    /**
     * @param int $idCompanyRole
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PermissionTransfer> $permissions
     *
     * @return void
     */
    protected function saveCompanyRolePermissions(int $idCompanyRole, $permissions): void
    {
        parent::saveCompanyRolePermissions($idCompanyRole, $permissions);

        $this->addCustomersToForceLogoutListByCompanyRoleId(
            (new CompanyRoleTransfer())->setIdCompanyRole($idCompanyRole),
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
            ->getPyzCompanyRoleClient()->getCustomerIdsByIdCompanyRole($companyRoleTransfer);

        if ($customerCollectionTransfer->getCustomers()->count() === 0) {
            return;
        }

        $customerIdsToLogout = $this->getFactory()->getRedisClient()->get(
            static::REDIS_CONNECTION,
            static::REDIS_KEY,
        );

        $customerIdsToLogout = json_decode($customerIdsToLogout, true) ?: [];

        $customerIdsPerRole = [];

        foreach ($customerCollectionTransfer->getCustomers() as $customerTransfer) {
            if (
                $this->getFactory()->getCustomerClient()->getCustomer() &&
                $this->getFactory()->getCustomerClient()->getCustomer()->getIdCustomer() === $customerTransfer->getIdCustomer()
            ) {
                continue;
            }
            $customerIdsPerRole[] = $customerTransfer->getIdCustomer();
        }

        $this->getFactory()->getRedisClient()->set(
            static::REDIS_CONNECTION,
            static::REDIS_KEY,
            json_encode($customerIdsPerRole + $customerIdsToLogout),
        );
    }
}
