<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductStorage;

use Spryker\Zed\Kernel\Container;
use Spryker\Zed\MerchantProductStorage\Communication\Plugin\ProductStorage\MerchantProductAbstractStorageExpanderPlugin;
use Spryker\Zed\MerchantProductStorage\Communication\Plugin\ProductStorage\MerchantProductConcreteStorageCollectionExpanderPlugin;
use Spryker\Zed\ProductApproval\Communication\Plugin\ProductStorage\ProductApprovalProductAbstractStorageCollectionFilterPlugin;
use Spryker\Zed\ProductApproval\Communication\Plugin\ProductStorage\ProductApprovalProductConcreteStorageCollectionFilterPlugin;
use Spryker\Zed\ProductStorage\ProductStorageDependencyProvider as SprykerProductStorageDependencyProvider;

class ProductStorageDependencyProvider extends SprykerProductStorageDependencyProvider
{
    /**
     * @var string
     */
    public const SERVICE_SYNCHRONIZATION = 'SERVICE_SYNCHRONIZATION';

    /**
     * @var string
     */
    public const CLIENT_QUEUE = 'CLIENT_QUEUE';

    /**
     * @var string
     */
    public const FACADE_PROPEL = 'FACADE_PROPEL';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addSynchronizationService($container);
        $container = $this->addQueueClient($container);
        $container = $this->addPropelFacade($container);

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\ProductStorageExtension\Dependency\Plugin\ProductAbstractStorageExpanderPluginInterface>
     */
    protected function getProductAbstractStorageExpanderPlugins(): array
    {
        return [
            new MerchantProductAbstractStorageExpanderPlugin(),
        ];
    }

    /**
     * @return array<\Spryker\Zed\ProductStorageExtension\Dependency\Plugin\ProductConcreteStorageCollectionExpanderPluginInterface>
     */
    protected function getProductConcreteStorageCollectionExpanderPlugins(): array
    {
        return [
            new MerchantProductConcreteStorageCollectionExpanderPlugin(),
        ];
    }

    /**
     * @return array<\Spryker\Zed\ProductStorageExtension\Dependency\Plugin\ProductAbstractStorageCollectionFilterPluginInterface>
     */
    protected function getProductAbstractStorageCollectionFilterPlugins(): array
    {
        return [
            new ProductApprovalProductAbstractStorageCollectionFilterPlugin(),
        ];
    }

    /**
     * @return array<\Spryker\Zed\ProductStorageExtension\Dependency\Plugin\ProductConcreteStorageCollectionFilterPluginInterface>
     */
    protected function getProductConcreteStorageCollectionFilterPlugins(): array
    {
        return [
            new ProductApprovalProductConcreteStorageCollectionFilterPlugin(),
        ];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function addSynchronizationService(Container $container): Container
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
    public function addQueueClient(Container $container): Container
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
    public function addPropelFacade(Container $container): Container
    {
        $container->set(static::FACADE_PROPEL, function (Container $container) {
            return $container->getLocator()->propel()->facade();
        });

        return $container;
    }
}
