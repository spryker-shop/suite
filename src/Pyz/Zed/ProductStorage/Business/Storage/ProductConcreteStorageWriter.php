<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductStorage\Business\Storage;

use Generated\Shared\Transfer\QueueSendMessageTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Orm\Zed\ProductStorage\Persistence\SpyProductConcreteStorage;
use Propel\Runtime\Propel;
use Pyz\Zed\ProductStorage\Business\Storage\Cte\ProductStorageCteStrategyInterface;
use Spryker\Client\Queue\QueueClientInterface;
use Spryker\Service\Synchronization\SynchronizationServiceInterface;
use Spryker\Zed\ProductStorage\Business\Storage\ProductConcreteStorageWriter as SprykerProductConcreteStorageWriter;
use Spryker\Zed\ProductStorage\Dependency\Facade\ProductStorageToProductInterface;
use Spryker\Zed\ProductStorage\Persistence\ProductStorageQueryContainerInterface;

/**
 * @example
 *
 * This is an example of running ProductConcreteStorageWriter
 * with CTE (@see https://www.postgresql.org/docs/9.1/queries-with.html).
 * By using this class, reduce the amount of database queries and increase the performance
 * for saving storage data in database.
 */
class ProductConcreteStorageWriter extends SprykerProductConcreteStorageWriter
{
    /**
     * @var \Spryker\Service\Synchronization\SynchronizationServiceInterface
     */
    protected $synchronizationService;

    /**
     * @var \Spryker\Client\Queue\QueueClientInterface
     */
    protected $queueClient;

    /**
     * @var array
     */
    protected $synchronizedDataCollection = [];

    /**
     * @var array
     */
    protected $synchronizedMessageCollection = [];

    /**
     * @var \Pyz\Zed\ProductStorage\Business\Storage\Cte\ProductStorageCteStrategyInterface
     */
    protected $productConcreteStorageCte;

    /**
     * @param \Spryker\Zed\ProductStorage\Dependency\Facade\ProductStorageToProductInterface $productFacade
     * @param \Spryker\Zed\ProductStorage\Persistence\ProductStorageQueryContainerInterface $queryContainer
     * @param bool $isSendingToQueue
     * @param \Spryker\Service\Synchronization\SynchronizationServiceInterface $synchronizationService
     * @param \Spryker\Client\Queue\QueueClientInterface $queueClient
     * @param \Pyz\Zed\ProductStorage\Business\Storage\Cte\ProductStorageCteStrategyInterface $productConcreteStorageCte
     */
    public function __construct(
        ProductStorageToProductInterface $productFacade,
        ProductStorageQueryContainerInterface $queryContainer,
        $isSendingToQueue,
        SynchronizationServiceInterface $synchronizationService,
        QueueClientInterface $queueClient,
        ProductStorageCteStrategyInterface $productConcreteStorageCte
    ) {
        parent::__construct($productFacade, $queryContainer, $isSendingToQueue);

        $this->synchronizationService = $synchronizationService;
        $this->queueClient = $queueClient;
        $this->productConcreteStorageCte = $productConcreteStorageCte;
    }

    /**
     * @param array $productConcreteLocalizedEntities
     * @param array<\Orm\Zed\ProductStorage\Persistence\SpyProductConcreteStorage> $productConcreteStorageEntities
     *
     * @return void
     */
    protected function storeData(array $productConcreteLocalizedEntities, array $productConcreteStorageEntities)
    {
        $pairedEntities = $this->pairProductConcreteLocalizedEntitiesWithProductConcreteStorageEntities(
            $productConcreteLocalizedEntities,
            $productConcreteStorageEntities
        );

        foreach ($pairedEntities as $pair) {
            $productConcreteLocalizedEntity = $pair[static::PRODUCT_CONCRETE_LOCALIZED_ENTITY];
            $productConcreteStorageEntity = $pair[static::PRODUCT_CONCRETE_STORAGE_ENTITY];

            if ($productConcreteLocalizedEntity === null || !$this->isActive($productConcreteLocalizedEntity)) {
                $this->deletedProductConcreteSorageEntity($productConcreteStorageEntity);

                continue;
            }

            $this->storeProductConcreteStorageEntity(
                $productConcreteLocalizedEntity,
                $productConcreteStorageEntity,
                $pair[static::LOCALE_NAME]
            );
        }

        $this->write();

        if ($this->synchronizedMessageCollection !== []) {
            $this->queueClient->sendMessages('sync.storage.product', $this->synchronizedMessageCollection);
        }
    }

    /**
     * @param array $productConcreteLocalizedEntity
     * @param \Orm\Zed\ProductStorage\Persistence\SpyProductConcreteStorage $productConcreteStorageEntity
     * @param string $localeName
     *
     * @return void
     */
    protected function storeProductConcreteStorageEntity(
        array $productConcreteLocalizedEntity,
        SpyProductConcreteStorage $productConcreteStorageEntity,
        $localeName
    ) {
        $productConcreteStorageTransfer = $this->mapToProductConcreteStorageTransfer($productConcreteLocalizedEntity);
        $productConcreteStorageData = [
            'fk_product' => $productConcreteLocalizedEntity[static::COL_FK_PRODUCT],
            'data' => $productConcreteStorageTransfer->toArray(),
            'locale' => $localeName,
        ];

        $this->add($productConcreteStorageData);
    }

    /**
     * @return void
     */
    protected function loadSuperAttributes()
    {
        $superAttributes = $this->queryContainer
            ->queryProductAttributeKey()
            ->find();

        if (empty($superAttributes->getData())) {
            $this->superAttributeKeyBuffer[] = null;

            return;
        }

        foreach ($superAttributes as $attribute) {
            $this->superAttributeKeyBuffer[$attribute->getKey()] = true;
        }
    }

    /**
     * @param array $productConcreteStorageData
     *
     * @return void
     */
    protected function add(array $productConcreteStorageData): void
    {
        $synchronizedData = $this->buildSynchronizedData($productConcreteStorageData, 'fk_product', 'product_concrete');
        $this->synchronizedDataCollection[] = $synchronizedData;

        if ($this->isSendingToQueue) {
            $this->synchronizedMessageCollection[] = $this->buildSynchronizedMessage($synchronizedData, 'product_concrete');
        }
    }

    /**
     * @param array $data
     * @param string $keySuffix
     * @param string $resourceName
     *
     * @return array
     */
    public function buildSynchronizedData(array $data, string $keySuffix, string $resourceName): array
    {
        $key = $this->generateResourceKey($data, $keySuffix, $resourceName);
        $encodedData = json_encode($data['data']);
        $data['key'] = $key;
        $data['data'] = $encodedData;

        return $data;
    }

    /**
     * @param array $data
     * @param string $keySuffix
     * @param string $resourceName
     *
     * @return string
     */
    protected function generateResourceKey(array $data, string $keySuffix, string $resourceName)
    {
        $syncTransferData = new SynchronizationDataTransfer();
        if (isset($data['store'])) {
            $syncTransferData->setStore($data['store']);
        }

        if (isset($data['locale'])) {
            $syncTransferData->setLocale($data['locale']);
        }

        $syncTransferData->setReference($data[$keySuffix]);
        $keyBuilder = $this->synchronizationService->getStorageKeyBuilder($resourceName);

        return $keyBuilder->generateKey($syncTransferData);
    }

    /**
     * @param array $data
     * @param string $resourceName
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\QueueSendMessageTransfer
     */
    public function buildSynchronizedMessage(
        array $data,
        string $resourceName,
        array $params = []
    ): QueueSendMessageTransfer {
        $data['_timestamp'] = microtime(true);
        $payload = [
            'write' => [
                'key' => $data['key'],
                'value' => $data['data'],
                'resource' => $resourceName,
                'params' => $params,
            ],
        ];

        $queueSendTransfer = new QueueSendMessageTransfer();
        $queueSendTransfer->setBody(json_encode($payload));

        if (isset($data['store'])) {
            $queueSendTransfer->setStoreName($data['store']);

            return $queueSendTransfer;
        }

        $queueSendTransfer->setQueuePoolName('synchronizationPool');

        return $queueSendTransfer;
    }

    /**
     * @return void
     */
    public function write()
    {
        if (empty($this->synchronizedDataCollection)) {
            return;
        }

        $stmt = Propel::getConnection()->prepare($this->getSql());
        $stmt->execute($this->getParams());
    }

    /**
     * @return string
     */
    protected function getSql(): string
    {
        return $this->productConcreteStorageCte->getSql();
    }

    /**
     * @return array<string>
     */
    protected function getParams(): array
    {
        return $this->productConcreteStorageCte->buildParams($this->synchronizedDataCollection);
    }
}
