<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Pyz\Yves\CompanyPage;

use Pyz\Client\CompanyRole\CompanyRoleClient;
use Spryker\Client\Redis\RedisClientInterface;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\CompanyPage\CompanyPageFactory as SprykerShopCompanyPageFactory;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCustomerClientInterface;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyRoleClientInterface;
use Spryker\Zed\CompanyRole\Business\CompanyRoleFacadeInterface;
use Spryker\Client\CompanyRole\CompanyRoleClientInterface as PyzCompanyRoleClientInterface;

/**
 * @method \SprykerShop\Yves\CompanyPage\CompanyPageConfig getConfig()
 */
class CompanyPageFactory extends SprykerShopCompanyPageFactory
{
    /**
     * @return \Spryker\Client\Redis\RedisClientInterface
     */
    public function getRedisClient(): RedisClientInterface
    {
        return $this->getProvidedDependency(CompanyPageDependencyProvider::CLIENT_REDIS);
    }

    /**
     * @return \Pyz\Client\CompanyRole\CompanyRoleClientInterface
     */
    public function getPyzCompanyRoleClient()
    {
        return new CompanyRoleClient();
    }
}
