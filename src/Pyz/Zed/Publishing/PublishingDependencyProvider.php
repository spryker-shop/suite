<?php

namespace Pyz\Zed\Publishing;

use Spryker\Zed\GlossaryStorage\Communication\Plugin\Event\Subscriber\GlossaryStoragePublisherRegistry;

class PublishingDependencyProvider extends \Spryker\Zed\Publishing\PublishingDependencyProvider
{

    /**
     * @return \Spryker\Zed\Publisher\Dependency\PublisherRegistryCollection
     */
    public function getPublisherRegistryCollection()
    {
        $collection = parent::getPublisherRegistryCollection();

        $collection->add(new GlossaryStoragePublisherRegistry());

        return $collection;
    }

}
