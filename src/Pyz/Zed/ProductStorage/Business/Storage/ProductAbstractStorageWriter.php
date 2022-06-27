<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductStorage\Business\Storage;

use Generated\Shared\Transfer\ProductAbstractStorageTransfer;
use Generated\Shared\Transfer\QueueSendMessageTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Propel\Runtime\Propel;
use Pyz\Zed\ProductStorage\Business\Storage\Cte\ProductStorageCteStrategyInterface;
use Spryker\Client\Queue\QueueClientInterface;
use Spryker\Service\Synchronization\SynchronizationServiceInterface;
use Spryker\Zed\ProductStorage\Business\Attribute\AttributeMapInterface;
use Spryker\Zed\ProductStorage\Business\Storage\ProductAbstractStorageWriter as SprykerProductAbstractStorageWriter;
use Spryker\Zed\ProductStorage\Dependency\Facade\ProductStorageToProductInterface;
use Spryker\Zed\ProductStorage\Dependency\Facade\ProductStorageToStoreFacadeInterface;
use Spryker\Zed\ProductStorage\Persistence\ProductStorageQueryContainerInterface;

/**
 * @example
 *
 * This is an example of running ProductAbstractStorageWriter
 * with CTE (@see https://www.postgresql.org/docs/9.1/queries-with.html).
 * By using this class, reduce the amount of database queries and increase the performance
 * for saving storage data in database.
 */
class ProductAbstractStorageWriter extends SprykerProductAbstractStorageWriter
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
     * @var array<array<string, mixed>>
     */
    protected $synchronizedDataCollection = [];

    /**
     * @var array<\Generated\Shared\Transfer\QueueSendMessageTransfer>
     */
    protected $synchronizedMessageCollection = [];

    /**
     * @var array<\Spryker\Zed\ProductStorageExtension\Dependency\Plugin\ProductAbstractStorageExpanderPluginInterface>
     */
    protected $productAbstractStorageExpanderPlugins = [];

    /**
     * @var array<\Spryker\Zed\ProductStorageExtension\Dependency\Plugin\ProductAbstractStorageCollectionFilterPluginInterface>
     */
    protected $productAbstractStorageCollectionFilterPlugins = [];

    /**
     * @var \Pyz\Zed\ProductStorage\Business\Storage\Cte\ProductStorageCteStrategyInterface
     */
    protected $productAbstractStorageCte;

    /**
     * @param \Spryker\Zed\ProductStorage\Dependency\Facade\ProductStorageToProductInterface $productFacade
     * @param \Spryker\Zed\ProductStorage\Business\Attribute\AttributeMapInterface $attributeMap
     * @param \Spryker\Zed\ProductStorage\Persistence\ProductStorageQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\ProductStorage\Dependency\Facade\ProductStorageToStoreFacadeInterface $storeFacade
     * @param bool $isSendingToQueue
     * @param array<\Spryker\Zed\ProductStorageExtension\Dependency\Plugin\ProductAbstractStorageExpanderPluginInterface> $productAbstractStorageExpanderPlugins
     * @param array<\Spryker\Zed\ProductStorageExtension\Dependency\Plugin\ProductAbstractStorageCollectionFilterPluginInterface> $productAbstractStorageCollectionFilterPlugins
     * @param \Spryker\Service\Synchronization\SynchronizationServiceInterface $synchronizationService
     * @param \Spryker\Client\Queue\QueueClientInterface $queueClient
     * @param \Pyz\Zed\ProductStorage\Business\Storage\Cte\ProductStorageCteStrategyInterface $productAbstractStorageCte
     */
    public function __construct(
        ProductStorageToProductInterface $productFacade,
        AttributeMapInterface $attributeMap,
        ProductStorageQueryContainerInterface $queryContainer,
        ProductStorageToStoreFacadeInterface $storeFacade,
        $isSendingToQueue,
        array $productAbstractStorageExpanderPlugins,
        array $productAbstractStorageCollectionFilterPlugins,
        SynchronizationServiceInterface $synchronizationService,
        QueueClientInterface $queueClient,
        ProductStorageCteStrategyInterface $productAbstractStorageCte
    ) {
        parent::__construct(
            $productFacade,
            $attributeMap,
            $queryContainer,
            $storeFacade,
            $isSendingToQueue,
            $productAbstractStorageExpanderPlugins,
            $productAbstractStorageCollectionFilterPlugins,
        );

        $this->synchronizationService = $synchronizationService;
        $this->queueClient = $queueClient;
        $this->productAbstractStorageCte = $productAbstractStorageCte;
    }

    /**
     * @param array<array<string, mixed>> $productAbstractLocalizedEntities
     * @param array<\Orm\Zed\ProductStorage\Persistence\SpyProductAbstractStorage> $productAbstractStorageEntities
     *
     * @return void
     */
    protected function storeData(array $productAbstractLocalizedEntities, array $productAbstractStorageEntities): void
    {
        $pairedEntities = $this->pairProductAbstractLocalizedEntitiesWithProductAbstractStorageEntities(
            $productAbstractLocalizedEntities,
            $productAbstractStorageEntities,
        );

        $attributeMapBulk = $this->attributeMap->generateAttributeMapBulk(
            array_column($productAbstractLocalizedEntities, static::COL_FK_PRODUCT_ABSTRACT),
            array_column($productAbstractLocalizedEntities, static::COL_FK_LOCALE),
        );

        $productAbstractStorageTransfers = $this->mapProductAbstractLocalizedEntitiesToProductAbstractStorageTransfers(
            $productAbstractLocalizedEntities,
            $attributeMapBulk,
        );
        $productAbstractStorageTransfers = $this->executeProductAbstractStorageFilterPlugins($productAbstractStorageTransfers);
        $indexedProductAbstractStorageTransfers = $this->indexProductAbstractStorageTransfersByIdProductAbstract($productAbstractStorageTransfers);

        foreach ($pairedEntities as $pair) {
            $productAbstractLocalizedEntity = $pair[static::PRODUCT_ABSTRACT_LOCALIZED_ENTITY];
            $productAbstractStorageEntity = $pair[static::PRODUCT_ABSTRACT_STORAGE_ENTITY];

            if ($productAbstractLocalizedEntity === null || !$this->isActive($productAbstractLocalizedEntity)) {
                $this->deleteProductAbstractStorageEntity($productAbstractStorageEntity);

                continue;
            }

            $idProductAbstract = $productAbstractLocalizedEntity[static::COL_FK_PRODUCT_ABSTRACT];
            $productAbstractStorageTransfer = $indexedProductAbstractStorageTransfers[$idProductAbstract] ?? null;

            if ($productAbstractStorageTransfer === null) {
                $this->deleteProductAbstractStorageEntity($productAbstractStorageEntity);

                continue;
            }

            $this->addProductAbstractStorageEntity(
                $productAbstractLocalizedEntity,
                $pair[static::STORE_NAME],
                $pair[static::LOCALE_NAME],
                $attributeMapBulk,
            );
        }

        $this->write();

        if ($this->synchronizedMessageCollection !== []) {
            $this->queueClient->sendMessages('sync.storage.product', $this->synchronizedMessageCollection);
        }
    }

    /**
     * @param array<string, mixed> $productAbstractLocalizedEntity
     * @param string $storeName
     * @param string $localeName
     * @param array<string, \Generated\Shared\Transfer\AttributeMapStorageTransfer> $attributeMapBulk
     *
     * @return void
     */
    protected function addProductAbstractStorageEntity(
        array $productAbstractLocalizedEntity,
        $storeName,
        $localeName,
        array $attributeMapBulk = []
    ): void {
        $productAbstractStorageTransfer = $this->mapToProductAbstractStorageTransfer(
            $productAbstractLocalizedEntity,
            new ProductAbstractStorageTransfer(),
            $attributeMapBulk,
        );

        $productAbstractStorageData = [
            'fk_product_abstract' => $productAbstractLocalizedEntity['SpyProductAbstract'][static::COL_ID_PRODUCT_ABSTRACT],
            'data' => $productAbstractStorageTransfer->toArray(),
            'store' => $storeName,
            'locale' => $localeName,
        ];

        $this->add($productAbstractStorageData);
    }

    /**
     * @param array<string, mixed> $productAbstractStorageData
     *
     * @return void
     */
    protected function add(array $productAbstractStorageData): void
    {
        $synchronizedData = $this->buildSynchronizedData($productAbstractStorageData, 'fk_product_abstract', 'product_abstract');
        $this->synchronizedDataCollection[] = $synchronizedData;

        if ($this->isSendingToQueue) {
            $this->synchronizedMessageCollection[] = $this->buildSynchronizedMessage($synchronizedData, 'product_abstract');
        }
    }

    /**
     * @param array<string, mixed> $data
     * @param string $keySuffix
     * @param string $resourceName
     *
     * @return array<string, mixed>
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
     * @param array<string, mixed> $data
     * @param string $keySuffix
     * @param string $resourceName
     *
     * @return string
     */
    protected function generateResourceKey(array $data, string $keySuffix, string $resourceName): string
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
     * @param array<string, mixed> $data
     * @param string $resourceName
     * @param array<string, mixed> $params
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
    public function write(): void
    {
        if (!$this->synchronizedDataCollection) {
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
        return $this->productAbstractStorageCte->getSql();
    }

    /**
     * @return array<string>
     */
    protected function getParams(): array
    {
        return $this->productAbstractStorageCte->buildParams($this->synchronizedDataCollection);
    }
}
