<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\PushNotification;

use Spryker\Zed\PushNotification\PushNotificationDependencyProvider as SprykerPushNotificationDependencyProvider;
use Spryker\Zed\PushNotificationWebPushPhp\Communication\Plugin\PushNotification\PushNotificationWebPushPhpPayloadLengthPushNotificationValidatorPlugin;
use Spryker\Zed\PushNotificationWebPushPhp\Communication\Plugin\PushNotification\PushNotificationWebPushPhpPushNotificationSenderPlugin;
use Spryker\Zed\PushNotificationWebPushPhp\Communication\Plugin\PushNotification\PushNotificationWebPushPhpPushNotificationSubscriptionValidatorPlugin;

class PushNotificationDependencyProvider extends SprykerPushNotificationDependencyProvider
{
    /**
     * @return array<int, \Spryker\Zed\PushNotificationExtension\Dependency\Plugin\PushNotificationSubscriptionValidatorPluginInterface>
     */
    protected function getPushNotificationSubscriptionValidatorPlugins(): array
    {
        return [
            new PushNotificationWebPushPhpPushNotificationSubscriptionValidatorPlugin(),
        ];
    }

    /**
     * @return array<int, \Spryker\Zed\PushNotificationExtension\Dependency\Plugin\PushNotificationValidatorPluginInterface>
     */
    protected function getPushNotificationValidatorPlugins(): array
    {
        return [
            new PushNotificationWebPushPhpPayloadLengthPushNotificationValidatorPlugin(),
        ];
    }

    /**
     * @return array<int, \Spryker\Zed\PushNotificationExtension\Dependency\Plugin\PushNotificationSenderPluginInterface>
     */
    protected function getPushNotificationSenderPlugins(): array
    {
        return [
            new PushNotificationWebPushPhpPushNotificationSenderPlugin(),
        ];
    }
}
