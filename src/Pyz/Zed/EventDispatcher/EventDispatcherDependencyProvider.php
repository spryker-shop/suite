<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\EventDispatcher;

use Spryker\Zed\Acl\Communication\Plugin\EventDispatcher\AccessControlEventDispatcherPlugin;
use Spryker\Zed\Application\Communication\Plugin\EventDispatcher\HeadersSecurityEventDispatcherPlugin;
use Spryker\Zed\Auth\Communication\Plugin\EventDispatcher\AuthorizationEventDispatcherPlugin;
use Spryker\Zed\Auth\Communication\Plugin\EventDispatcher\RedirectAfterLoginEventDispatcherPlugin;
use Spryker\Zed\EventBehavior\Communication\Plugin\EventDispatcher\EventBehaviorEventDispatcherPlugin;
use Spryker\Zed\EventDispatcher\EventDispatcherDependencyProvider as SprykerEventDispatcherDependencyProvider;
use Spryker\Zed\Locale\Communication\Plugin\EventDispatcher\LocaleEventDispatcherPlugin;
use Spryker\Zed\Monitoring\Communication\Plugin\EventDispatcher\MonitoringRequestTransactionEventDispatcherPlugin;
use Spryker\Zed\Router\Communication\Plugin\EventDispatcher\RouterListenerEventDispatcherPlugin;
use Spryker\Zed\Router\Communication\Plugin\EventDispatcher\RouterLocaleEventDispatcherPlugin;
use Spryker\Zed\Router\Communication\Plugin\EventDispatcher\RouterSslRedirectEventDispatcherPlugin;
use Spryker\Zed\Twig\Communication\Plugin\EventDispatcher\TwigEventDispatcherPlugin;

class EventDispatcherDependencyProvider extends SprykerEventDispatcherDependencyProvider
{
    /**
     * @return \Spryker\Shared\EventDispatcherExtension\Dependency\Plugin\EventDispatcherPluginInterface[]
     */
    protected function getEventDispatcherPlugins(): array
    {
        return [
            new AuthorizationEventDispatcherPlugin(),
            new AccessControlEventDispatcherPlugin(),
            new RedirectAfterLoginEventDispatcherPlugin(),
            new TwigEventDispatcherPlugin(),
            new LocaleEventDispatcherPlugin(),
            new RouterLocaleEventDispatcherPlugin(),
            new HeadersSecurityEventDispatcherPlugin(),
            new EventBehaviorEventDispatcherPlugin(),
            new RouterListenerEventDispatcherPlugin(),
            new RouterSslRedirectEventDispatcherPlugin(),
            new MonitoringRequestTransactionEventDispatcherPlugin(),
        ];
    }
}
