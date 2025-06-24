<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Pyz\Yves\SessionRedis;

use Pyz\Yves\SessionRedis\Validator\CustomSessionEntityValidator;
use Spryker\Client\Redis\RedisClientInterface;
use Spryker\Shared\SessionRedis\Hasher\HasherInterface;
use Spryker\Shared\SessionRedis\Handler\KeyBuilder\SessionKeyBuilderInterface;
use Spryker\Shared\SessionRedis\Validator\SessionEntityValidatorInterface;
use Spryker\Yves\SessionRedis\SessionRedisFactory as SprykerSessionRedisFactory;
use Spryker\Shared\SessionRedis\Redis\SessionRedisWrapperInterface;

/**
 * @method \Spryker\Yves\SessionRedis\SessionRedisConfig getConfig()
 */
class SessionRedisFactory extends SprykerSessionRedisFactory
{
    /**
     * @return \Spryker\Shared\SessionRedis\Validator\SessionEntityValidatorInterface
     */
    public function createSessionEntityValidator(): SessionEntityValidatorInterface
    {
        return new CustomSessionEntityValidator(
            $this->getSessionRedisWrapper(),
            $this->createHasher(),
            $this->createSessionKeyBuilder()
        );
    }

}
