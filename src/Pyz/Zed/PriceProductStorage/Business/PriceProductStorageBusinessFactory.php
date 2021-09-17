<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\PriceProductStorage\Business;

use Pyz\Zed\PriceProductStorage\Business\Storage\Cte\PriceProductAbstract\PriceProductAbstractStorageMariaDbCte;
use Pyz\Zed\PriceProductStorage\Business\Storage\Cte\PriceProductAbstract\PriceProductAbstractStoragePostgresCte;
use Pyz\Zed\PriceProductStorage\Business\Storage\Cte\PriceProductConcrete\PriceProductConcreteStorageMariaDbCte;
use Pyz\Zed\PriceProductStorage\Business\Storage\Cte\PriceProductConcrete\PriceProductConcreteStoragePostgresCte;
use Pyz\Zed\PriceProductStorage\Business\Storage\Cte\PriceProductStorageCteInterface;
use Pyz\Zed\PriceProductStorage\Business\Storage\Cte\PriceProductStorageCteStrategyResolver;
use Pyz\Zed\PriceProductStorage\Business\Storage\Cte\PriceProductStorageCteStrategyResolverInterface;
use Pyz\Zed\PriceProductStorage\Business\Storage\PriceProductAbstractStorageWriter;
use Pyz\Zed\PriceProductStorage\Business\Storage\PriceProductConcreteStorageWriter;
use Pyz\Zed\PriceProductStorage\PriceProductStorageDependencyProvider;
use Pyz\Zed\Propel\Business\PropelFacadeInterface;
use Spryker\Client\Queue\QueueClientInterface;
use Spryker\Service\Synchronization\SynchronizationServiceInterface;
use Spryker\Zed\PriceProductStorage\Business\PriceProductStorageBusinessFactory as SprykerPriceProductStorageBusinessFactory;
use Spryker\Zed\PriceProductStorage\Business\Storage\PriceProductAbstractStorageWriterInterface;
use Spryker\Zed\PriceProductStorage\Business\Storage\PriceProductConcreteStorageWriterInterface;

/**
 * @method \Pyz\Zed\PriceProductStorage\PriceProductStorageConfig getConfig()
 */
class PriceProductStorageBusinessFactory extends SprykerPriceProductStorageBusinessFactory
{
    /**
     * @return \Spryker\Zed\PriceProductStorage\Business\Storage\PriceProductAbstractStorageWriterInterface
     */
    public function createPriceProductAbstractStorageWriter(): PriceProductAbstractStorageWriterInterface
    {
        if (!$this->getConfig()->isCteEnabled()) {
            return parent::createPriceProductAbstractStorageWriter();
        }

        return new PriceProductAbstractStorageWriter(
            $this->getPriceProductFacade(),
            $this->getStoreFacade(),
            $this->getQueryContainer(),
            $this->getConfig()->isSendingToQueue(),
            $this->getSynchronizationService(),
            $this->getQueueClient(),
            $this->createPriceProductAbstractStorageCteStrategyResolver()->resolve()
        );
    }

    /**
     * @return \Spryker\Zed\PriceProductStorage\Business\Storage\PriceProductConcreteStorageWriterInterface
     */
    public function createPriceProductConcreteStorageWriter(): PriceProductConcreteStorageWriterInterface
    {
        if (!$this->getConfig()->isCteEnabled()) {
            return parent::createPriceProductConcreteStorageWriter();
        }

        return new PriceProductConcreteStorageWriter(
            $this->getPriceProductFacade(),
            $this->getStoreFacade(),
            $this->getQueryContainer(),
            $this->getConfig()->isSendingToQueue(),
            $this->getSynchronizationService(),
            $this->getQueueClient(),
            $this->createPriceProductConcreteStorageCteStrategyResolver()->resolve()
        );
    }

    /**
     * @return \Pyz\Zed\PriceProductStorage\Business\Storage\Cte\PriceProductStorageCteStrategyResolverInterface
     */
    public function createPriceProductAbstractStorageCteStrategyResolver(): PriceProductStorageCteStrategyResolverInterface
    {
        return new PriceProductStorageCteStrategyResolver(
            $this->createPriceProductAbstractStorageCteCollection(),
            $this->getPropelFacade()
        );
    }

    /**
     * @return \Pyz\Zed\PriceProductStorage\Business\Storage\Cte\PriceProductStorageCteStrategyResolverInterface
     */
    public function createPriceProductConcreteStorageCteStrategyResolver(): PriceProductStorageCteStrategyResolverInterface
    {
        return new PriceProductStorageCteStrategyResolver(
            $this->createPriceProductConcreteStorageCteCollection(),
            $this->getPropelFacade()
        );
    }

    /**
     * @return array<\Pyz\Zed\PriceProductStorage\Business\Storage\Cte\PriceProductStorageCteInterface>
     */
    public function createPriceProductAbstractStorageCteCollection(): array
    {
        return [
            $this->createPriceProductAbstractStoragePostgresCte(),
            $this->createPriceProductAbstractStorageMariaDbCte(),
        ];
    }

    /**
     * @return \Pyz\Zed\PriceProductStorage\Business\Storage\Cte\PriceProductStorageCteInterface
     */
    public function createPriceProductAbstractStoragePostgresCte(): PriceProductStorageCteInterface
    {
        return new PriceProductAbstractStoragePostgresCte();
    }

    /**
     * @return \Pyz\Zed\PriceProductStorage\Business\Storage\Cte\PriceProductStorageCteInterface
     */
    public function createPriceProductAbstractStorageMariaDbCte(): PriceProductStorageCteInterface
    {
        return new PriceProductAbstractStorageMariaDbCte();
    }

    /**
     * @return array<\Pyz\Zed\PriceProductStorage\Business\Storage\Cte\PriceProductStorageCteInterface>
     */
    public function createPriceProductConcreteStorageCteCollection(): array
    {
        return [
            $this->createPriceProductConcreteStoragePostgresCte(),
            $this->createPriceProductConcreteStorageMariaDbCte(),
        ];
    }

    /**
     * @return \Pyz\Zed\PriceProductStorage\Business\Storage\Cte\PriceProductStorageCteInterface
     */
    public function createPriceProductConcreteStoragePostgresCte(): PriceProductStorageCteInterface
    {
        return new PriceProductConcreteStoragePostgresCte();
    }

    /**
     * @return \Pyz\Zed\PriceProductStorage\Business\Storage\Cte\PriceProductStorageCteInterface
     */
    public function createPriceProductConcreteStorageMariaDbCte(): PriceProductStorageCteInterface
    {
        return new PriceProductConcreteStorageMariaDbCte();
    }

    /**
     * @return \Pyz\Zed\Propel\Business\PropelFacadeInterface
     */
    public function getPropelFacade(): PropelFacadeInterface
    {
        return $this->getProvidedDependency(PriceProductStorageDependencyProvider::FACADE_PROPEL);
    }

    /**
     * @return \Spryker\Service\Synchronization\SynchronizationServiceInterface
     */
    public function getSynchronizationService(): SynchronizationServiceInterface
    {
        return $this->getProvidedDependency(PriceProductStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }

    /**
     * @return \Spryker\Client\Queue\QueueClientInterface
     */
    public function getQueueClient(): QueueClientInterface
    {
        return $this->getProvidedDependency(PriceProductStorageDependencyProvider::CLIENT_QUEUE);
    }
}
