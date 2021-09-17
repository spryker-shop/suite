<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\SessionRedis;

use Spryker\Zed\SecuritySystemUser\Communication\Plugin\SessionRedis\SystemUserSessionRedisLifeTimeCalculatorPlugin;
use Spryker\Zed\SessionRedis\SessionRedisDependencyProvider as SprykerSessionRedisDependencyProvider;

class SessionRedisDependencyProvider extends SprykerSessionRedisDependencyProvider
{
    /**
     * @return array<\Spryker\Zed\SessionRedisExtension\Dependency\Plugin\SessionRedisLifeTimeCalculatorPluginInterface>
     */
    protected function getSessionRedisLifeTimeCalculatorPlugins(): array
    {
        return [
            new SystemUserSessionRedisLifeTimeCalculatorPlugin(),
        ];
    }
}
