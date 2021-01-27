<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductStorage\Business;

use Pyz\Zed\ProductStorage\Business\Storage\Cte\ProductAbstractStorageMariaDbCte;
use Pyz\Zed\ProductStorage\Business\Storage\Cte\ProductAbstractStoragePostgresCte;
use Pyz\Zed\ProductStorage\Business\Storage\Cte\ProductConcreteStorageMariaDbCte;
use Pyz\Zed\ProductStorage\Business\Storage\Cte\ProductConcreteStoragePostgresCte;
use Pyz\Zed\ProductStorage\Business\Storage\Cte\ProductStorageCteStrategyInterface;
use Pyz\Zed\ProductStorage\Business\Storage\Cte\ProductStorageCteStrategyResolver;
use Pyz\Zed\ProductStorage\Business\Storage\Cte\ProductStorageCteStrategyResolverInterface;
use Pyz\Zed\ProductStorage\Business\Storage\ProductAbstractStorageWriter;
use Pyz\Zed\ProductStorage\Business\Storage\ProductConcreteStorageWriter;
use Pyz\Zed\ProductStorage\ProductStorageDependencyProvider;
use Pyz\Zed\Propel\Business\PropelFacadeInterface;
use Spryker\Client\Queue\QueueClientInterface;
use Spryker\Service\Synchronization\SynchronizationServiceInterface;
use Spryker\Zed\ProductStorage\Business\ProductStorageBusinessFactory as SprykerProductStorageBusinessFactory;
use Spryker\Zed\ProductStorage\Business\Storage\ProductAbstractStorageWriterInterface;
use Spryker\Zed\ProductStorage\Business\Storage\ProductConcreteStorageWriterInterface;

/**
 * @method \Pyz\Zed\ProductStorage\ProductStorageConfig getConfig()
 */
class ProductStorageBusinessFactory extends SprykerProductStorageBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductStorage\Business\Storage\ProductAbstractStorageWriterInterface
     */
    public function createProductAbstractStorageWriter(): ProductAbstractStorageWriterInterface
    {
        if (!$this->getConfig()->isCteEnabled()) {
            return parent::createProductAbstractStorageWriter();
        }

        return new ProductAbstractStorageWriter(
            $this->getProductFacade(),
            $this->createAttributeMap(),
            $this->getQueryContainer(),
            $this->getStoreFacade(),
            $this->getConfig()->isSendingToQueue(),
            $this->getProductAbstractStorageExpanderPlugins(),
            $this->getSynchronizationService(),
            $this->getQueueClient(),
            $this->createProductAbstractStorageCteStrategyResolver()->resolve()
        );
    }

    /**
     * @return \Pyz\Zed\ProductStorage\Business\Storage\Cte\ProductStorageCteStrategyResolverInterface
     */
    public function createProductAbstractStorageCteStrategyResolver(): ProductStorageCteStrategyResolverInterface
    {
        return new ProductStorageCteStrategyResolver($this->createProductAbstractStorageCteCollection(), $this->getPropelFacade());
    }

    /**
     * @return \Pyz\Zed\ProductStorage\Business\Storage\Cte\ProductStorageCteStrategyInterface[]
     */
    public function createProductAbstractStorageCteCollection(): array
    {
        return [
            $this->createProductAbstractStoragePostgresCte(),
            $this->createProductAbstractStorageMariaDbCte(),
        ];
    }

    /**
     * @return \Pyz\Zed\ProductStorage\Business\Storage\Cte\ProductStorageCteStrategyInterface
     */
    public function createProductAbstractStoragePostgresCte(): ProductStorageCteStrategyInterface
    {
        return new ProductAbstractStoragePostgresCte();
    }

    /**
     * @return \Pyz\Zed\ProductStorage\Business\Storage\Cte\ProductStorageCteStrategyInterface
     */
    public function createProductAbstractStorageMariaDbCte(): ProductStorageCteStrategyInterface
    {
        return new ProductAbstractStorageMariaDbCte();
    }

    /**
     * @return \Spryker\Zed\ProductStorage\Business\Storage\ProductConcreteStorageWriterInterface
     */
    public function createProductConcreteStorageWriter(): ProductConcreteStorageWriterInterface
    {
        if (!$this->getConfig()->isCteEnabled()) {
            return parent::createProductConcreteStorageWriter();
        }

        return new ProductConcreteStorageWriter(
            $this->getProductFacade(),
            $this->getQueryContainer(),
            $this->getConfig()->isSendingToQueue(),
            $this->getSynchronizationService(),
            $this->getQueueClient(),
            $this->createProductConcreteStorageCteStrategyResolver()->resolve()
        );
    }

    /**
     * @return \Pyz\Zed\ProductStorage\Business\Storage\Cte\ProductStorageCteStrategyResolverInterface
     */
    public function createProductConcreteStorageCteStrategyResolver(): ProductStorageCteStrategyResolverInterface
    {
        return new ProductStorageCteStrategyResolver($this->createProductConcreteStorageCteCollection(), $this->getPropelFacade());
    }

    /**
     * @return \Pyz\Zed\ProductStorage\Business\Storage\Cte\ProductStorageCteStrategyInterface[]
     */
    public function createProductConcreteStorageCteCollection(): array
    {
        return [
            $this->createProductConcreteStoragePostgresCte(),
            $this->createProductConcreteStorageMariaDbCte(),
        ];
    }

    /**
     * @return \Pyz\Zed\ProductStorage\Business\Storage\Cte\ProductStorageCteStrategyInterface
     */
    public function createProductConcreteStoragePostgresCte(): ProductStorageCteStrategyInterface
    {
        return new ProductConcreteStoragePostgresCte();
    }

    /**
     * @return \Pyz\Zed\ProductStorage\Business\Storage\Cte\ProductStorageCteStrategyInterface
     */
    public function createProductConcreteStorageMariaDbCte(): ProductStorageCteStrategyInterface
    {
        return new ProductConcreteStorageMariaDbCte();
    }

    /**
     * @return \Pyz\Zed\Propel\Business\PropelFacadeInterface
     */
    public function getPropelFacade(): PropelFacadeInterface
    {
        return $this->getProvidedDependency(ProductStorageDependencyProvider::FACADE_PROPEL);
    }

    /**
     * @return \Spryker\Service\Synchronization\SynchronizationServiceInterface
     */
    public function getSynchronizationService(): SynchronizationServiceInterface
    {
        return $this->getProvidedDependency(ProductStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }

    /**
     * @return \Spryker\Client\Queue\QueueClientInterface
     */
    public function getQueueClient(): QueueClientInterface
    {
        return $this->getProvidedDependency(ProductStorageDependencyProvider::CLIENT_QUEUE);
    }
}
