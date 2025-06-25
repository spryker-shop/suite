<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Pyz\Yves\SessionRedis\Validator;

use Generated\Shared\Transfer\SessionEntityRequestTransfer;
use Generated\Shared\Transfer\SessionEntityResponseTransfer;
use Spryker\Shared\SessionRedis\Hasher\HasherInterface;
use Spryker\Shared\SessionRedis\Redis\SessionRedisWrapperInterface;
use Spryker\Shared\SessionRedis\Validator\SessionEntityValidator as SprykerSessionEntityValidator;
use Spryker\Shared\SessionRedis\Handler\KeyBuilder\SessionKeyBuilderInterface;

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
        SessionKeyBuilderInterface $keyBuilder
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
            if (in_array(
                $sessionEntityRequestTransfer->getIdEntity(),
                json_decode($usersToLogout, true))
            ) {
                return (new SessionEntityResponseTransfer())->setIsSuccessfull(false);
            }
        }

        return parent::validate($sessionEntityRequestTransfer);
    }
}
