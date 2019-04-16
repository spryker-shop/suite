<?php

namespace Pyz\Zed\Publisher;

use Spryker\Zed\GlossaryStorage\Communication\Plugin\Publisher\GlossaryStoragePublisherRegistryPlugin;

class PublisherDependencyProvider extends \Spryker\Zed\Publisher\PublisherDependencyProvider
{

    /**
     * @return mixed
     */
    public function getPublisherRegistryCollection()
    {
        $publishingRegistryCollection = parent::getPublisherRegistryCollection();

        $publishingRegistryCollection->add( new GlossaryStoragePublisherRegistryPlugin());
//        $publishingRegistryCollection->add( new ProductStoragePublishingRegistry());
//        $publishingRegistryCollection->add( new ProductPriceStoragePublishingRegistry());
//        $publishingRegistryCollection->add( new CmsPublishingRegistry());
//        ....

        return $publishingRegistryCollection;
    }

}
