<?php

namespace Pyz\Zed\Publishing;

use Spryker\Zed\AvailabilityStorage\Communication\Plugin\Publishing\AvailabilityStoragePublisherRegistry;
use Spryker\Zed\GlossaryStorage\Communication\Plugin\Publishing\GlossaryStoragePublisherRegistry;

class PublishingDependencyProvider extends \Spryker\Zed\Publishing\PublishingDependencyProvider
{

    /**
     * @return mixed
     */
    public function getPublisherRegistryCollection()
    {
        $publishingRegistryCollection = parent::getPublisherRegistryCollection();

        $publishingRegistryCollection->add( new GlossaryStoragePublisherRegistry());
        $publishingRegistryCollection->add( new AvailabilityStoragePublisherRegistry());
//        $publishingRegistryCollection->add( new ProductStoragePublishingRegistry());
//        $publishingRegistryCollection->add( new ProductPriceStoragePublishingRegistry());
//        $publishingRegistryCollection->add( new CmsPublishingRegistry());
//        ....

        return $publishingRegistryCollection;
    }

}
