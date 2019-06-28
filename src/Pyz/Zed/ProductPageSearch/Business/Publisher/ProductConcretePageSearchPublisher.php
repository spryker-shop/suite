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
use Spryker\Client\Queue\QueueClientInterface;
use Spryker\Service\Synchronization\SynchronizationServiceInterface;
use Spryker\Zed\ProductPageSearch\Business\ProductConcretePageSearchReader\ProductConcretePageSearchReaderInterface;
use Spryker\Zed\ProductPageSearch\Business\ProductConcretePageSearchWriter\ProductConcretePageSearchWriterInterface;
use Spryker\Zed\ProductPageSearch\Business\Publisher\ProductConcretePageSearchPublisher as SprykerProductConcretePageSearchPublisher;
use Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToProductInterface;
use Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToSearchInterface;
use Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToStoreFacadeInterface;
use Spryker\Zed\ProductPageSearch\Dependency\Service\ProductPageSearchToUtilEncodingInterface;

/**
 * @example
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
     * @param \Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToSearchInterface $searchFacade
     * @param \Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\ProductPageSearchExtension\Dependency\Plugin\ProductConcretePageDataExpanderPluginInterface[] $pageDataExpanderPlugins
     * @param \Spryker\Service\Synchronization\SynchronizationServiceInterface $synchronizationService
     * @param \Spryker\Client\Queue\QueueClientInterface $queueClient
     */
    public function __construct(
        ProductConcretePageSearchReaderInterface $productConcretePageSearchReader,
        ProductConcretePageSearchWriterInterface $productConcretePageSearchWriter,
        ProductPageSearchToProductInterface $productFacade,
        ProductPageSearchToUtilEncodingInterface $utilEncoding,
        ProductPageSearchToSearchInterface $searchFacade,
        ProductPageSearchToStoreFacadeInterface $storeFacade,
        array $pageDataExpanderPlugins,
        SynchronizationServiceInterface $synchronizationService,
        QueueClientInterface $queueClient
    ) {
        parent::__construct(
            $productConcretePageSearchReader,
            $productConcretePageSearchWriter,
            $productFacade,
            $utilEncoding,
            $searchFacade,
            $storeFacade,
            $pageDataExpanderPlugins
        );

        $this->synchronizationService = $synchronizationService;
        $this->queueClient = $queueClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer[] $productConcreteTransfers
     * @param \Generated\Shared\Transfer\ProductConcretePageSearchTransfer[] $productConcretePageSearchTransfers
     *
     * @return void
     */
    protected function executePublishTransaction(array $productConcreteTransfers, array $productConcretePageSearchTransfers): void
    {
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
    protected function add(ProductConcretePageSearchTransfer $productPageSearchTransfer)
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
    public function buildSynchronizedData(ProductConcretePageSearchTransfer $productPageSearchTransfer, string $resourceName): array
    {
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
    protected function generateResourceKey(ProductConcretePageSearchTransfer $productPageSearchTransfer, string $resourceName)
    {
        $syncTransferData = new SynchronizationDataTransfer();
        $syncTransferData->setStore($productPageSearchTransfer->getStore());
        $syncTransferData->setLocale($productPageSearchTransfer->getLocale());
        $syncTransferData->setReference($productPageSearchTransfer->getFkProduct());
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
    public function buildSynchronizedMessage(array $data, string $resourceName, array $params = []): QueueSendMessageTransfer
    {
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

        $foreignKeys = $this->formatPostgresArray(array_column($this->synchronizedDataCollection, 'fk_product'));
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
        if (is_array($values) && empty($values)) {
            return '{null}';
        }

        $values = array_map(function ($value) {
            return ($value === null || $value === "") ? "NULL" : $value;
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
      input.fk_product,
      input.store,
      input.locale,
      input.data,
      input.structured_data,
      input.key,
      id_product_concrete_page_search
    FROM (
           SELECT 
             unnest(? :: INTEGER []) AS fk_product,
             unnest(? :: VARCHAR []) AS store,
             unnest(? :: VARCHAR []) AS locale,
             json_array_elements(?) AS data,
             json_array_elements(?) AS structured_data,
             unnest(? :: VARCHAR []) AS key
         ) input
      LEFT JOIN spy_product_concrete_page_search ON spy_product_concrete_page_search.key = input.key
    ),
    updated AS (
    UPDATE spy_product_concrete_page_search
    SET 
      fk_product = records.fk_product,
      store = records.store,
      locale = records.locale,
      data = records.data,
      structured_data = records.structured_data,
      key = records.key,
      updated_at = now()
    FROM records
    WHERE records.key = spy_product_concrete_page_search.key
    RETURNING spy_product_concrete_page_search.id_product_concrete_page_search
  ),
    inserted AS (
    INSERT INTO spy_product_concrete_page_search(
      id_product_concrete_page_search, 
      fk_product,
      store,
      locale,
      data,
      structured_data,
      key,
      created_at,
      updated_at
    ) (
      SELECT
        nextval('spy_product_concrete_page_search_pk_seq'), 
        fk_product,
        store,
        locale,
        data,
        structured_data,
        key,
        now(),
        now()
      FROM records
      WHERE id_product_concrete_page_search is null
    ) RETURNING spy_product_concrete_page_search.id_product_concrete_page_search
  )
SELECT updated.id_product_concrete_page_search FROM updated
UNION ALL
SELECT inserted.id_product_concrete_page_search FROM inserted;
SQL;

        return $sql;
    }
}
