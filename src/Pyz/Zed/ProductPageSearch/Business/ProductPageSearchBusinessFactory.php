<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductPageSearch\Business;

use Pyz\Zed\ProductPageSearch\Business\Publisher\ProductAbstractPagePublisher;
use Pyz\Zed\ProductPageSearch\Business\Publisher\ProductConcretePageSearchPublisher;
use Pyz\Zed\ProductPageSearch\ProductPageSearchDependencyProvider;
use Spryker\Zed\ProductPageSearch\Business\ProductPageSearchBusinessFactory as SprykerProductPageSearchBusinessFactory;
use Spryker\Zed\ProductPageSearch\Business\Publisher\ProductConcretePageSearchPublisherInterface;

/**
 * @method \Spryker\Zed\ProductPageSearch\ProductPageSearchConfig getConfig()
 * @method \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchEntityManagerInterface getEntityManager()
 */
class ProductPageSearchBusinessFactory extends SprykerProductPageSearchBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductPageSearch\Business\Publisher\ProductAbstractPagePublisherInterface
     */
    public function createProductAbstractPagePublisher()
    {
        return new ProductAbstractPagePublisher(
            $this->getQueryContainer(),
            $this->getProductPageDataExpanderPlugins(),
            $this->getProductPageDataLoaderPlugins(),
            $this->createProductPageMapper(),
            $this->createProductPageWriter(),
            $this->getSynchronizationService(),
            $this->getQueueClient()
        );
    }

    /**
     * @return \Spryker\Zed\ProductPageSearch\Business\Publisher\ProductConcretePageSearchPublisherInterface
     */
    public function createProductConcretePageSearchPublisher(): ProductConcretePageSearchPublisherInterface
    {
        return new ProductConcretePageSearchPublisher(
            $this->createProductConcretePageSearchReader(),
            $this->createProductConcretePageSearchWriter(),
            $this->getProductFacade(),
            $this->getUtilEncoding(),
            $this->getSearchFacade(),
            $this->getProductConcretePageDataExpanderPlugins(),
            $this->getSynchronizationService(),
            $this->getQueueClient()
        );
    }

    /**
     * @return \Spryker\Client\Queue\QueueClientInterface
     */
    protected function getQueueClient()
    {
        return $this->getProvidedDependency(ProductPageSearchDependencyProvider::CLIENT_QUEUE);
    }

    /**
     * @return \Spryker\Service\Synchronization\SynchronizationServiceInterface
     */
    public function getSynchronizationService()
    {
        return $this->getProvidedDependency(ProductPageSearchDependencyProvider::SERVICE_SYNCHRONIZATION);
    }
}
