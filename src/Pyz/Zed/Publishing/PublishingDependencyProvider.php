<?php

namespace Pyz\Zed\Publishing;

use Spryker\Zed\AvailabilityStorage\Communication\Plugin\Publishing\AvailabilityStoragePublishingRegistry;
use Spryker\Zed\GlossaryStorage\Communication\Plugin\Publishing\GlossaryStoragePublishingRegistry;

class PublishingDependencyProvider extends \Spryker\Zed\Publishing\PublishingDependencyProvider
{

    /**
     * @return mixed
     */
    public function getPublishingRegistryCollection()
    {
        $publishingRegistryCollection = parent::getPublishingRegistryCollection();

        $publishingRegistryCollection->add( new GlossaryStoragePublishingRegistry());
        $publishingRegistryCollection->add( new AvailabilityStoragePublishingRegistry());
//        $publishingRegistryCollection->add( new ProductStoragePublishingRegistry());
//        $publishingRegistryCollection->add( new ProductPriceStoragePublishingRegistry());
//        $publishingRegistryCollection->add( new CmsPublishingRegistry());
//        ....

        return $publishingRegistryCollection;
    }

}
