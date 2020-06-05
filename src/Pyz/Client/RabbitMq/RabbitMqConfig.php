<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\RabbitMq;

use Spryker\Client\RabbitMq\RabbitMqConfig as SprykerRabbitMqConfig;
use Spryker\Shared\AvailabilityStorage\AvailabilityStorageConfig;
use Spryker\Shared\AvailabilityStorage\AvailabilityStorageConstants;
use Spryker\Shared\CategoryPageSearch\CategoryPageSearchConstants;
use Spryker\Shared\CategoryStorage\CategoryStorageConstants;
use Spryker\Shared\CmsPageSearch\CmsPageSearchConstants;
use Spryker\Shared\CmsStorage\CmsStorageConstants;
use Spryker\Shared\CompanyUserStorage\CompanyUserStorageConfig;
use Spryker\Shared\ConfigurableBundlePageSearch\ConfigurableBundlePageSearchConfig;
use Spryker\Shared\ConfigurableBundleStorage\ConfigurableBundleStorageConfig;
use Spryker\Shared\ContentStorage\ContentStorageConfig;
use Spryker\Shared\CustomerAccessStorage\CustomerAccessStorageConstants;
use Spryker\Shared\Event\EventConfig;
use Spryker\Shared\Event\EventConstants;
use Spryker\Shared\FileManagerStorage\FileManagerStorageConstants;
use Spryker\Shared\GlossaryStorage\GlossaryStorageConfig;
use Spryker\Shared\Log\LogConstants;
use Spryker\Shared\MerchantOpeningHoursStorage\MerchantOpeningHoursStorageConfig;
use Spryker\Shared\MerchantProductOfferStorage\MerchantProductOfferStorageConfig;
use Spryker\Shared\MerchantStorage\MerchantStorageConfig;
use Spryker\Shared\PriceProductOfferStorage\PriceProductOfferStorageConfig;
use Spryker\Shared\PriceProductStorage\PriceProductStorageConfig;
use Spryker\Shared\PriceProductStorage\PriceProductStorageConstants;
use Spryker\Shared\ProductImageStorage\ProductImageStorageConfig;
use Spryker\Shared\ProductOfferAvailabilityStorage\ProductOfferAvailabilityStorageConfig;
use Spryker\Shared\ProductPackagingUnitStorage\ProductPackagingUnitStorageConfig;
use Spryker\Shared\ProductPageSearch\ProductPageSearchConfig;
use Spryker\Shared\ProductPageSearch\ProductPageSearchConstants;
use Spryker\Shared\ProductStorage\ProductStorageConfig;
use Spryker\Shared\ProductStorage\ProductStorageConstants;
use Spryker\Shared\SalesReturnSearch\SalesReturnSearchConfig;
use Spryker\Shared\Publisher\PublisherConfig;
use Spryker\Shared\ShoppingListStorage\ShoppingListStorageConfig;
use Spryker\Shared\TaxProductStorage\TaxProductStorageConfig;
use Spryker\Shared\TaxStorage\TaxStorageConfig;
use Spryker\Shared\UrlStorage\UrlStorageConfig;
use Spryker\Shared\UrlStorage\UrlStorageConstants;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class RabbitMqConfig extends SprykerRabbitMqConfig
{
    /**
     *  QueueNameFoo, // Queue => QueueNameFoo, (Queue and error queue will be created: QueueNameFoo and QueueNameFoo.error)
     *  QueueNameBar => [
     *       RoutingKeyFoo => QueueNameBaz, // (Additional queues can be defined by several routing keys)
     *   ],
     *
     * @see https://www.rabbitmq.com/tutorials/amqp-concepts.html
     *
     * @return array
     */
    protected function getQueueConfiguration(): array
    {
        return array_merge(
            [
                EventConstants::EVENT_QUEUE => [
                    EventConfig::EVENT_ROUTING_KEY_RETRY => EventConstants::EVENT_QUEUE_RETRY,
                    EventConfig::EVENT_ROUTING_KEY_ERROR => EventConstants::EVENT_QUEUE_ERROR,
                ],
                $this->get(LogConstants::LOG_QUEUE_NAME),
            ],
            $this->getPublishQueueConfiguration(),
            $this->getSynchronizationQueueConfiguration()
        );
    }

    /**
     * @return array
     */
    protected function getPublishQueueConfiguration(): array
    {
        return [
            PublisherConfig::PUBLISH_QUEUE => [
                PublisherConfig::PUBLISH_ROUTING_KEY_RETRY => PublisherConfig::PUBLISH_RETRY_QUEUE,
                PublisherConfig::PUBLISH_ROUTING_KEY_ERROR => PublisherConfig::PUBLISH_ERROR_QUEUE,
            ],
            GlossaryStorageConfig::PUBLISH_TRANSLATION => [
                PublisherConfig::PUBLISH_ROUTING_KEY_RETRY => GlossaryStorageConfig::PUBLISH_TRANSLATION_RETRY_QUEUE,
                PublisherConfig::PUBLISH_ROUTING_KEY_ERROR => GlossaryStorageConfig::PUBLISH_TRANSLATION_ERROR_QUEUE,
            ],
            UrlStorageConfig::PUBLISH_URL => [
                PublisherConfig::PUBLISH_ROUTING_KEY_RETRY => UrlStorageConfig::PUBLISH_URL_RETRY_QUEUE,
                PublisherConfig::PUBLISH_ROUTING_KEY_ERROR => UrlStorageConfig::PUBLISH_URL_ERROR_QUEUE,
            ],
            AvailabilityStorageConfig::PUBLISH_AVAILABILITY => [
                PublisherConfig::PUBLISH_ROUTING_KEY_RETRY => AvailabilityStorageConfig::PUBLISH_AVAILABILITY_RETRY_QUEUE,
                PublisherConfig::PUBLISH_ROUTING_KEY_ERROR => AvailabilityStorageConfig::PUBLISH_AVAILABILITY_ERROR_QUEUE,
            ],
            PriceProductStorageConfig::PUBLISH_PRICE_PRODUCT_ABSTRACT => [
                PublisherConfig::PUBLISH_ROUTING_KEY_RETRY => PriceProductStorageConfig::PUBLISH_PRICE_PRODUCT_ABSTRACT_RETRY_QUEUE,
                PublisherConfig::PUBLISH_ROUTING_KEY_ERROR => PriceProductStorageConfig::PUBLISH_PRICE_PRODUCT_ABSTRACT_ERROR_QUEUE,
            ],
            PriceProductStorageConfig::PUBLISH_PRICE_PRODUCT_CONCRETE => [
                PublisherConfig::PUBLISH_ROUTING_KEY_RETRY => PriceProductStorageConfig::PUBLISH_PRICE_PRODUCT_CONCRETE_RETRY_QUEUE,
                PublisherConfig::PUBLISH_ROUTING_KEY_ERROR => PriceProductStorageConfig::PUBLISH_PRICE_PRODUCT_CONCRETE_ERROR_QUEUE,
            ],
            ProductImageStorageConfig::PUBLISH_PRODUCT_ABSTRACT_IMAGE => [
                PublisherConfig::PUBLISH_ROUTING_KEY_RETRY => ProductImageStorageConfig::PUBLISH_PRODUCT_ABSTRACT_IMAGE_RETRY_QUEUE,
                PublisherConfig::PUBLISH_ROUTING_KEY_ERROR => ProductImageStorageConfig::PUBLISH_PRODUCT_ABSTRACT_IMAGE_ERROR_QUEUE,
            ],
            ProductImageStorageConfig::PUBLISH_PRODUCT_CONCRETE_IMAGE => [
                PublisherConfig::PUBLISH_ROUTING_KEY_RETRY => ProductImageStorageConfig::PUBLISH_PRODUCT_CONCRETE_IMAGE_RETRY_QUEUE,
                PublisherConfig::PUBLISH_ROUTING_KEY_ERROR => ProductImageStorageConfig::PUBLISH_PRODUCT_CONCRETE_IMAGE_RETRY_QUEUE,
            ],
            ProductPageSearchConfig::PUBLISH_PRODUCT_ABSTRACT_PAGE => [
                PublisherConfig::PUBLISH_ROUTING_KEY_RETRY => ProductPageSearchConfig::PUBLISH_PRODUCT_ABSTRACT_PAGE_RETRY_QUEUE,
                PublisherConfig::PUBLISH_ROUTING_KEY_ERROR => ProductPageSearchConfig::PUBLISH_PRODUCT_ABSTRACT_PAGE_ERROR_QUEUE,
            ],
            ProductPageSearchConfig::PUBLISH_PRODUCT_CONCRETE_PAGE => [
                PublisherConfig::PUBLISH_ROUTING_KEY_RETRY => ProductPageSearchConfig::PUBLISH_PRODUCT_CONCRETE_PAGE_RETRY_QUEUE,
                PublisherConfig::PUBLISH_ROUTING_KEY_ERROR => ProductPageSearchConfig::PUBLISH_PRODUCT_CONCRETE_PAGE_ERROR_QUEUE,
            ],
            ProductStorageConfig::PUBLISH_PRODUCT_ABSTRACT => [
                PublisherConfig::PUBLISH_ROUTING_KEY_RETRY => ProductStorageConfig::PUBLISH_PRODUCT_ABSTRACT_RETRY_QUEUE,
                PublisherConfig::PUBLISH_ROUTING_KEY_ERROR => ProductStorageConfig::PUBLISH_PRODUCT_ABSTRACT_ERROR_QUEUE,
            ],
            ProductStorageConfig::PUBLISH_PRODUCT_CONCRETE => [
                PublisherConfig::PUBLISH_ROUTING_KEY_RETRY => ProductStorageConfig::PUBLISH_PRODUCT_CONCRETE_RETRY_QUEUE,
                PublisherConfig::PUBLISH_ROUTING_KEY_ERROR => ProductStorageConfig::PUBLISH_PRODUCT_CONCRETE_RETRY_QUEUE,
            ],
        ];
    }

    /**
     * @return array
     */
    protected function getSynchronizationQueueConfiguration(): array
    {
        return [
            GlossaryStorageConfig::SYNC_STORAGE_TRANSLATION,
            UrlStorageConstants::URL_SYNC_STORAGE_QUEUE,
            AvailabilityStorageConstants::AVAILABILITY_SYNC_STORAGE_QUEUE,
            CustomerAccessStorageConstants::CUSTOMER_ACCESS_SYNC_STORAGE_QUEUE,
            CategoryStorageConstants::CATEGORY_SYNC_STORAGE_QUEUE,
            ProductStorageConstants::PRODUCT_SYNC_STORAGE_QUEUE,
            PriceProductStorageConstants::PRICE_SYNC_STORAGE_QUEUE,
            ProductPackagingUnitStorageConfig::PRODUCT_PACKAGING_UNIT_SYNC_STORAGE_QUEUE,
            ConfigurableBundleStorageConfig::CONFIGURABLE_BUNDLE_SYNC_STORAGE_QUEUE,
            ConfigurableBundlePageSearchConfig::CONFIGURABLE_BUNDLE_SEARCH_QUEUE,
            CmsStorageConstants::CMS_SYNC_STORAGE_QUEUE,
            CategoryPageSearchConstants::CATEGORY_SYNC_SEARCH_QUEUE,
            CmsPageSearchConstants::CMS_SYNC_SEARCH_QUEUE,
            ProductPageSearchConstants::PRODUCT_SYNC_SEARCH_QUEUE,
            FileManagerStorageConstants::FILE_SYNC_STORAGE_QUEUE,
            ShoppingListStorageConfig::SHOPPING_LIST_SYNC_STORAGE_QUEUE,
            CompanyUserStorageConfig::COMPANY_USER_SYNC_STORAGE_QUEUE,
            ContentStorageConfig::CONTENT_SYNC_STORAGE_QUEUE,
            TaxProductStorageConfig::PRODUCT_ABSTRACT_TAX_SET_SYNC_STORAGE_QUEUE,
            TaxStorageConfig::TAX_SET_SYNC_STORAGE_QUEUE,
            MerchantStorageConfig::MERCHANT_SYNC_STORAGE_QUEUE,
            MerchantOpeningHoursStorageConfig::MERCHANT_OPENING_HOURS_SYNC_STORAGE_QUEUE,
            MerchantProductOfferStorageConfig::MERCHANT_PRODUCT_OFFER_SYNC_STORAGE_QUEUE,
            PriceProductOfferStorageConfig::PRICE_PRODUCT_OFFER_OFFER_SYNC_STORAGE_QUEUE,
            ProductOfferAvailabilityStorageConfig::PRODUCT_OFFER_AVAILABILITY_SYNC_STORAGE_QUEUE,
            SalesReturnSearchConfig::SYNC_SEARCH_RETURN,
            $this->get(LogConstants::LOG_QUEUE_NAME),
        ];
    }

    /**
     * @return string
     */
    protected function getDefaultBoundQueueNamePrefix(): string
    {
        return 'error';
    }
}
