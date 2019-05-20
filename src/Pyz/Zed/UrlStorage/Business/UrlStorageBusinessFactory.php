<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\UrlStorage\Business;

use Pyz\Zed\UrlStorage\Business\Storage\UrlStorageWriter;
use Pyz\Zed\UrlStorage\UrlStorageDependencyProvider;
use Spryker\Zed\UrlStorage\Business\UrlStorageBusinessFactory as SprykerUrlStorageBusinessFactory;

/**
 * @method \Pyz\Zed\UrlStorage\UrlStorageConfig getConfig()
 */
class UrlStorageBusinessFactory extends SprykerUrlStorageBusinessFactory
{
    /**
     * @return \Spryker\Zed\UrlStorage\Business\Storage\UrlStorageWriterInterface
     */
    public function createUrlStorageWriter()
    {
        return new UrlStorageWriter(
            $this->getUtilSanitizeService(),
            $this->getQueryContainer(),
            $this->getConfig()->isSendingToQueue(),
            $this->getStoreFacade(),
            $this->getSynchronizationService(),
            $this->getQueueClient()
        );
    }

    /**
     * @return \Spryker\Client\Queue\QueueClientInterface
     */
    protected function getQueueClient()
    {
        return $this->getProvidedDependency(UrlStorageDependencyProvider::CLIENT_QUEUE);
    }

    /**
     * @return \Spryker\Service\Synchronization\SynchronizationServiceInterface
     */
    public function getSynchronizationService()
    {
        return $this->getProvidedDependency(UrlStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }

    /**
     * @return \Pyz\Zed\Publisher\Business\PublisherFacadeInterface
     */
    public function getPublisherFacade()
    {
        return $this->getProvidedDependency(UrlStorageDependencyProvider::FACADE_PUBLISHER);
    }
}
