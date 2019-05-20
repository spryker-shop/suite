<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductStorage\Business;

use Pyz\Zed\ProductStorage\Business\Storage\ProductAbstractStorageWriter;
use Pyz\Zed\ProductStorage\Business\Storage\ProductConcreteStorageWriter;
use Pyz\Zed\ProductStorage\ProductStorageDependencyProvider;
use Spryker\Zed\ProductStorage\Business\ProductStorageBusinessFactory as SprykerProductStorageBusinessFactory;

/**
 * @method \Spryker\Zed\ProductStorage\Persistence\ProductStorageQueryContainer getQueryContainer()
 * @method \Pyz\Zed\ProductStorage\ProductStorageConfig getConfig()
 */
class ProductStorageBusinessFactory extends SprykerProductStorageBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductStorage\Business\Storage\ProductAbstractStorageWriterInterface
     */
    public function createProductAbstractStorageWriter()
    {
        return new ProductAbstractStorageWriter(
            $this->getProductFacade(),
            $this->createAttributeMap(),
            $this->getQueryContainer(),
            $this->getConfig()->isSendingToQueue(),
            $this->getSynchronizationService(),
            $this->getQueueClient()
        );
    }

    /**
     * @return \Spryker\Zed\ProductStorage\Business\Storage\ProductConcreteStorageWriterInterface
     */
    public function createProductConcreteStorageWriter()
    {
        return new ProductConcreteStorageWriter(
            $this->getProductFacade(),
            $this->getQueryContainer(),
            $this->getConfig()->isSendingToQueue(),
            $this->getSynchronizationService(),
            $this->getQueueClient()
        );
    }

    /**
     * @return \Spryker\Client\Queue\QueueClientInterface
     */
    protected function getQueueClient()
    {
        return $this->getProvidedDependency(ProductStorageDependencyProvider::CLIENT_QUEUE);
    }

    /**
     * @return \Spryker\Service\Synchronization\SynchronizationServiceInterface
     */
    public function getSynchronizationService()
    {
        return $this->getProvidedDependency(ProductStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }
}
