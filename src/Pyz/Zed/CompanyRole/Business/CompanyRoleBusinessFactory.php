<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Zed\CompanyRole\Business;

use Pyz\Zed\CompanyRole\Business\Reader\CompanyRoleReader;
use Pyz\Zed\CompanyRole\Persistence\CompanyRoleRepositoryInterface;
use Spryker\Zed\CompanyRole\Business\CompanyRoleBusinessFactory as SprykerCompanyRoleBusinessFactory;
use Spryker\Zed\CompanyRole\Persistence\CompanyRoleRepository;

/**
 * @method \Pyz\Zed\CompanyRole\CompanyRoleConfig getConfig()
 * @method \Spryker\Zed\CompanyRole\Persistence\CompanyRoleRepositoryInterface getRepository()
 * @method \Spryker\Zed\CompanyRole\Persistence\CompanyRoleEntityManagerInterface getEntityManager()
 */
class CompanyRoleBusinessFactory extends SprykerCompanyRoleBusinessFactory
{
    /**
     * @return \Spryker\Zed\CompanyRole\Business\Reader\CompanyRoleReaderInterface
     */
    public function createCompanyRoleReader(): \Spryker\Zed\CompanyRole\Business\Reader\CompanyRoleReaderInterface
    {
        return new CompanyRoleReader(
            $this->getRepository()
        );
    }
}
