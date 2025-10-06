<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Yves\SessionRedis\Validator;

use Generated\Shared\Transfer\SessionEntityRequestTransfer;
use Generated\Shared\Transfer\SessionEntityResponseTransfer;
use Spryker\Shared\SessionRedis\Handler\KeyBuilder\SessionKeyBuilderInterface;
use Spryker\Shared\SessionRedis\Hasher\HasherInterface;
use Spryker\Shared\SessionRedis\Redis\SessionRedisWrapperInterface;
use Spryker\Shared\SessionRedis\Validator\SessionEntityValidator as SprykerSessionEntityValidator;

class CustomSessionEntityValidator extends SprykerSessionEntityValidator
{
    /**
     * @var \Spryker\Shared\SessionRedis\Redis\SessionRedisWrapperInterface
     */
    protected SessionRedisWrapperInterface $redisClient;

    /**
     * @var string
     */
    protected const REDIS_KEY = 'force_logout_customers';

    /**
     * @param \Spryker\Shared\SessionRedis\Redis\SessionRedisWrapperInterface $redisClient
     * @param \Spryker\Shared\SessionRedis\Hasher\HasherInterface $hasher
     * @param \Spryker\Shared\SessionRedis\Handler\KeyBuilder\SessionKeyBuilderInterface $keyBuilder
     */
    public function __construct(
        SessionRedisWrapperInterface $redisClient,
        HasherInterface $hasher,
        SessionKeyBuilderInterface $keyBuilder,
    ) {
        parent::__construct($redisClient, $hasher, $keyBuilder);
    }

    /**
     * @param \Generated\Shared\Transfer\SessionEntityRequestTransfer $sessionEntityRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SessionEntityResponseTransfer
     */
    public function validate(SessionEntityRequestTransfer $sessionEntityRequestTransfer): SessionEntityResponseTransfer
    {
        $usersToLogout = $this->redisClient->get(static::REDIS_KEY);

        if ($usersToLogout) {
            if (
                in_array(
                    $sessionEntityRequestTransfer->getIdEntity(),
                    json_decode($usersToLogout, true),
                )
            ) {
                return (new SessionEntityResponseTransfer())->setIsSuccessfull(false);
            }
        }

        return parent::validate($sessionEntityRequestTransfer);
    }
}
