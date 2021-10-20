<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\UrlStorage\Business;

use Pyz\Zed\Propel\Business\PropelFacadeInterface;
use Pyz\Zed\UrlStorage\Business\Storage\Cte\UrlStorageCteInterface;
use Pyz\Zed\UrlStorage\Business\Storage\Cte\UrlStorageCteStrategyResolver;
use Pyz\Zed\UrlStorage\Business\Storage\Cte\UrlStorageCteStrategyResolverInterface;
use Pyz\Zed\UrlStorage\Business\Storage\Cte\UrlStorageMariaDbCte;
use Pyz\Zed\UrlStorage\Business\Storage\Cte\UrlStoragePostgresCte;
use Pyz\Zed\UrlStorage\Business\Storage\UrlStorageWriter;
use Pyz\Zed\UrlStorage\UrlStorageDependencyProvider;
use Spryker\Client\Queue\QueueClientInterface;
use Spryker\Service\Synchronization\SynchronizationServiceInterface;
use Spryker\Zed\UrlStorage\Business\Storage\UrlStorageWriterInterface;
use Spryker\Zed\UrlStorage\Business\UrlStorageBusinessFactory as SprykerUrlStorageBusinessFactory;

/**
 * @method \Pyz\Zed\UrlStorage\UrlStorageConfig getConfig()
 */
class UrlStorageBusinessFactory extends SprykerUrlStorageBusinessFactory
{
    /**
     * @return \Spryker\Zed\UrlStorage\Business\Storage\UrlStorageWriterInterface
     */
    public function createUrlStorageWriter(): UrlStorageWriterInterface
    {
        if (!$this->getConfig()->isCteEnabled()) {
            return parent::createUrlStorageWriter();
        }

        return new UrlStorageWriter(
            $this->getUtilSanitizeService(),
            $this->getRepository(),
            $this->getEntityManager(),
            $this->getStoreFacade(),
            $this->getConfig()->isSendingToQueue(),
            $this->getSynchronizationService(),
            $this->getQueueClient(),
            $this->createUrlStorageCteStrategyResolver()->resolve(),
        );
    }

    /**
     * @return \Pyz\Zed\UrlStorage\Business\Storage\Cte\UrlStorageCteStrategyResolverInterface
     */
    public function createUrlStorageCteStrategyResolver(): UrlStorageCteStrategyResolverInterface
    {
        return new UrlStorageCteStrategyResolver($this->createUrlStorageCteCollection(), $this->getPropelFacade());
    }

    /**
     * @return array<\Pyz\Zed\UrlStorage\Business\Storage\Cte\UrlStorageCteInterface>
     */
    public function createUrlStorageCteCollection(): array
    {
        return [
            $this->createUrlStoragePostgresCte(),
            $this->createUrlStorageMariaDbCte(),
        ];
    }

    /**
     * @return \Pyz\Zed\UrlStorage\Business\Storage\Cte\UrlStorageCteInterface
     */
    public function createUrlStoragePostgresCte(): UrlStorageCteInterface
    {
        return new UrlStoragePostgresCte();
    }

    /**
     * @return \Pyz\Zed\UrlStorage\Business\Storage\Cte\UrlStorageCteInterface
     */
    public function createUrlStorageMariaDbCte(): UrlStorageCteInterface
    {
        return new UrlStorageMariaDbCte();
    }

    /**
     * @return \Pyz\Zed\Propel\Business\PropelFacadeInterface
     */
    public function getPropelFacade(): PropelFacadeInterface
    {
        return $this->getProvidedDependency(UrlStorageDependencyProvider::FACADE_PROPEL);
    }

    /**
     * @return \Spryker\Service\Synchronization\SynchronizationServiceInterface
     */
    public function getSynchronizationService(): SynchronizationServiceInterface
    {
        return $this->getProvidedDependency(UrlStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }

    /**
     * @return \Spryker\Client\Queue\QueueClientInterface
     */
    public function getQueueClient(): QueueClientInterface
    {
        return $this->getProvidedDependency(UrlStorageDependencyProvider::CLIENT_QUEUE);
    }
}
