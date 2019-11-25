<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\RabbitMq;

use ArrayObject;
use Generated\Shared\Transfer\RabbitMqOptionTransfer;
use Spryker\Client\RabbitMq\Model\Connection\Connection;
use Spryker\Client\RabbitMq\RabbitMqConfig as SprykerRabbitMqConfig;
use Spryker\Shared\AvailabilityStorage\AvailabilityStorageConstants;
use Spryker\Shared\CategoryPageSearch\CategoryPageSearchConstants;
use Spryker\Shared\CategoryStorage\CategoryStorageConstants;
use Spryker\Shared\CmsPageSearch\CmsPageSearchConstants;
use Spryker\Shared\CmsStorage\CmsStorageConstants;
use Spryker\Shared\CompanyUserStorage\CompanyUserStorageConfig;
use Spryker\Shared\ConfigurableBundleStorage\ConfigurableBundleStorageConfig;
use Spryker\Shared\ContentStorage\ContentStorageConfig;
use Spryker\Shared\CustomerAccessStorage\CustomerAccessStorageConstants;
use Spryker\Shared\Event\EventConfig;
use Spryker\Shared\Event\EventConstants;
use Spryker\Shared\FileManagerStorage\FileManagerStorageConstants;
use Spryker\Shared\GlossaryStorage\GlossaryStorageConfig;
use Spryker\Shared\Log\LogConstants;
use Spryker\Shared\PriceProductStorage\PriceProductStorageConstants;
use Spryker\Shared\ProductPackagingUnitStorage\ProductPackagingUnitStorageConfig;
use Spryker\Shared\ProductPageSearch\ProductPageSearchConstants;
use Spryker\Shared\ProductStorage\ProductStorageConstants;
use Spryker\Shared\ShoppingListStorage\ShoppingListStorageConfig;
use Spryker\Shared\TaxProductStorage\TaxProductStorageConfig;
use Spryker\Shared\TaxStorage\TaxStorageConfig;
use Spryker\Shared\UrlStorage\UrlStorageConstants;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class RabbitMqConfig extends SprykerRabbitMqConfig
{
    /**
     * @var \ArrayObject|null
     */
    protected $queueOptionCollection;

    /**
     *  QueueNameFoo, // Queue and error queue will be created: QueueNameFoo and QueueNameFoo.error
     *  QueueNameBar => [
     *       RoutingKeyFoo => QueueNameBaz, // additional queues can be defined by several routing keys
     *   ],
     *
     * @see https://www.rabbitmq.com/tutorials/amqp-concepts.html
     *
     * @return array
     */
    protected function getQueueConfiguration(): array
    {
        return [
            EventConstants::EVENT_QUEUE => [
                EventConfig::EVENT_ROUTING_KEY_RETRY => EventConstants::EVENT_QUEUE_RETRY,
                EventConfig::EVENT_ROUTING_KEY_ERROR => EventConstants::EVENT_QUEUE_ERROR,
            ],
            GlossaryStorageConfig::SYNC_QUEUE_NAME,
            UrlStorageConstants::URL_SYNC_STORAGE_QUEUE,
            AvailabilityStorageConstants::AVAILABILITY_SYNC_STORAGE_QUEUE,
            CustomerAccessStorageConstants::CUSTOMER_ACCESS_SYNC_STORAGE_QUEUE,
            CategoryStorageConstants::CATEGORY_SYNC_STORAGE_QUEUE,
            ProductStorageConstants::PRODUCT_SYNC_STORAGE_QUEUE,
            PriceProductStorageConstants::PRICE_SYNC_STORAGE_QUEUE,
            ProductPackagingUnitStorageConfig::PRODUCT_PACKAGING_UNIT_SYNC_STORAGE_QUEUE,
            ConfigurableBundleStorageConfig::CONFIGURABLE_BUNDLE_SYNC_STORAGE_QUEUE,
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
            $this->get(LogConstants::LOG_QUEUE_NAME),
        ];
    }

    /**
     * @return \ArrayObject
     */
    protected function getQueueOptions()
    {
        if ($this->queueOptionCollection !== null) {
            return $this->queueOptionCollection;
        }

        $queueConfigurations = $this->getQueueConfiguration();
        $this->queueOptionCollection = new ArrayObject();

        foreach ($queueConfigurations as $queueNameKey => $queueConfiguration) {
            if (!is_array($queueConfiguration)) {
                $this->queueOptionCollection->append($this->createQueueOption($queueConfiguration, sprintf('%s.error', $queueConfiguration), 'error'));

                continue;
            }

            foreach ($queueConfiguration as $routingKey => $queueName) {
                $this->queueOptionCollection->append($this->createQueueOption($queueNameKey, $queueName, $routingKey));
            }
        }

        return $this->queueOptionCollection;
    }

    /**
     * @param string $queueName
     * @param string $boundQueueName
     * @param string $routingKey
     *
     * @return \Generated\Shared\Transfer\RabbitMqOptionTransfer
     */
    protected function createQueueOption($queueName, $boundQueueName, $routingKey)
    {
        $queueOptionTransfer = new RabbitMqOptionTransfer();
        $queueOptionTransfer
            ->setQueueName($queueName)
            ->setDurable(true)
            ->setType('direct')
            ->setDeclarationType(Connection::RABBIT_MQ_EXCHANGE)
            ->addBindingQueueItem($this->createQueueBinding($queueName))
            ->addBindingQueueItem($this->createBoundQueueBinding($boundQueueName, $routingKey));

        return $queueOptionTransfer;
    }

    /**
     * @param string $queueName
     * @param string $routingKey
     *
     * @return \Generated\Shared\Transfer\RabbitMqOptionTransfer
     */
    protected function createQueueBinding($queueName, $routingKey = '')
    {
        $queueOptionTransfer = new RabbitMqOptionTransfer();
        $queueOptionTransfer
            ->setQueueName($queueName)
            ->setDurable(true)
            ->setNoWait(false)
            ->addRoutingKey($routingKey);

        return $queueOptionTransfer;
    }

    /**
     * @param string $boundQueueName
     * @param string $routingKey
     *
     * @return \Generated\Shared\Transfer\RabbitMqOptionTransfer
     */
    protected function createBoundQueueBinding($boundQueueName, $routingKey)
    {
        $queueOptionTransfer = new RabbitMqOptionTransfer();
        $queueOptionTransfer
            ->setQueueName($boundQueueName)
            ->setDurable(true)
            ->setNoWait(false)
            ->addRoutingKey($routingKey);

        return $queueOptionTransfer;
    }
}
