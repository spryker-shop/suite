<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Pyz\Zed\CompanyRole\Business\Reader;

use Generated\Shared\Transfer\CompanyRoleTransfer;
use Generated\Shared\Transfer\CustomerCollectionTransfer;
use Spryker\Zed\CompanyRole\Business\Reader\CompanyRoleReader as SprykerCompanyRoleReader;
use Spryker\Zed\CompanyRole\Persistence\CompanyRoleRepositoryInterface;

class CompanyRoleReader extends SprykerCompanyRoleReader implements CompanyRoleReaderInterface
{
    /**
     * @var \Spryker\Zed\CompanyRole\Persistence\CompanyRoleRepositoryInterface
     */
    protected $companyRoleRepository;

    /**
     * @param \Spryker\Zed\CompanyRole\Persistence\CompanyRoleRepositoryInterface $companyRoleRepository
     */
    public function __construct(
        CompanyRoleRepositoryInterface $companyRoleRepository,
    ) {
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
        $idCompanyRole = $companyRoleTransfer->getIdCompanyRole();

        return $this->companyRoleRepository->getCustomerCollectionByIdCompanyRole($idCompanyRole);
    }
}
