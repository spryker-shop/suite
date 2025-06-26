<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Yves\CompanyPage;

use Pyz\Client\CompanyRole\CompanyRoleClient;
use Pyz\Client\CompanyRole\CompanyRoleClientInterface;
use Spryker\Client\Redis\RedisClientInterface;
use SprykerShop\Yves\CompanyPage\CompanyPageFactory as SprykerShopCompanyPageFactory;

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
    public function getPyzCompanyRoleClient(): CompanyRoleClientInterface
    {
        return new CompanyRoleClient();
    }
}
