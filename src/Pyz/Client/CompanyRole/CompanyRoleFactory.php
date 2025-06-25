<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

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
