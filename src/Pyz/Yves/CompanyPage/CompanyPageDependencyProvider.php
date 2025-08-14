<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Yves\CompanyPage;

use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\CompanyPage\CompanyPageDependencyProvider as SprykerShopCompanyPageDependencyProvider;

class CompanyPageDependencyProvider extends SprykerShopCompanyPageDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_REDIS = 'CLIENT_REDIS';

    /**
     * @var string
     */
    public const CLIENT_COMPANY_ROLE = 'CLIENT_COMPANY_ROLE';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addRedisClient($container);
        $container = $this->addCompanyRoleClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addRedisClient(Container $container): Container
    {
        $container->set(static::CLIENT_REDIS, function (Container $container) {
            return $container->getLocator()->redis()->client();
        });

        return $container;
    }
}
