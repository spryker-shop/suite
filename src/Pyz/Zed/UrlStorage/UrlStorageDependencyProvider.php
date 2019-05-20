<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\UrlStorage;

use Spryker\Zed\Kernel\Container;
use Spryker\Zed\UrlStorage\UrlStorageDependencyProvider as SprykerUrlStorageDependencyProvider;

class UrlStorageDependencyProvider extends SprykerUrlStorageDependencyProvider
{
    public const SERVICE_SYNCHRONIZATION = 'SERVICE_SYNCHRONIZATION';
    public const CLIENT_QUEUE = 'CLIENT_QUEUE';
    public const FACADE_PUBLISHER = 'FACADE_PUBLISHER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addSynchronizationService($container);
        $container = $this->addQueueClient($container);
        $container = $this->addPublisherFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSynchronizationService(Container $container)
    {
        $container->set(static::SERVICE_SYNCHRONIZATION, function (Container $container) {
            return $container->getLocator()->synchronization()->service();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addQueueClient(Container $container)
    {
        $container->set(static::CLIENT_QUEUE, function (Container $container) {
            return $container->getLocator()->queue()->client();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPublisherFacade(Container $container): Container
    {
        $container->set(static::FACADE_PUBLISHER, function (Container $container) {
            return $container->getLocator()->publisher()->facade();
        });

        return $container;
    }
}
