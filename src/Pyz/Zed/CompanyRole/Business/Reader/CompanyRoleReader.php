<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Pyz\Zed\CompanyRole\Business\Reader;

use Generated\Shared\Transfer\CompanyRoleTransfer;
use Generated\Shared\Transfer\CustomerCollectionTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;
use Orm\Zed\CompanyUser\Persistence\Map\SpyCompanyUserTableMap;
use Orm\Zed\CompanyRole\Persistence\Map\SpyCompanyRoleToCompanyUserTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\CompanyRole\Business\Reader\CompanyRoleReader as SprykerCompanyRoleReader;
use Spryker\Zed\CompanyRole\Persistence\CompanyRoleRepositoryInterface;
use Pyz\Zed\CompanyRole\Persistence\CompanyRolePersistenceFactoryInterface;

class CompanyRoleReader extends SprykerCompanyRoleReader
{
    /**
     * @var \Spryker\Zed\CompanyRole\Persistence\CompanyRoleRepositoryInterface
     */
    protected $companyRoleRepository;

    /**
     * @var \Pyz\Zed\CompanyRole\Persistence\CompanyRolePersistenceFactoryInterface
     */
    protected $factory;

    /**
     * @param \Spryker\Zed\CompanyRole\Persistence\CompanyRoleRepositoryInterface $companyRoleRepository
     * @param \Pyz\Zed\CompanyRole\Persistence\CompanyRolePersistenceFactoryInterface $factory
     */
    public function __construct(
        CompanyRoleRepositoryInterface $companyRoleRepository,
        CompanyRolePersistenceFactoryInterface $factory
    ) {
        parent::__construct($companyRoleRepository);
        $this->companyRoleRepository = $companyRoleRepository;
        $this->factory = $factory;
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
