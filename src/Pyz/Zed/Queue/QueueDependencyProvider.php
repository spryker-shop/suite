<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Zed\Queue;

use Spryker\Shared\AssetStorage\AssetStorageConfig;
use Spryker\Shared\AvailabilityStorage\AvailabilityStorageConfig;
use Spryker\Shared\AvailabilityStorage\AvailabilityStorageConstants;
use Spryker\Shared\CategoryPageSearch\CategoryPageSearchConstants;
use Spryker\Shared\CategoryStorage\CategoryStorageConstants;
use Spryker\Shared\CmsPageSearch\CmsPageSearchConstants;
use Spryker\Shared\CmsStorage\CmsStorageConstants;
use Spryker\Shared\CompanyUserStorage\CompanyUserStorageConfig;
use Spryker\Shared\Config\Config;
use Spryker\Shared\ConfigurableBundlePageSearch\ConfigurableBundlePageSearchConfig;
use Spryker\Shared\ConfigurableBundleStorage\ConfigurableBundleStorageConfig;
use Spryker\Shared\ContentStorage\ContentStorageConfig;
use Spryker\Shared\CustomerAccessStorage\CustomerAccessStorageConstants;
use Spryker\Shared\CustomerStorage\CustomerStorageConfig;
use Spryker\Shared\Event\EventConstants;
use Spryker\Shared\FileManagerStorage\FileManagerStorageConstants;
use Spryker\Shared\GlossaryStorage\GlossaryStorageConfig;
use Spryker\Shared\Log\LogConstants;
use Spryker\Shared\MerchantOpeningHoursStorage\MerchantOpeningHoursStorageConfig;
use Spryker\Shared\MerchantSearch\MerchantSearchConfig;
use Spryker\Shared\MerchantStorage\MerchantStorageConfig;
use Spryker\Shared\PriceProductOfferStorage\PriceProductOfferStorageConfig;
use Spryker\Shared\PriceProductStorage\PriceProductStorageConfig;
use Spryker\Shared\PriceProductStorage\PriceProductStorageConstants;
use Spryker\Shared\ProductConfigurationStorage\ProductConfigurationStorageConfig;
use Spryker\Shared\ProductImageStorage\ProductImageStorageConfig;
use Spryker\Shared\ProductOfferAvailabilityStorage\ProductOfferAvailabilityStorageConfig;
use Spryker\Shared\ProductOfferServicePointStorage\ProductOfferServicePointStorageConfig;
use Spryker\Shared\ProductOfferShipmentTypeStorage\ProductOfferShipmentTypeStorageConfig;
use Spryker\Shared\ProductOfferStorage\ProductOfferStorageConfig;
use Spryker\Shared\ProductPageSearch\ProductPageSearchConfig;
use Spryker\Shared\ProductPageSearch\ProductPageSearchConstants;
use Spryker\Shared\ProductStorage\ProductStorageConfig;
use Spryker\Shared\ProductStorage\ProductStorageConstants;
use Spryker\Shared\PublishAndSynchronizeHealthCheck\PublishAndSynchronizeHealthCheckConfig;
use Spryker\Shared\PublishAndSynchronizeHealthCheckSearch\PublishAndSynchronizeHealthCheckSearchConfig;
use Spryker\Shared\PublishAndSynchronizeHealthCheckStorage\PublishAndSynchronizeHealthCheckStorageConfig;
use Spryker\Shared\Publisher\PublisherConfig;
use Spryker\Shared\SalesReturnSearch\SalesReturnSearchConfig;
use Spryker\Shared\SearchHttp\SearchHttpConfig;
use Spryker\Shared\ServicePointSearch\ServicePointSearchConfig;
use Spryker\Shared\ServicePointStorage\ServicePointStorageConfig;
use Spryker\Shared\ShipmentTypeStorage\ShipmentTypeStorageConfig;
use Spryker\Shared\ShoppingListStorage\ShoppingListStorageConfig;
use Spryker\Shared\StoreStorage\StoreStorageConfig;
use Spryker\Shared\TaxProductStorage\TaxProductStorageConfig;
use Spryker\Shared\TaxStorage\TaxStorageConfig;
use Spryker\Shared\UrlStorage\UrlStorageConfig;
use Spryker\Shared\UrlStorage\UrlStorageConstants;
use Spryker\Zed\Event\Communication\Plugin\Queue\EventQueueMessageProcessorPlugin;
use Spryker\Zed\Event\Communication\Plugin\Queue\EventRetryQueueMessageProcessorPlugin;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Queue\QueueDependencyProvider as SprykerDependencyProvider;
use Spryker\Zed\RabbitMq\Communication\Plugin\Queue\RabbitMqQueueMessageCheckerPlugin;
use Spryker\Zed\Synchronization\Communication\Plugin\Queue\SynchronizationSearchQueueMessageProcessorPlugin;
use Spryker\Zed\Synchronization\Communication\Plugin\Queue\SynchronizationStorageQueueMessageProcessorPlugin;
use SprykerEco\Zed\Loggly\Communication\Plugin\LogglyLoggerQueueMessageProcessorPlugin;

class QueueDependencyProvider extends SprykerDependencyProvider
{
    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return array<\Spryker\Zed\Queue\Dependency\Plugin\QueueMessageProcessorPluginInterface>
     */
    protected function getProcessorMessagePlugins(Container $container): array // phpcs:ignore SlevomatCodingStandard.Functions.UnusedParameter
    {
        return [
            EventConstants::EVENT_QUEUE => new EventQueueMessageProcessorPlugin(),
            EventConstants::EVENT_QUEUE_RETRY => new EventRetryQueueMessageProcessorPlugin(),
            PublisherConfig::PUBLISH_QUEUE => new EventQueueMessageProcessorPlugin(),
            PublisherConfig::PUBLISH_RETRY_QUEUE => new EventRetryQueueMessageProcessorPlugin(),
            Config::get(LogConstants::LOG_QUEUE_NAME) => new LogglyLoggerQueueMessageProcessorPlugin(),
            GlossaryStorageConfig::PUBLISH_TRANSLATION => new EventQueueMessageProcessorPlugin(),
            PublishAndSynchronizeHealthCheckConfig::PUBLISH_PUBLISH_AND_SYNCHRONIZE_HEALTH_CHECK => new EventQueueMessageProcessorPlugin(),
            PublishAndSynchronizeHealthCheckStorageConfig::SYNC_STORAGE_PUBLISH_AND_SYNCHRONIZE_HEALTH_CHECK => new SynchronizationStorageQueueMessageProcessorPlugin(),
            PublishAndSynchronizeHealthCheckSearchConfig::SYNC_SEARCH_PUBLISH_AND_SYNCHRONIZE_HEALTH_CHECK => new SynchronizationSearchQueueMessageProcessorPlugin(),
            GlossaryStorageConfig::SYNC_STORAGE_TRANSLATION => new SynchronizationStorageQueueMessageProcessorPlugin(),
            CmsStorageConstants::CMS_SYNC_STORAGE_QUEUE => new SynchronizationStorageQueueMessageProcessorPlugin(),
            AvailabilityStorageConfig::PUBLISH_AVAILABILITY => new EventQueueMessageProcessorPlugin(),
            AvailabilityStorageConstants::AVAILABILITY_SYNC_STORAGE_QUEUE => new SynchronizationStorageQueueMessageProcessorPlugin(),
            CustomerAccessStorageConstants::CUSTOMER_ACCESS_SYNC_STORAGE_QUEUE => new SynchronizationStorageQueueMessageProcessorPlugin(),
            CustomerStorageConfig::PUBLISH_CUSTOMER_INVALIDATED => new EventQueueMessageProcessorPlugin(),
            CustomerStorageConfig::CUSTOMER_INVALIDATED_SYNC_STORAGE_QUEUE => new SynchronizationStorageQueueMessageProcessorPlugin(),
            UrlStorageConfig::PUBLISH_URL => new EventQueueMessageProcessorPlugin(),
            UrlStorageConstants::URL_SYNC_STORAGE_QUEUE => new SynchronizationStorageQueueMessageProcessorPlugin(),
            UrlStorageConfig::PUBLISH_URL_RETRY => new EventRetryQueueMessageProcessorPlugin(),
            ProductStorageConfig::PUBLISH_PRODUCT_ABSTRACT => new EventQueueMessageProcessorPlugin(),
            ProductStorageConfig::PUBLISH_PRODUCT_CONCRETE => new EventQueueMessageProcessorPlugin(),
            ProductStorageConstants::PRODUCT_SYNC_STORAGE_QUEUE => new SynchronizationStorageQueueMessageProcessorPlugin(),
            ConfigurableBundleStorageConfig::CONFIGURABLE_BUNDLE_SYNC_STORAGE_QUEUE => new SynchronizationStorageQueueMessageProcessorPlugin(),
            ConfigurableBundlePageSearchConfig::CONFIGURABLE_BUNDLE_SEARCH_QUEUE => new SynchronizationSearchQueueMessageProcessorPlugin(),
            PriceProductStorageConfig::PUBLISH_PRICE_PRODUCT_ABSTRACT => new EventQueueMessageProcessorPlugin(),
            PriceProductStorageConfig::PUBLISH_PRICE_PRODUCT_CONCRETE => new EventQueueMessageProcessorPlugin(),
            ProductImageStorageConfig::PUBLISH_PRODUCT_ABSTRACT_IMAGE => new EventQueueMessageProcessorPlugin(),
            ProductImageStorageConfig::PUBLISH_PRODUCT_CONCRETE_IMAGE => new EventQueueMessageProcessorPlugin(),
            PriceProductStorageConstants::PRICE_SYNC_STORAGE_QUEUE => new SynchronizationStorageQueueMessageProcessorPlugin(),
            CategoryStorageConstants::CATEGORY_SYNC_STORAGE_QUEUE => new SynchronizationStorageQueueMessageProcessorPlugin(),
            CmsPageSearchConstants::CMS_SYNC_SEARCH_QUEUE => new SynchronizationSearchQueueMessageProcessorPlugin(),
            CategoryPageSearchConstants::CATEGORY_SYNC_SEARCH_QUEUE => new SynchronizationSearchQueueMessageProcessorPlugin(),
            ProductPageSearchConfig::PUBLISH_PRODUCT_ABSTRACT_PAGE => new EventQueueMessageProcessorPlugin(),
            ProductPageSearchConfig::PUBLISH_PRODUCT_CONCRETE_PAGE => new EventQueueMessageProcessorPlugin(),
            ProductPageSearchConstants::PRODUCT_SYNC_SEARCH_QUEUE => new SynchronizationSearchQueueMessageProcessorPlugin(),
            FileManagerStorageConstants::FILE_SYNC_STORAGE_QUEUE => new SynchronizationStorageQueueMessageProcessorPlugin(),
            ShoppingListStorageConfig::SHOPPING_LIST_SYNC_STORAGE_QUEUE => new SynchronizationStorageQueueMessageProcessorPlugin(),
            CompanyUserStorageConfig::COMPANY_USER_SYNC_STORAGE_QUEUE => new SynchronizationStorageQueueMessageProcessorPlugin(),
            ContentStorageConfig::CONTENT_SYNC_STORAGE_QUEUE => new SynchronizationStorageQueueMessageProcessorPlugin(),
            TaxProductStorageConfig::PRODUCT_ABSTRACT_TAX_SET_SYNC_STORAGE_QUEUE => new SynchronizationStorageQueueMessageProcessorPlugin(),
            TaxStorageConfig::TAX_SET_SYNC_STORAGE_QUEUE => new SynchronizationStorageQueueMessageProcessorPlugin(),
            MerchantStorageConfig::MERCHANT_SYNC_STORAGE_QUEUE => new SynchronizationStorageQueueMessageProcessorPlugin(),
            ProductOfferStorageConfig::PRODUCT_OFFER_SYNC_STORAGE_QUEUE => new SynchronizationStorageQueueMessageProcessorPlugin(),
            MerchantOpeningHoursStorageConfig::MERCHANT_OPENING_HOURS_SYNC_STORAGE_QUEUE => new SynchronizationStorageQueueMessageProcessorPlugin(),
            PriceProductOfferStorageConfig::PRICE_PRODUCT_OFFER_OFFER_SYNC_STORAGE_QUEUE => new SynchronizationStorageQueueMessageProcessorPlugin(),
            ProductOfferAvailabilityStorageConfig::PRODUCT_OFFER_AVAILABILITY_SYNC_STORAGE_QUEUE => new SynchronizationStorageQueueMessageProcessorPlugin(),
            SalesReturnSearchConfig::SYNC_SEARCH_RETURN => new SynchronizationSearchQueueMessageProcessorPlugin(),
            ProductConfigurationStorageConfig::PRODUCT_CONFIGURATION_SYNC_STORAGE_QUEUE => new SynchronizationStorageQueueMessageProcessorPlugin(),
            MerchantSearchConfig::SYNC_SEARCH_MERCHANT => new SynchronizationSearchQueueMessageProcessorPlugin(),
            StoreStorageConfig::STORE_SYNC_STORAGE_QUEUE => new SynchronizationStorageQueueMessageProcessorPlugin(),
            AssetStorageConfig::ASSET_SYNC_STORAGE_QUEUE => new SynchronizationStorageQueueMessageProcessorPlugin(),
            SearchHttpConfig::SEARCH_HTTP_CONFIG_SYNC_QUEUE => new SynchronizationStorageQueueMessageProcessorPlugin(),
            ServicePointSearchConfig::QUEUE_NAME_SYNC_SEARCH_SERVICE_POINT => new SynchronizationSearchQueueMessageProcessorPlugin(),
            ServicePointStorageConfig::QUEUE_NAME_SYNC_STORAGE_SERVICE_POINT => new SynchronizationStorageQueueMessageProcessorPlugin(),
            ShipmentTypeStorageConfig::QUEUE_NAME_SYNC_STORAGE_SHIPMENT_TYPE => new SynchronizationStorageQueueMessageProcessorPlugin(),
            ProductOfferServicePointStorageConfig::QUEUE_NAME_SYNC_STORAGE_PRODUCT_OFFER_SERVICE => new SynchronizationStorageQueueMessageProcessorPlugin(),
            ProductOfferShipmentTypeStorageConfig::PRODUCT_OFFER_SHIPMENT_TYPE_SYNC_STORAGE_QUEUE => new SynchronizationStorageQueueMessageProcessorPlugin(),
        ];
    }

    /**
     * @return array<\Spryker\Zed\QueueExtension\Dependency\Plugin\QueueMessageCheckerPluginInterface>
     */
    protected function getQueueMessageCheckerPlugins(): array
    {
        return [
            new RabbitMqQueueMessageCheckerPlugin(),
        ];
    }
}
