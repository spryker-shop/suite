<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Publisher;

use Spryker\Zed\GlossaryStorage\Communication\Plugin\Publisher\GlossaryStoragePublisherRegistryPlugin;
use Spryker\Zed\Publisher\Communication\Collection\PublisherRegistryCollectionInterface;
use Spryker\Zed\Publisher\PublisherDependencyProvider as SprykerPublisherDependencyProvider;

class PublisherDependencyProvider extends SprykerPublisherDependencyProvider
{
    /**
     * @return \Spryker\Zed\Publisher\Communication\Collection\PublisherRegistryCollectionInterface
     */
    public function getPublisherRegistryCollection(): PublisherRegistryCollectionInterface
    {
        $publishingRegistryCollection = parent::getPublisherRegistryCollection();

        $publishingRegistryCollection->add(new GlossaryStoragePublisherRegistryPlugin());
//        $publishingRegistryCollection->add( new ProductStoragePublishingRegistry());
//        $publishingRegistryCollection->add( new ProductPriceStoragePublishingRegistry());
//        $publishingRegistryCollection->add( new CmsPublishingRegistry());
//        ....

        return $publishingRegistryCollection;
    }
}
