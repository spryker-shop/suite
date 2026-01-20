<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Client\CompanyRole;

use Pyz\Client\CompanyRole\Zed\CompanyRoleStub;
use Spryker\Client\CompanyRole\CompanyRoleFactory as SprykerCompanyRoleFactory;
use Spryker\Client\CompanyRole\Zed\CompanyRoleStubInterface;

/**
 * @method \Pyz\Client\CompanyRole\CompanyRoleConfig getConfig()
 */
class CompanyRoleFactory extends SprykerCompanyRoleFactory
{
    /**
     * @return \Pyz\Client\CompanyRole\Zed\CompanyRoleStubInterface
     */
    public function createZedCompanyRoleStub(): CompanyRoleStubInterface
    {
        return new CompanyRoleStub($this->getZedRequestClient());
    }
}
