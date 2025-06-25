<?php

namespace Pyz\Yves\CustomerPage\Plugin\Provider;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Client\Redis\RedisClientInterface;
use SprykerShop\Yves\CustomerPage\Plugin\Provider\CustomerAuthenticationSuccessHandler as SprykerCustomerAuthenticationSuccessHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class CustomerAuthenticationSuccessHandler extends SprykerCustomerAuthenticationSuccessHandler
{
    /**
     * @var string
     */
    protected const REDIS_CONNECTION = 'SESSION_YVES';

    /**
     * @var string
     */
    protected const REDIS_KEY = 'force_logout_customers';

    /**
     * @var \Spryker\Client\Redis\RedisClientInterface
     */
    protected $redisClient;

    /**
     * @param \Spryker\Client\Redis\RedisClientInterface $redisClient
     */
    public function __construct(RedisClientInterface $redisClient)
    {
        $this->redisClient = $redisClient;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Security\Core\Authentication\Token\TokenInterface $token
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token): Response
    {
        /** @var \SprykerShop\Yves\CustomerPage\Security\Customer $customer */
        $customer = $token->getUser();

        // Get current list of users to logout
        $usersToLogout = $this->getUsersToLogout();

        // Remove current user from the logout list
        $updatedUsersToLogout = array_diff(
            $usersToLogout,
            [$customer->getCustomerTransfer()->getIdCustomer()]
        );
        $this->setUsersToLogout($updatedUsersToLogout);

        return  parent::onAuthenticationSuccess($request, $token);
    }

    /**
     * @return array<int>
     */
    protected function getUsersToLogout(): array
    {
        $usersToLogout = $this->redisClient->get(
            static::REDIS_CONNECTION,
            static::REDIS_KEY
        );

        return $usersToLogout ? json_decode($usersToLogout, true) : [];
    }

    /**
     * @param array<int> $users
     *
     * @return void
     */
    protected function setUsersToLogout(array $users): void
    {
        $this->redisClient->set(
            static::REDIS_CONNECTION,
            static::REDIS_KEY,
            json_encode($users)
        );
    }
}
