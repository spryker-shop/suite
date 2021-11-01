<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\PriceProductStorage\Business\Storage;

use Generated\Shared\Transfer\PriceProductStorageTransfer;
use Generated\Shared\Transfer\QueueSendMessageTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Orm\Zed\PriceProductStorage\Persistence\SpyPriceProductAbstractStorage;
use Propel\Runtime\Propel;
use Pyz\Zed\PriceProductStorage\Business\Storage\Cte\PriceProductStorageCteInterface;
use Spryker\Client\Queue\QueueClientInterface;
use Spryker\Service\Synchronization\SynchronizationServiceInterface;
use Spryker\Zed\PriceProductStorage\Business\Storage\PriceProductAbstractStorageWriter as SprykerPriceProductAbstractStorageWriter;
use Spryker\Zed\PriceProductStorage\Dependency\Facade\PriceProductStorageToPriceProductFacadeInterface;
use Spryker\Zed\PriceProductStorage\Dependency\Facade\PriceProductStorageToStoreFacadeInterface;
use Spryker\Zed\PriceProductStorage\Persistence\PriceProductStorageQueryContainerInterface;

/**
 * @example
 *
 * This is an example of running PriceProductAbstractStorageWriter
 * with CTE (@see https://www.postgresql.org/docs/9.1/queries-with.html).
 * By using this class, reduce the amount of database queries and increase the performance
 * for saving storage data in database.
 */
class PriceProductAbstractStorageWriter extends SprykerPriceProductAbstractStorageWriter
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
     * @var \Pyz\Zed\PriceProductStorage\Business\Storage\Cte\PriceProductStorageCteInterface
     */
    private $priceProductAbstractStorageCte;

    /**
     * @param \Spryker\Zed\PriceProductStorage\Dependency\Facade\PriceProductStorageToPriceProductFacadeInterface $priceProductFacade
     * @param \Spryker\Zed\PriceProductStorage\Dependency\Facade\PriceProductStorageToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\PriceProductStorage\Persistence\PriceProductStorageQueryContainerInterface $queryContainer
     * @param bool $isSendingToQueue
     * @param \Spryker\Service\Synchronization\SynchronizationServiceInterface $synchronizationService
     * @param \Spryker\Client\Queue\QueueClientInterface $queueClient
     * @param \Pyz\Zed\PriceProductStorage\Business\Storage\Cte\PriceProductStorageCteInterface $priceProductAbstractStorageCte
     */
    public function __construct(
        PriceProductStorageToPriceProductFacadeInterface $priceProductFacade,
        PriceProductStorageToStoreFacadeInterface $storeFacade,
        PriceProductStorageQueryContainerInterface $queryContainer,
        bool $isSendingToQueue,
        SynchronizationServiceInterface $synchronizationService,
        QueueClientInterface $queueClient,
        PriceProductStorageCteInterface $priceProductAbstractStorageCte
    ) {
        parent::__construct($priceProductFacade, $storeFacade, $queryContainer, $isSendingToQueue);

        $this->synchronizationService = $synchronizationService;
        $this->queueClient = $queueClient;
        $this->priceProductAbstractStorageCte = $priceProductAbstractStorageCte;
    }

    /**
     * @param array $priceGroups First level keys are product abstract ids, second level keys are store names, values are grouped prices
     * @param array $priceProductAbstractStorageMap First level keys are product abstract ids, second level keys are store names, values are SpyPriceProductAbstractStorage objects
     *
     * @return void
     */
    protected function storeData(array $priceGroups, array $priceProductAbstractStorageMap)
    {
        foreach ($priceGroups as $idProductAbstract => $storePriceGroups) {
            foreach ($storePriceGroups as $storeName => $priceGroup) {
                $priceProductAbstractStorage = $this->getRelatedPriceProductAbstractStorageEntity(
                    $priceProductAbstractStorageMap,
                    $idProductAbstract,
                    $storeName,
                );

                unset($priceProductAbstractStorageMap[$idProductAbstract][$storeName]);

                if ($this->hasProductAbstractPrices($priceGroup)) {
                    $this->createPriceProductStorage(
                        $idProductAbstract,
                        $storeName,
                        $priceGroup,
                    );

                    continue;
                }

                $this->deletePriceProduct($priceProductAbstractStorage);
            }
        }

        $this->write();

        if ($this->synchronizedMessageCollection !== []) {
            $this->queueClient->sendMessages('sync.storage.price', $this->synchronizedMessageCollection);
        }

        array_walk_recursive($priceProductAbstractStorageMap, function (SpyPriceProductAbstractStorage $priceProductAbstractStorageEntity) {
            $priceProductAbstractStorageEntity->delete();
        });
    }

    /**
     * @param int $idProductAbstract
     * @param string $storeName
     * @param array $priceGroup
     *
     * @return void
     */
    protected function createPriceProductStorage(
        $idProductAbstract,
        $storeName,
        array $priceGroup
    ) {
        $priceProductStorageTransfer = (new PriceProductStorageTransfer())
            ->setPrices($priceGroup);

        $priceProductAbstractStorageData = [
            'fk_product_abstract' => $idProductAbstract,
            'data' => $priceProductStorageTransfer->toArray(),
            'store' => $storeName,
        ];

        $this->add($priceProductAbstractStorageData);
    }

    /**
     * @param array $priceProductAbstractStorageData
     *
     * @return void
     */
    protected function add(array $priceProductAbstractStorageData): void
    {
        $synchronizedData = $this->buildSynchronizedData($priceProductAbstractStorageData, 'fk_product_abstract', 'price_product_abstract');
        $this->synchronizedDataCollection[] = $synchronizedData;

        if ($this->isSendingToQueue) {
            $this->synchronizedMessageCollection[] = $this->buildSynchronizedMessage($synchronizedData, 'price_product_abstract');
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
        return $this->priceProductAbstractStorageCte->getSql();
    }

    /**
     * @return array
     */
    protected function getParams(): array
    {
        return $this->priceProductAbstractStorageCte->buildParams($this->synchronizedDataCollection);
    }
}
