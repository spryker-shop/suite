<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Zed\CompanyRole\Business\Reader;

use Generated\Shared\Transfer\CompanyRoleResponseTransfer;
use Generated\Shared\Transfer\CompanyRoleTransfer;
use Generated\Shared\Transfer\CustomerCollectionTransfer;
use Pyz\Zed\CompanyRole\Persistence\CompanyRoleRepositoryInterface;
use Spryker\Zed\CompanyRole\Business\Reader\CompanyRoleReader as SprykerCompanyRoleReader;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Pyz\Zed\CompanyRole\Persistence\CompanyRoleRepositoryInterface getRepository()
 */
class CompanyRoleReader extends SprykerCompanyRoleReader implements CompanyRoleReaderInterface
{
    /**
     * @var \Pyz\Zed\CompanyRole\Persistence\CompanyRoleRepositoryInterface
     */
    protected $companyRoleRepository;

    /**
     * @param \Pyz\Zed\CompanyRole\Persistence\CompanyRoleRepositoryInterface $companyRoleRepository
     */
    public function __construct(CompanyRoleRepositoryInterface $companyRoleRepository)
    {
        parent::__construct($companyRoleRepository);
        $this->companyRoleRepository = $companyRoleRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerCollectionTransfer
     */
    public function getCustomerCollectionByIdCompanyRole(CompanyRoleTransfer $companyRoleTransfer): CustomerCollectionTransfer
    {
        $companyRoleTransfer->requireIdCompanyRole();
        $idCompanyRole = $companyRoleTransfer->getIdCompanyRoleOrFail();

        return $this->companyRoleRepository->getCustomerCollectionByIdCompanyRole($idCompanyRole);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyRoleResponseTransfer
     */
    public function findCompanyRoleByUuid(CompanyRoleTransfer $companyRoleTransfer): CompanyRoleResponseTransfer
    {
        return parent::findCompanyRoleByUuid($companyRoleTransfer);
    }
}
