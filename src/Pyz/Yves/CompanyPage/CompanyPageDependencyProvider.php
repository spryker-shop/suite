<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Pyz\Yves\CompanyPage;

use Spryker\Yves\Kernel\Container;
use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
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
