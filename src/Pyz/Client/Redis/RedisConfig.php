<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Client\Redis;

use Spryker\Client\Redis\RedisConfig as SprykerRedisConfig;

class RedisConfig extends SprykerRedisConfig
{
    /**
     * @api
     *
     * @return bool
     */
    public function usePhpredis(): bool
    {
        return true;
    }
}
