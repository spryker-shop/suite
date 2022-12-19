<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductPageSearch\Business\Publisher;

use Generated\Shared\Transfer\ProductPageLoadTransfer;
use Generated\Shared\Transfer\ProductPageSearchTransfer;
use Generated\Shared\Transfer\QueueSendMessageTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Orm\Zed\ProductPageSearch\Persistence\SpyProductAbstractPageSearch;
use Propel\Runtime\Propel;
use Pyz\Zed\ProductPageSearch\Business\Publisher\Sql\ProductPagePublisherCteInterface;
use Spryker\Client\Queue\QueueClientInterface;
use Spryker\Service\Synchronization\SynchronizationServiceInterface;
use Spryker\Zed\ProductPageSearch\Business\Mapper\ProductPageSearchMapperInterface;
use Spryker\Zed\ProductPageSearch\Business\Model\ProductPageSearchWriterInterface;
use Spryker\Zed\ProductPageSearch\Business\Publisher\ProductAbstractPagePublisher as SprykerProductAbstractPagePublisher;
use Spryker\Zed\ProductPageSearch\Business\Reader\AddToCartSkuReaderInterface;
use Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToStoreFacadeInterface;
use Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchQueryContainerInterface;
use Spryker\Zed\ProductPageSearch\ProductPageSearchConfig;

/**
 * @example
 *
 * This is an example of running ProductAbstractPagePublisher
 * with CTE (@see https://www.postgresql.org/docs/9.1/queries-with.html).
 * By using this class, reduce the amount of database queries and increase the performance
 * for saving storage data in database.
 */
class ProductAbstractPagePublisher extends SprykerProductAbstractPagePublisher
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
    protected $productAbstractPagePublisherCte;

    /**
     * @var array<array<string, mixed>>
     */
    protected $synchronizedDataCollection = [];

    /**
     * @var array<\Generated\Shared\Transfer\QueueSendMessageTransfer>
     */
    protected $synchronizedMessageCollection = [];

    /**
     * @param \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchQueryContainerInterface $queryContainer
     * @param array<\Spryker\Zed\ProductPageSearch\Dependency\Plugin\ProductPageDataExpanderInterface> $pageDataExpanderPlugins
     * @param array<\Spryker\Zed\ProductPageSearchExtension\Dependency\Plugin\ProductPageDataLoaderPluginInterface> $productPageDataLoaderPlugins
     * @param array<\Spryker\Zed\ProductPageSearchExtension\Dependency\Plugin\ProductPageSearchCollectionFilterPluginInterface> $productPageSearchCollectionFilterPlugins
     * @param \Spryker\Zed\ProductPageSearch\Business\Mapper\ProductPageSearchMapperInterface $productPageSearchMapper
     * @param \Spryker\Zed\ProductPageSearch\Business\Model\ProductPageSearchWriterInterface $productPageSearchWriter
     * @param \Spryker\Zed\ProductPageSearch\ProductPageSearchConfig $productPageSearchConfig
     * @param \Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\ProductPageSearch\Business\Reader\AddToCartSkuReaderInterface $addToCartSkuReader
     * @param \Spryker\Service\Synchronization\SynchronizationServiceInterface $synchronizationService
     * @param \Spryker\Client\Queue\QueueClientInterface $queueClient
     * @param \Pyz\Zed\ProductPageSearch\Business\Publisher\Sql\ProductPagePublisherCteInterface $productAbstractPagePublisherCte
     */
    public function __construct(
        ProductPageSearchQueryContainerInterface $queryContainer,
        array $pageDataExpanderPlugins,
        array $productPageDataLoaderPlugins,
        array $productPageSearchCollectionFilterPlugins,
        ProductPageSearchMapperInterface $productPageSearchMapper,
        ProductPageSearchWriterInterface $productPageSearchWriter,
        ProductPageSearchConfig $productPageSearchConfig,
        ProductPageSearchToStoreFacadeInterface $storeFacade,
        AddToCartSkuReaderInterface $addToCartSkuReader,
        SynchronizationServiceInterface $synchronizationService,
        QueueClientInterface $queueClient,
        ProductPagePublisherCteInterface $productAbstractPagePublisherCte,
    ) {
        parent::__construct(
            $queryContainer,
            $pageDataExpanderPlugins,
            $productPageDataLoaderPlugins,
            $productPageSearchCollectionFilterPlugins,
            $productPageSearchMapper,
            $productPageSearchWriter,
            $productPageSearchConfig,
            $storeFacade,
            $addToCartSkuReader,
        );

        $this->synchronizationService = $synchronizationService;
        $this->queueClient = $queueClient;
        $this->productAbstractPagePublisherCte = $productAbstractPagePublisherCte;
    }

    /**
     * @param array<array<string, mixed>> $productAbstractLocalizedEntities
     * @param array<\Orm\Zed\ProductPageSearch\Persistence\SpyProductAbstractPageSearch> $productAbstractPageSearchEntities
     * @param array<\Spryker\Zed\ProductPageSearch\Dependency\Plugin\ProductPageDataExpanderInterface> $pageDataExpanderPlugins
     * @param \Generated\Shared\Transfer\ProductPageLoadTransfer $productPageLoadTransfer
     * @param int|bool $isRefresh
     *
     * @return void
     */
    protected function storeData(
        array $productAbstractLocalizedEntities,
        array $productAbstractPageSearchEntities,
        array $pageDataExpanderPlugins,
        ProductPageLoadTransfer $productPageLoadTransfer,
        $isRefresh = 0,
    ): void {
        $pairedEntities = $this->pairProductAbstractLocalizedEntitiesWithProductAbstractPageSearchEntities(
            $productAbstractLocalizedEntities,
            $productAbstractPageSearchEntities,
            $productPageLoadTransfer,
        );

        $productPageSearchTransfers = $this->mapPairedEntitiesToProductPageSearchTransfers(
            $pairedEntities,
            $isRefresh,
        );
        $productPageSearchTransfers = $this->executeProductPageSearchCollectionFilterPlugins($productPageSearchTransfers);
        $indexedProductAbstractPageSearchTransfers = $this->indexProductPageSearchTransfersByLocaleAndIdProductAbstract(
            $productPageSearchTransfers,
        );

        foreach ($pairedEntities as $pairedEntity) {
            $productAbstractLocalizedEntity = $pairedEntity[static::PRODUCT_ABSTRACT_LOCALIZED_ENTITY];
            $productAbstractPageSearchEntity = $pairedEntity[static::PRODUCT_ABSTRACT_PAGE_SEARCH_ENTITY];
            $store = $pairedEntity[static::STORE_NAME];
            $locale = $pairedEntity[static::LOCALE_NAME];

            if ($productAbstractLocalizedEntity === null || !$this->isActual($productAbstractLocalizedEntity)) {
                $this->deleteProductAbstractPageSearchEntity($productAbstractPageSearchEntity);

                continue;
            }

            $idProductAbstract = $productAbstractLocalizedEntity['fk_product_abstract'];
            $productPageSearchTransfer = $indexedProductAbstractPageSearchTransfers[$locale][$idProductAbstract] ?? null;

            if ($productPageSearchTransfer === null) {
                $this->deleteProductAbstractPageSearchEntity($productAbstractPageSearchEntity);

                continue;
            }

            $this->storeProductAbstractPageSearchEntity(
                $productAbstractLocalizedEntity,
                $productAbstractPageSearchEntity,
                $productPageSearchTransfer,
                $store,
                $locale,
                $pageDataExpanderPlugins,
            );
        }

        $this->write();

        if ($this->synchronizedMessageCollection !== []) {
            $this->queueClient->sendMessages('sync.search.product', $this->synchronizedMessageCollection);
        }
    }

    /**
     * @param array<string, mixed> $productAbstractLocalizedEntity
     * @param \Orm\Zed\ProductPageSearch\Persistence\SpyProductAbstractPageSearch $productAbstractPageSearchEntity
     * @param \Generated\Shared\Transfer\ProductPageSearchTransfer $productPageSearchTransfer
     * @param string $storeName
     * @param string $localeName
     * @param array<\Spryker\Zed\ProductPageSearch\Dependency\Plugin\ProductPageDataExpanderInterface> $pageDataExpanderPlugins
     *
     * @return void
     */
    protected function storeProductAbstractPageSearchEntity(
        array $productAbstractLocalizedEntity,
        SpyProductAbstractPageSearch $productAbstractPageSearchEntity,
        ProductPageSearchTransfer $productPageSearchTransfer,
        string $storeName,
        string $localeName,
        array $pageDataExpanderPlugins,
    ): void {
        $productPageSearchTransfer->setStore($storeName);
        $productPageSearchTransfer->setLocale($localeName);

        $this->expandPageSearchTransferWithPlugins($pageDataExpanderPlugins, $productAbstractLocalizedEntity, $productPageSearchTransfer);

        $searchDocument = $this->productPageSearchMapper->mapToSearchData($productPageSearchTransfer);

        $this->add($productPageSearchTransfer, $searchDocument);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductPageSearchTransfer $productPageSearchTransfer
     * @param array<string, mixed> $searchDocument
     *
     * @return void
     */
    protected function add(ProductPageSearchTransfer $productPageSearchTransfer, array $searchDocument): void
    {
        $synchronizedData = $this->buildSynchronizedData($productPageSearchTransfer, $searchDocument, 'product_abstract');
        $this->synchronizedDataCollection[] = $synchronizedData;

        $this->synchronizedMessageCollection[] = $this->buildSynchronizedMessage($synchronizedData, 'product_abstract', ['type' => 'page']);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductPageSearchTransfer $productPageSearchTransfer
     * @param array<string, mixed> $data
     * @param string $resourceName
     *
     * @return array<string, mixed>
     */
    public function buildSynchronizedData(
        ProductPageSearchTransfer $productPageSearchTransfer,
        array $data,
        string $resourceName,
    ): array {
        $key = $this->generateResourceKey($data, (string)$productPageSearchTransfer->getIdProductAbstract(), $resourceName);
        $encodedData = json_encode($data);
        $data['key'] = $key;
        $data['data'] = $encodedData;
        $data['structured_data'] = json_encode($productPageSearchTransfer->toArray());
        $data['fk_product_abstract'] = $productPageSearchTransfer->getIdProductAbstract();

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

        $syncTransferData->setReference($keySuffix);
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
        array $params = [],
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
    public function write(): void
    {
        if (!$this->synchronizedDataCollection) {
            return;
        }

        $sql = $this->productAbstractPagePublisherCte->getSql();

        $con = Propel::getConnection();

        $stmt = $con->prepare($sql);

        $params = $this->productAbstractPagePublisherCte->buildParams($this->synchronizedDataCollection);

        $stmt->execute($params);
    }
}
