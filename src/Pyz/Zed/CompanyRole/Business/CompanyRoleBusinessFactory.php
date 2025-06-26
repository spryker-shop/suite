<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Pyz\Zed\CompanyRole\Business;

use Pyz\Zed\CompanyRole\Business\Reader\CompanyRoleReader;
use Pyz\Zed\CompanyRole\Business\Reader\CompanyRoleReaderInterface;
use Spryker\Zed\CompanyRole\Business\CompanyRoleBusinessFactory as SprykerCompanyRoleBusinessFactory;

/**
 * @method \Pyz\Zed\CompanyRole\CompanyRoleConfig getConfig()
 * @method \Pyz\Zed\CompanyRole\Persistence\CompanyRoleRepositoryInterface getRepository()
 */
class CompanyRoleBusinessFactory extends SprykerCompanyRoleBusinessFactory
{
    /**
     * @return \Pyz\Zed\CompanyRole\Business\Reader\CompanyRoleReaderInterface
     */
    public function createCompanyRoleReader(): CompanyRoleReaderInterface
    {
        return new CompanyRoleReader($this->getRepository());
    }
}
