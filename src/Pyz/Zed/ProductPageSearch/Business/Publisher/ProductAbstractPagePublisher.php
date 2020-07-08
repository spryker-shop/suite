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
     * @var array
     */
    protected $synchronizedDataCollection = [];

    /**
     * @var array
     */
    protected $synchronizedMessageCollection = [];

    /**
     * @param \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\ProductPageSearch\Dependency\Plugin\ProductPageDataExpanderInterface[] $pageDataExpanderPlugins
     * @param \Spryker\Zed\ProductPageSearchExtension\Dependency\Plugin\ProductPageDataLoaderPluginInterface[] $productPageDataLoaderPlugins
     * @param \Spryker\Zed\ProductPageSearch\Business\Mapper\ProductPageSearchMapperInterface $productPageSearchMapper
     * @param \Spryker\Zed\ProductPageSearch\Business\Model\ProductPageSearchWriterInterface $productPageSearchWriter
     * @param \Spryker\Zed\ProductPageSearch\ProductPageSearchConfig $productPageSearchConfig
     * @param \Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\ProductPageSearch\Business\Reader\AddToCartSkuReaderInterface $addToCartSkuReader
     * @param \Spryker\Service\Synchronization\SynchronizationServiceInterface $synchronizationService
     * @param \Spryker\Client\Queue\QueueClientInterface $queueClient
     */
    public function __construct(
        ProductPageSearchQueryContainerInterface $queryContainer,
        array $pageDataExpanderPlugins,
        array $productPageDataLoaderPlugins,
        ProductPageSearchMapperInterface $productPageSearchMapper,
        ProductPageSearchWriterInterface $productPageSearchWriter,
        ProductPageSearchConfig $productPageSearchConfig,
        ProductPageSearchToStoreFacadeInterface $storeFacade,
        AddToCartSkuReaderInterface $addToCartSkuReader,
        SynchronizationServiceInterface $synchronizationService,
        QueueClientInterface $queueClient
    ) {
        parent::__construct(
            $queryContainer,
            $pageDataExpanderPlugins,
            $productPageDataLoaderPlugins,
            $productPageSearchMapper,
            $productPageSearchWriter,
            $productPageSearchConfig,
            $storeFacade,
            $addToCartSkuReader
        );

        $this->synchronizationService = $synchronizationService;
        $this->queueClient = $queueClient;
    }

    /**
     * @param array $productAbstractLocalizedEntities
     * @param \Orm\Zed\ProductPageSearch\Persistence\SpyProductAbstractPageSearch[] $productAbstractPageSearchEntities
     * @param \Spryker\Zed\ProductPageSearch\Dependency\Plugin\ProductPageDataExpanderInterface[] $pageDataExpanderPlugins
     * @param \Generated\Shared\Transfer\ProductPageLoadTransfer $productPageLoadTransfer
     * @param bool|int $isRefresh
     *
     * @return void
     */
    protected function storeData(
        array $productAbstractLocalizedEntities,
        array $productAbstractPageSearchEntities,
        array $pageDataExpanderPlugins,
        ProductPageLoadTransfer $productPageLoadTransfer,
        $isRefresh = 0
    ) {
        $pairedEntities = $this->pairProductAbstractLocalizedEntitiesWithProductAbstractPageSearchEntities(
            $productAbstractLocalizedEntities,
            $productAbstractPageSearchEntities,
            $productPageLoadTransfer
        );

        foreach ($pairedEntities as $pairedEntity) {
            $productAbstractLocalizedEntity = $pairedEntity[static::PRODUCT_ABSTRACT_LOCALIZED_ENTITY];
            $productAbstractPageSearchEntity = $pairedEntity[static::PRODUCT_ABSTRACT_PAGE_SEARCH_ENTITY];

            if ($productAbstractLocalizedEntity === null || !$this->isActual($productAbstractLocalizedEntity)) {
                $this->deleteProductAbstractPageSearchEntity($productAbstractPageSearchEntity);

                continue;
            }

            $this->storeProductAbstractPageSearchEntity(
                $productAbstractLocalizedEntity,
                $productAbstractPageSearchEntity,
                $pairedEntity[static::STORE_NAME],
                $pairedEntity[static::LOCALE_NAME],
                $pageDataExpanderPlugins,
                $isRefresh
            );
        }

        $this->write();

        if ($this->synchronizedMessageCollection !== []) {
            $this->queueClient->sendMessages('sync.search.product', $this->synchronizedMessageCollection);
        }
    }

    /**
     * @param array $productAbstractLocalizedEntity
     * @param \Orm\Zed\ProductPageSearch\Persistence\SpyProductAbstractPageSearch $productAbstractPageSearchEntity
     * @param string $storeName
     * @param string $localeName
     * @param \Spryker\Zed\ProductPageSearch\Dependency\Plugin\ProductPageDataExpanderInterface[] $pageDataExpanderPlugins
     * @param bool|int $isRefresh
     *
     * @return void
     */
    protected function storeProductAbstractPageSearchEntity(
        array $productAbstractLocalizedEntity,
        SpyProductAbstractPageSearch $productAbstractPageSearchEntity,
        $storeName,
        $localeName,
        array $pageDataExpanderPlugins,
        $isRefresh = 0
    ) {
        $isRefresh = filter_var($isRefresh, FILTER_VALIDATE_BOOLEAN);
        $productPageSearchTransfer = $this->getProductPageSearchTransfer(
            $productAbstractLocalizedEntity,
            $productAbstractPageSearchEntity,
            $isRefresh
        );

        $productPageSearchTransfer->setStore($storeName);
        $productPageSearchTransfer->setLocale($localeName);

        $this->expandPageSearchTransferWithPlugins($pageDataExpanderPlugins, $productAbstractLocalizedEntity, $productPageSearchTransfer);

        $searchDocument = $this->productPageSearchMapper->mapToSearchData($productPageSearchTransfer);

        $this->add($productPageSearchTransfer, $searchDocument);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductPageSearchTransfer $productPageSearchTransfer
     * @param array $searchDocument
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
     * @param array $data
     * @param string $resourceName
     *
     * @return array
     */
    public function buildSynchronizedData(
        ProductPageSearchTransfer $productPageSearchTransfer,
        array $data,
        string $resourceName
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

        $syncTransferData->setReference($keySuffix);
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

        $sql = $this->getSql();

        $con = Propel::getConnection();
        $stmt = $con->prepare($sql);

        $foreignKeys = $this->formatPostgresArray(array_column($this->synchronizedDataCollection, 'fk_product_abstract'));
        $stores = $this->formatPostgresArrayString(array_column($this->synchronizedDataCollection, 'store'));
        $locales = $this->formatPostgresArrayString(array_column($this->synchronizedDataCollection, 'locale'));
        $data = $this->formatPostgresArrayFromJson(array_column($this->synchronizedDataCollection, 'data'));
        $structuredData = $this->formatPostgresArrayFromJson(array_column($this->synchronizedDataCollection, 'structured_data'));
        $keys = $this->formatPostgresArrayString(array_column($this->synchronizedDataCollection, 'key'));

        $params = [
            $foreignKeys,
            $stores,
            $locales,
            $data,
            $structuredData,
            $keys,
        ];

        $stmt->execute($params);
    }

    /**
     * @param array $values
     *
     * @return string
     */
    public function formatPostgresArray(array $values): string
    {
        if (!$values) {
            return '{null}';
        }

        $values = array_map(function ($value) {
            return ($value === null || $value === '') ? 'NULL' : $value;
        }, $values);

        return sprintf(
            '{%s}',
            pg_escape_string(implode(',', $values))
        );
    }

    /**
     * @param array $values
     *
     * @return string
     */
    public function formatPostgresArrayString(array $values): string
    {
        return sprintf(
            '{"%s"}',
            pg_escape_string(implode('","', $values))
        );
    }

    /**
     * @param array $values
     *
     * @return string
     */
    public function formatPostgresArrayFromJson(array $values): string
    {
        return sprintf(
            '[%s]',
            pg_escape_string(implode(',', $values))
        );
    }

    /**
     * @return string
     */
    protected function getSql()
    {
        $sql = <<<SQL
WITH records AS (
    SELECT
      input.fk_product_abstract,
      input.store,
      input.locale,
      input.data,
      input.structured_data,
      input.key,
      id_product_abstract_page_search
    FROM (
           SELECT
             unnest(? :: INTEGER []) AS fk_product_abstract,
             unnest(? :: VARCHAR []) AS store,
             unnest(? :: VARCHAR []) AS locale,
             json_array_elements(?) AS data,
             json_array_elements(?) AS structured_data,
             unnest(? :: VARCHAR []) AS key
         ) input
      LEFT JOIN spy_product_abstract_page_search ON spy_product_abstract_page_search.key = input.key
    ),
    updated AS (
    UPDATE spy_product_abstract_page_search
    SET
      fk_product_abstract = records.fk_product_abstract,
      store = records.store,
      locale = records.locale,
      data = records.data,
      structured_data = records.structured_data,
      key = records.key,
      updated_at = now()
    FROM records
    WHERE records.key = spy_product_abstract_page_search.key
    RETURNING spy_product_abstract_page_search.id_product_abstract_page_search
  ),
    inserted AS (
    INSERT INTO spy_product_abstract_page_search(
      id_product_abstract_page_search,
      fk_product_abstract,
      store,
      locale,
      data,
      structured_data,
      key,
      created_at,
      updated_at
    ) (
      SELECT
        nextval('spy_product_abstract_page_search_pk_seq'),
        fk_product_abstract,
        store,
        locale,
        data,
        structured_data,
        key,
        now(),
        now()
      FROM records
      WHERE id_product_abstract_page_search is null
    ) RETURNING spy_product_abstract_page_search.id_product_abstract_page_search
  )
SELECT updated.id_product_abstract_page_search FROM updated
UNION ALL
SELECT inserted.id_product_abstract_page_search FROM inserted;
SQL;

        return $sql;
    }
}
