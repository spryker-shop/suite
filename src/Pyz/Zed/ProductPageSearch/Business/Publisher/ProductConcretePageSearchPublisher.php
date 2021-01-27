<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductPageSearch\Business\Publisher;

use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductConcretePageSearchTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\QueueSendMessageTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Propel\Runtime\Propel;
use Pyz\Zed\ProductPageSearch\Business\Publisher\Sql\ProductPagePublisherCteInterface;
use Spryker\Client\Queue\QueueClientInterface;
use Spryker\Service\Synchronization\SynchronizationServiceInterface;
use Spryker\Zed\ProductPageSearch\Business\DataMapper\AbstractProductSearchDataMapper;
use Spryker\Zed\ProductPageSearch\Business\ProductConcretePageSearchReader\ProductConcretePageSearchReaderInterface;
use Spryker\Zed\ProductPageSearch\Business\ProductConcretePageSearchWriter\ProductConcretePageSearchWriterInterface;
use Spryker\Zed\ProductPageSearch\Business\Publisher\ProductConcretePageSearchPublisher as SprykerProductConcretePageSearchPublisher;
use Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToProductInterface;
use Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToStoreFacadeInterface;
use Spryker\Zed\ProductPageSearch\Dependency\Service\ProductPageSearchToUtilEncodingInterface;
use Spryker\Zed\ProductPageSearch\ProductPageSearchConfig;

/**
 * @example
 *
 * @SuppressWarnings(PHPMD.ExcessiveParameterList)
 *
 * This is an example of running ProductConcretePageSearchPublisher
 * with CTE (@see https://www.postgresql.org/docs/9.1/queries-with.html).
 * By using this class, reduce the amount of database queries and increase the performance
 * for saving storage data in database.
 */
class ProductConcretePageSearchPublisher extends SprykerProductConcretePageSearchPublisher
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
     * @var \Pyz\Zed\ProductPageSearch\Business\Publisher\Sql\ProductPagePublisherCteInterface
     */
    protected $productConcretePagePublisherCte;

    /**
     * @var array
     */
    protected $synchronizedDataCollection = [];

    /**
     * @var array
     */
    protected $synchronizedMessageCollection = [];

    /**
     * @param \Spryker\Zed\ProductPageSearch\Business\ProductConcretePageSearchReader\ProductConcretePageSearchReaderInterface $productConcretePageSearchReader
     * @param \Spryker\Zed\ProductPageSearch\Business\ProductConcretePageSearchWriter\ProductConcretePageSearchWriterInterface $productConcretePageSearchWriter
     * @param \Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToProductInterface $productFacade
     * @param \Spryker\Zed\ProductPageSearch\Dependency\Service\ProductPageSearchToUtilEncodingInterface $utilEncoding
     * @param \Spryker\Zed\ProductPageSearch\Business\DataMapper\AbstractProductSearchDataMapper $productConcreteSearchDataMapper
     * @param \Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\ProductPageSearch\ProductPageSearchConfig $productPageSearchConfig
     * @param \Spryker\Zed\ProductPageSearchExtension\Dependency\Plugin\ProductConcretePageDataExpanderPluginInterface[] $pageDataExpanderPlugins
     * @param \Spryker\Service\Synchronization\SynchronizationServiceInterface $synchronizationService
     * @param \Spryker\Client\Queue\QueueClientInterface $queueClient
     * @param \Pyz\Zed\ProductPageSearch\Business\Publisher\Sql\ProductPagePublisherCteInterface $productConcretePagePublisherCte
     */
    public function __construct(
        ProductConcretePageSearchReaderInterface $productConcretePageSearchReader,
        ProductConcretePageSearchWriterInterface $productConcretePageSearchWriter,
        ProductPageSearchToProductInterface $productFacade,
        ProductPageSearchToUtilEncodingInterface $utilEncoding,
        AbstractProductSearchDataMapper $productConcreteSearchDataMapper,
        ProductPageSearchToStoreFacadeInterface $storeFacade,
        ProductPageSearchConfig $productPageSearchConfig,
        array $pageDataExpanderPlugins,
        SynchronizationServiceInterface $synchronizationService,
        QueueClientInterface $queueClient,
        ProductPagePublisherCteInterface $productConcretePagePublisherCte
    ) {
        parent::__construct(
            $productConcretePageSearchReader,
            $productConcretePageSearchWriter,
            $productFacade,
            $utilEncoding,
            $productConcreteSearchDataMapper,
            $storeFacade,
            $productPageSearchConfig,
            $pageDataExpanderPlugins
        );

        $this->synchronizationService = $synchronizationService;
        $this->queueClient = $queueClient;
        $this->productConcretePagePublisherCte = $productConcretePagePublisherCte;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer[] $productConcreteTransfers
     * @param \Generated\Shared\Transfer\ProductConcretePageSearchTransfer[] $productConcretePageSearchTransfers
     *
     * @return void
     */
    protected function executePublishTransaction(
        array $productConcreteTransfers,
        array $productConcretePageSearchTransfers
    ): void {
        foreach ($productConcreteTransfers as $productConcreteTransfer) {
            foreach ($productConcreteTransfer->getStores() as $storeTransfer) {
                $this->syncProductConcretePageSearchPerStore(
                    $productConcreteTransfer,
                    $storeTransfer,
                    $productConcretePageSearchTransfers[$productConcreteTransfer->getIdProductConcrete()][$storeTransfer->getName()] ?? []
                );
            }
        }

        $this->write();

        if ($this->synchronizedMessageCollection !== []) {
            $this->queueClient->sendMessages('sync.search.product', $this->synchronizedMessageCollection);
            $this->synchronizedMessageCollection = [];
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\ProductConcretePageSearchTransfer $productConcretePageSearchTransfer
     * @param \Generated\Shared\Transfer\LocalizedAttributesTransfer $localizedAttributesTransfer
     *
     * @return void
     */
    protected function syncProductConcretePageSearchPerLocale(
        ProductConcreteTransfer $productConcreteTransfer,
        StoreTransfer $storeTransfer,
        ProductConcretePageSearchTransfer $productConcretePageSearchTransfer,
        LocalizedAttributesTransfer $localizedAttributesTransfer
    ): void {
        if (!$productConcreteTransfer->getIsActive() && $productConcretePageSearchTransfer->getIdProductConcretePageSearch() !== null) {
            $this->deleteProductConcretePageSearch($productConcretePageSearchTransfer);

            return;
        }

        if (!$this->isValidStoreLocale($storeTransfer->getName(), $localizedAttributesTransfer->getLocale()->getLocaleName())) {
            if ($productConcretePageSearchTransfer->getIdProductConcretePageSearch() !== null) {
                $this->deleteProductConcretePageSearch($productConcretePageSearchTransfer);
            }

            return;
        }

        $this->mapProductConcretePageSearchTransfer(
            $productConcreteTransfer,
            $storeTransfer,
            $productConcretePageSearchTransfer,
            $localizedAttributesTransfer
        );

        $this->add($productConcretePageSearchTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcretePageSearchTransfer $productPageSearchTransfer
     *
     * @return void
     */
    protected function add(ProductConcretePageSearchTransfer $productPageSearchTransfer): void
    {
        $synchronizedData = $this->buildSynchronizedData($productPageSearchTransfer, 'product_concrete');
        $this->synchronizedDataCollection[] = $synchronizedData;

        $this->synchronizedMessageCollection[] = $this->buildSynchronizedMessage($synchronizedData, 'product_concrete', ['type' => 'page']);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcretePageSearchTransfer $productPageSearchTransfer
     * @param string $resourceName
     *
     * @return array
     */
    public function buildSynchronizedData(
        ProductConcretePageSearchTransfer $productPageSearchTransfer,
        string $resourceName
    ): array {
        $data = [];
        $key = $this->generateResourceKey($productPageSearchTransfer, $resourceName);
        $encodedData = json_encode($productPageSearchTransfer->getData());
        $data['key'] = $key;
        $data['data'] = $encodedData;
        $data['store'] = $productPageSearchTransfer->getStore();
        $data['locale'] = $productPageSearchTransfer->getLocale();
        $data['structured_data'] = json_encode($productPageSearchTransfer->toArray());
        $data['fk_product'] = $productPageSearchTransfer->getFkProduct();

        return $data;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcretePageSearchTransfer $productPageSearchTransfer
     * @param string $resourceName
     *
     * @return string
     */
    protected function generateResourceKey(
        ProductConcretePageSearchTransfer $productPageSearchTransfer,
        string $resourceName
    ) {
        $syncTransferData = new SynchronizationDataTransfer();
        $syncTransferData->setStore($productPageSearchTransfer->getStore());
        $syncTransferData->setLocale($productPageSearchTransfer->getLocale());
        $syncTransferData->setReference((string)$productPageSearchTransfer->getFkProduct());
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
                'value' => json_decode($data['data']),
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

        $con = Propel::getConnection();

        $sql = $this->productConcretePagePublisherCte->getSql();

        $stmt = $con->prepare($sql);

        $params = $this->productConcretePagePublisherCte->buildParams($this->synchronizedDataCollection);

        $stmt->execute($params);
    }
}
