<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\Session;

use Spryker\Yves\Session\SessionDependencyProvider as SprykerSessionDependencyProvider;
use Spryker\Yves\SessionRedis\Plugin\Session\SessionHandlerRedisLockingPlugin;
use Spryker\Yves\SessionRedis\Plugin\Session\SessionHandlerRedisPlugin;

class SessionDependencyProvider extends SprykerSessionDependencyProvider
{
    /**
     * @return \Spryker\Shared\SessionExtension\Dependency\Plugin\SessionHandlerPluginInterface[]
     */
    protected function getSessionHandlerPlugins(): array
    {
        return [
            new SessionHandlerRedisPlugin(),
            new SessionHandlerRedisLockingPlugin(),
        ];
    }
}
