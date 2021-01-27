<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductPageSearch\Business;

use Pyz\Zed\ProductPageSearch\Business\Publisher\ProductAbstractPagePublisher;
use Pyz\Zed\ProductPageSearch\Business\Publisher\ProductConcretePageSearchPublisher;
use Pyz\Zed\ProductPageSearch\Business\Publisher\Sql\ProductAbstractPageSearchMariaDbPagePublisherCte;
use Pyz\Zed\ProductPageSearch\Business\Publisher\Sql\ProductAbstractPageSearchPostgresPagePublisherCte;
use Pyz\Zed\ProductPageSearch\Business\Publisher\Sql\ProductConcretePageSearchMariaDbPagePublisherCte;
use Pyz\Zed\ProductPageSearch\Business\Publisher\Sql\ProductConcretePageSearchPostgresPagePublisherCte;
use Pyz\Zed\ProductPageSearch\Business\Publisher\Sql\ProductPagePublisherCteInterface;
use Pyz\Zed\ProductPageSearch\Business\Publisher\Sql\ProductPageSearchCteStrategyResolver;
use Pyz\Zed\ProductPageSearch\Business\Publisher\Sql\SqlResolverInterface;
use Pyz\Zed\ProductPageSearch\ProductPageSearchDependencyProvider;
use Pyz\Zed\Propel\Business\PropelFacadeInterface;
use Spryker\Client\Queue\QueueClientInterface;
use Spryker\Service\Synchronization\SynchronizationServiceInterface;
use Spryker\Zed\ProductPageSearch\Business\ProductPageSearchBusinessFactory as SprykerProductPageSearchBusinessFactory;
use Spryker\Zed\ProductPageSearch\Business\Publisher\ProductAbstractPagePublisherInterface;
use Spryker\Zed\ProductPageSearch\Business\Publisher\ProductConcretePageSearchPublisherInterface;

/**
 * @method \Pyz\Zed\ProductPageSearch\ProductPageSearchConfig getConfig()
 */
class ProductPageSearchBusinessFactory extends SprykerProductPageSearchBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductPageSearch\Business\Publisher\ProductAbstractPagePublisherInterface
     */
    public function createProductAbstractPagePublisher(): ProductAbstractPagePublisherInterface
    {
        if (!$this->getConfig()->isCteEnabled()) {
            return parent::createProductAbstractPagePublisher();
        }

        return new ProductAbstractPagePublisher(
            $this->getQueryContainer(),
            $this->getProductPageDataExpanderPlugins(),
            $this->getProductPageDataLoaderPlugins(),
            $this->createProductPageMapper(),
            $this->createProductPageWriter(),
            $this->getConfig(),
            $this->getStoreFacade(),
            $this->createAddToCartSkuReader(),
            $this->getSynchronizationService(),
            $this->getQueueClient(),
            $this->createProductAbstractPageCteStrategyResolver()->resolve()
        );
    }

    /**
     * @return \Spryker\Zed\ProductPageSearch\Business\Publisher\ProductConcretePageSearchPublisherInterface
     */
    public function createProductConcretePageSearchPublisher(): ProductConcretePageSearchPublisherInterface
    {
        if (!$this->getConfig()->isCteEnabled()) {
            return parent::createProductConcretePageSearchPublisher();
        }

        return new ProductConcretePageSearchPublisher(
            $this->createProductConcretePageSearchReader(),
            $this->createProductConcretePageSearchWriter(),
            $this->getProductFacade(),
            $this->getUtilEncoding(),
            $this->createProductConcreteSearchDataMapper(),
            $this->getStoreFacade(),
            $this->getConfig(),
            $this->getProductConcretePageDataExpanderPlugins(),
            $this->getSynchronizationService(),
            $this->getQueueClient(),
            $this->createProductConcretePageCteStrategyResolver()->resolve()
        );
    }

    /**
     * @return \Pyz\Zed\ProductPageSearch\Business\Publisher\Sql\SqlResolverInterface
     */
    public function createProductAbstractPageCteStrategyResolver(): SqlResolverInterface
    {
        return new ProductPageSearchCteStrategyResolver($this->getProductAbstractPageCteCollection(), $this->getPropelFacade());
    }

    /**
     * @return \Pyz\Zed\ProductPageSearch\Business\Publisher\Sql\SqlResolverInterface
     */
    public function createProductConcretePageCteStrategyResolver(): SqlResolverInterface
    {
        return new ProductPageSearchCteStrategyResolver($this->getProductConcretePageCteCollection(), $this->getPropelFacade());
    }

    /**
     * @return \Pyz\Zed\ProductPageSearch\Business\Publisher\Sql\ProductPagePublisherCteInterface[]
     */
    public function getProductAbstractPageCteCollection(): array
    {
        return [
            $this->createProductAbstractPageSearchPostgresPublisherCte(),
            $this->createProductAbstractPageSearchMariaDbPublisherCte(),
        ];
    }

    /**
     * @return \Pyz\Zed\ProductPageSearch\Business\Publisher\Sql\ProductPagePublisherCteInterface[]
     */
    public function getProductConcretePageCteCollection(): array
    {
        return [
            $this->createProductConcretePageSearchPostgresPublisherCte(),
            $this->createProductConcretePageSearchMariaDbPublisherCte(),
        ];
    }

    /**
     * @return \Pyz\Zed\ProductPageSearch\Business\Publisher\Sql\ProductPagePublisherCteInterface
     */
    public function createProductAbstractPageSearchPostgresPublisherCte(): ProductPagePublisherCteInterface
    {
        return new ProductAbstractPageSearchPostgresPagePublisherCte();
    }

    /**
     * @return \Pyz\Zed\ProductPageSearch\Business\Publisher\Sql\ProductPagePublisherCteInterface
     */
    public function createProductAbstractPageSearchMariaDbPublisherCte(): ProductPagePublisherCteInterface
    {
        return new ProductAbstractPageSearchMariaDbPagePublisherCte();
    }

    /**
     * @return \Pyz\Zed\ProductPageSearch\Business\Publisher\Sql\ProductPagePublisherCteInterface
     */
    public function createProductConcretePageSearchPostgresPublisherCte(): ProductPagePublisherCteInterface
    {
        return new ProductConcretePageSearchPostgresPagePublisherCte();
    }

    /**
     * @return \Pyz\Zed\ProductPageSearch\Business\Publisher\Sql\ProductPagePublisherCteInterface
     */
    public function createProductConcretePageSearchMariaDbPublisherCte(): ProductPagePublisherCteInterface
    {
        return new ProductConcretePageSearchMariaDbPagePublisherCte();
    }

    /**
     * @return \Spryker\Service\Synchronization\SynchronizationServiceInterface
     */
    public function getSynchronizationService(): SynchronizationServiceInterface
    {
        return $this->getProvidedDependency(ProductPageSearchDependencyProvider::SERVICE_SYNCHRONIZATION);
    }

    /**
     * @return \Spryker\Client\Queue\QueueClientInterface
     */
    public function getQueueClient(): QueueClientInterface
    {
        return $this->getProvidedDependency(ProductPageSearchDependencyProvider::CLIENT_QUEUE);
    }

    /**
     * @return \Pyz\Zed\Propel\Business\PropelFacadeInterface
     */
    public function getPropelFacade(): PropelFacadeInterface
    {
        return $this->getProvidedDependency(ProductPageSearchDependencyProvider::FACADE_PROPEL);
    }
}
