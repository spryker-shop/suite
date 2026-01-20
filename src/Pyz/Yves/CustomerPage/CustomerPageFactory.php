<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Yves\CustomerPage;

use Pyz\Yves\CustomerPage\Plugin\Provider\CustomerAuthenticationSuccessHandler;
use Spryker\Client\Redis\RedisClientInterface;
use SprykerShop\Yves\CustomerPage\Authenticator\CustomerLoginFormAuthenticator;
use SprykerShop\Yves\CustomerPage\CustomerPageFactory as SprykerCustomerPageFactory;
use Symfony\Component\Security\Http\Authenticator\AuthenticatorInterface;

class CustomerPageFactory extends SprykerCustomerPageFactory
{
    /**
     * @return \Pyz\Yves\CustomerPage\Plugin\Provider\CustomerAuthenticationSuccessHandler
     */
    public function createCustomerAuthenticationSuccessHandler(): CustomerAuthenticationSuccessHandler
    {
        return new CustomerAuthenticationSuccessHandler(
            $this->getRedisClient(),
        );
    }

    /**
     * @return \Spryker\Client\Redis\RedisClientInterface
     */
    protected function getRedisClient(): RedisClientInterface
    {
        return $this->getProvidedDependency(CustomerPageDependencyProvider::CLIENT_REDIS);
    }

    /**
     * @return \Symfony\Component\Security\Http\Authenticator\AuthenticatorInterface
     */
    public function createCustomerLoginAuthenticator(): AuthenticatorInterface
    {
        return new CustomerLoginFormAuthenticator(
            $this->createCustomerUserProvider(),
            $this->createRememberMeBadge(),
            $this->createCustomerAuthenticationSuccessHandler(),
            $this->createCustomerAuthenticationFailureHandler(),
            $this->getRouter(),
            $this->createMultiFactorAuthBadge(),
        );
    }
}
