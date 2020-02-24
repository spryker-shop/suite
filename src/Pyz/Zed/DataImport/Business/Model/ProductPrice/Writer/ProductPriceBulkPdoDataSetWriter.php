<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\ProductPrice\Writer;

use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Pyz\Zed\DataImport\Business\Model\DataFormatter\DataImportDataFormatterInterface;
use Pyz\Zed\DataImport\Business\Model\ProductPrice\ProductPriceHydratorStep;
use Pyz\Zed\DataImport\Business\Model\ProductPrice\Writer\Sql\ProductPriceSqlInterface;
use Pyz\Zed\DataImport\Business\Model\PropelExecutorInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface;
use Spryker\Zed\DataImport\Business\Model\Publisher\DataImporterPublisher;
use Spryker\Zed\PriceProduct\Dependency\PriceProductEvents;
use Spryker\Zed\Product\Dependency\ProductEvents;

class ProductPriceBulkPdoDataSetWriter implements DataSetWriterInterface
{
    /**
     * @var array
     */
    protected static $priceProductAbstractCollection = [];

    /**
     * @var array
     */
    protected static $priceProductConcreteCollection = [];

    /**
     * @var int[]
     */
    protected static $productCurrencyIdsCollection = [];

    /**
     * @var int[]
     */
    protected static $productStoreIdsCollection = [];

    /**
     * @var int[]
     */
    protected static $productPriceTypeIdsCollection = [];

    /**
     * @var int[]
     */
    protected static $productIds = [];

    /**
     * @var int[]
     */
    protected static $priceProductStoreIds = [];

    /**
     * @var int[]
     */
    protected static $priceProductIds = [];

    /**
     * @var \Pyz\Zed\DataImport\Business\Model\ProductPrice\Writer\Sql\ProductPriceSqlInterface
     */
    protected $productPriceSql;

    /**
     * @var \Pyz\Zed\DataImport\Business\Model\PropelExecutorInterface
     */
    protected $propelExecutor;

    /**
     * @var \Pyz\Zed\DataImport\Business\Model\DataFormatter\DataImportDataFormatterInterface
     */
    protected $dataFormatter;

    /**
     * @param \Pyz\Zed\DataImport\Business\Model\ProductPrice\Writer\Sql\ProductPriceSqlInterface $productPriceSql
     * @param \Pyz\Zed\DataImport\Business\Model\PropelExecutorInterface $propelExecutor
     * @param \Pyz\Zed\DataImport\Business\Model\DataFormatter\DataImportDataFormatterInterface $dataFormatter
     */
    public function __construct(
        ProductPriceSqlInterface $productPriceSql,
        PropelExecutorInterface $propelExecutor,
        DataImportDataFormatterInterface $dataFormatter
    ) {
        $this->productPriceSql = $productPriceSql;
        $this->propelExecutor = $propelExecutor;
        $this->dataFormatter = $dataFormatter;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function write(DataSetInterface $dataSet): void
    {
        $this->collectProductPriceCollection($dataSet);

        if (
            count(static::$priceProductAbstractCollection) >= ProductPriceHydratorStep::BULK_SIZE ||
            count(static::$priceProductConcreteCollection) >= ProductPriceHydratorStep::BULK_SIZE
        ) {
            $this->flush();
        }
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    protected function collectProductPriceCollection(DataSetInterface $dataSet): void
    {
        $productPriceItem = $dataSet[ProductPriceHydratorStep::PRICE_PRODUCT_TRANSFER]->modifiedToArray();

        if (array_key_exists(ProductPriceHydratorStep::KEY_SPY_PRODUCT_ABSTRACT, $productPriceItem)) {
            static::$priceProductAbstractCollection[] = array_merge(
                $productPriceItem[ProductPriceHydratorStep::KEY_PRICE_TYPE],
                $productPriceItem[ProductPriceHydratorStep::KEY_SPY_PRODUCT_ABSTRACT],
                $productPriceItem[ProductPriceHydratorStep::KEY_PRICE_PRODUCT_STORES][0]
            );

            return;
        }

        static::$priceProductConcreteCollection[] = array_merge(
            $productPriceItem[ProductPriceHydratorStep::KEY_PRICE_TYPE],
            $productPriceItem[ProductPriceHydratorStep::KEY_PRODUCT],
            $productPriceItem[ProductPriceHydratorStep::KEY_PRICE_PRODUCT_STORES][0]
        );
    }

    /**
     * @return void
     */
    protected function persistProductAbstractPriceTypeCollection(): void
    {
        $this->persistProductPriceTypeCollection(static::$priceProductAbstractCollection);
    }

    /**
     * @return void
     */
    protected function persistProductConcretePriceTypeCollection(): void
    {
        $this->persistProductPriceTypeCollection(static::$priceProductConcreteCollection);
    }

    /**
     * @param array $priceProductCollection
     *
     * @return void
     */
    protected function persistProductPriceTypeCollection(array $priceProductCollection): void
    {
        $priceTypeCollection = $this->dataFormatter->getCollectionDataByKey($priceProductCollection, ProductPriceHydratorStep::KEY_PRICE_TYPE_NAME);
        $priceTypeModeCollection = $this->dataFormatter->getCollectionDataByKey($priceProductCollection, ProductPriceHydratorStep::KEY_PRICE_MODE_CONFIGURATION);
        $priceType = $this->dataFormatter->formatPostgresArrayString($priceTypeCollection);
        $priceMode = $this->dataFormatter->formatPostgresArrayString($priceTypeModeCollection);
        $orderKey = $this->dataFormatter->formatPostgresArrayString(array_keys($priceTypeCollection));

        $sql = $this->productPriceSql->createPriceTypeSQL();
        $parameters = [
            $priceType,
            $priceMode,
            $orderKey,
        ];

        $this->propelExecutor->execute($sql, $parameters);
    }

    /**
     * @return void
     */
    protected function prepareProductAbstractPriceTypeIdsCollection(): void
    {
        $this->prepareProductPriceTypeIdsCollection(static::$priceProductAbstractCollection);
    }

    /**
     * @return void
     */
    protected function prepareProductConcretePriceTypeIdsCollection(): void
    {
        $this->prepareProductPriceTypeIdsCollection(static::$priceProductConcreteCollection);
    }

    /**
     * @param array $priceProductConcreteCollection
     *
     * @return void
     */
    protected function prepareProductPriceTypeIdsCollection(array $priceProductConcreteCollection): void
    {
        $priceTypeCollection = $this->dataFormatter->getCollectionDataByKey($priceProductConcreteCollection, ProductPriceHydratorStep::KEY_PRICE_TYPE_NAME);
        $priceType = $this->dataFormatter->formatPostgresArrayString($priceTypeCollection);
        $orderKey = $this->dataFormatter->formatPostgresArrayString(array_keys($priceTypeCollection));

        $sql = $this->productPriceSql->collectPriceTypes();

        $parameters = [
            $priceType,
            $orderKey,
        ];

        $priceTypeIds = $this->propelExecutor->execute($sql, $parameters);

        foreach ($priceTypeIds as $idPriceType) {
            static::$productPriceTypeIdsCollection[] = $idPriceType;
        }
    }

    /**
     * @return void
     */
    protected function prepareProductAbstractStoreIdsCollection(): void
    {
        $this->prepareProductStoreIdsCollection(static::$priceProductAbstractCollection);
    }

    /**
     * @return void
     */
    protected function prepareProductConcreteStoreIdsCollection(): void
    {
        $this->prepareProductStoreIdsCollection(static::$priceProductConcreteCollection);
    }

    /**
     * @param array $priceProductCollection
     *
     * @return void
     */
    protected function prepareProductStoreIdsCollection(array $priceProductCollection): void
    {
        $storeCollection = $this->dataFormatter->getCollectionDataByKey($priceProductCollection, ProductPriceHydratorStep::KEY_STORE);
        $storeNameCollection = $this->dataFormatter->getCollectionDataByKey($storeCollection, ProductPriceHydratorStep::KEY_STORE_NAME);
        $orderKey = $this->dataFormatter->formatPostgresArrayString(array_keys($storeNameCollection));
        $store = $this->dataFormatter->formatPostgresArrayString($storeNameCollection);

        $sql = $this->productPriceSql->convertStoreNameToId();

        $parameters = [
            $orderKey,
            $store,
        ];

        $result = $this->propelExecutor->execute($sql, $parameters);

        foreach ($result as $idStore) {
            static::$productStoreIdsCollection[] = $idStore;
        }
    }

    /**
     * @return void
     */
    protected function prepareProductAbstractCurrencyIdsCollection(): void
    {
        $this->prepareProductCurrencyIdsCollection(static::$priceProductAbstractCollection);
    }

    /**
     * @return void
     */
    protected function prepareProductConcreteCurrencyIdsCollection(): void
    {
        $this->prepareProductCurrencyIdsCollection(static::$priceProductConcreteCollection);
    }

    /**
     * @param array $priceProductCollection
     *
     * @return void
     */
    protected function prepareProductCurrencyIdsCollection(array $priceProductCollection): void
    {
        $currencyCollection = $this->dataFormatter->getCollectionDataByKey($priceProductCollection, ProductPriceHydratorStep::KEY_CURRENCY);
        $currencyNameCollection = $this->dataFormatter->getCollectionDataByKey($currencyCollection, ProductPriceHydratorStep::KEY_CURRENCY_NAME);
        $orderKey = $this->dataFormatter->formatPostgresArrayString(array_keys($currencyNameCollection));
        $currency = $this->dataFormatter->formatPostgresArrayString($currencyNameCollection);

        $sql = $this->productPriceSql->convertCurrencyNameToId();

        $parameters = [
            $orderKey,
            $currency,
        ];

        $result = $this->propelExecutor->execute($sql, $parameters);

        foreach ($result as $idCurrency) {
            static::$productCurrencyIdsCollection[] = $idCurrency;
        }
    }

    /**
     * @return void
     */
    protected function prepareProductAbstractIdsCollection(): void
    {
        $this->prepareProductIdsCollection(
            static::$priceProductAbstractCollection,
            SpyProductAbstractTableMap::TABLE_NAME,
            ProductPriceHydratorStep::KEY_ID_PRODUCT_ABSTRACT
        );
    }

    /**
     * @return void
     */
    protected function prepareProductConcreteIdsCollection(): void
    {
        $this->prepareProductIdsCollection(
            static::$priceProductConcreteCollection,
            SpyProductTableMap::TABLE_NAME,
            ProductPriceHydratorStep::KEY_ID_PRODUCT
        );
    }

    /**
     * @param array $priceProductCollection
     * @param string $tableName
     * @param string $productKey
     *
     * @return void
     */
    protected function prepareProductIdsCollection(
        array $priceProductCollection,
        string $tableName,
        string $productKey
    ): void {
        $productConcreteSkuCollection = $this->dataFormatter->getCollectionDataByKey($priceProductCollection, ProductPriceHydratorStep::KEY_SKU);
        $productSku = $this->dataFormatter->formatPostgresArray($productConcreteSkuCollection);
        $orderKey = $this->dataFormatter->formatPostgresArrayString(array_keys($productConcreteSkuCollection));

        $sql = $this->productPriceSql->convertProductSkuToId($tableName, $productKey);

        $parameters = [
            $orderKey,
            $productSku,
        ];

        $result = $this->propelExecutor->execute($sql, $parameters);

        foreach ($result as $idProduct) {
            static::$productIds[] = $idProduct;
        }
    }

    /**
     * @return void
     */
    protected function persistPriceProductAbstractEntities(): void
    {
        $this->persistPriceProductEntities(
            ProductPriceHydratorStep::KEY_ID_PRODUCT_ABSTRACT,
            ProductPriceHydratorStep::KEY_SPY_PRODUCT_ABSTRACT,
            ProductPriceHydratorStep::KEY_FK_PRODUCT_ABSTRACT
        );
    }

    /**
     * @return void
     */
    protected function persistPriceProductConcreteEntities(): void
    {
        $this->persistPriceProductEntities(
            ProductPriceHydratorStep::KEY_ID_PRODUCT,
            ProductPriceHydratorStep::KEY_SPY_PRODUCT,
            ProductPriceHydratorStep::KEY_FK_PRODUCT
        );
    }

    /**
     * @param string $productIdKey
     * @param string $productTable
     * @param string $productFkKey
     *
     * @return void
     */
    protected function persistPriceProductEntities(
        string $productIdKey,
        string $productTable,
        string $productFkKey
    ): void {
        if (!static::$productIds) {
            return;
        }

        $productCollection = $this->dataFormatter->getCollectionDataByKey(static::$productIds, $productIdKey);
        $product = $this->dataFormatter->formatPostgresArray($productCollection);
        $priceType = $this->dataFormatter->formatPostgresArrayString(
            $this->dataFormatter->getCollectionDataByKey(static::$productPriceTypeIdsCollection, ProductPriceHydratorStep::KEY_ID_PRICE_TYPE)
        );

        $priceProductAbstractParameters = [
            $product,
            $priceType,
        ];

        $sql = $this->productPriceSql->createPriceProductSQL(
            $productIdKey,
            $productTable,
            $productFkKey
        );

        $this->propelExecutor->execute($sql, $priceProductAbstractParameters);
    }

    /**
     * @return void
     */
    protected function addPriceProductAbstractEvents(): void
    {
        $result = $this->addPriceProductEvents(
            ProductPriceHydratorStep::KEY_ID_PRODUCT_ABSTRACT,
            ProductPriceHydratorStep::KEY_FK_PRODUCT_ABSTRACT
        );

        foreach ($result as $columns) {
            static::$priceProductIds[][ProductPriceHydratorStep::KEY_ID_PRICE_PRODUCT] = $columns[ProductPriceHydratorStep::KEY_ID_PRICE_PRODUCT];
            DataImporterPublisher::addEvent(PriceProductEvents::PRICE_ABSTRACT_PUBLISH, $columns[ProductPriceHydratorStep::KEY_ID_PRODUCT_ABSTRACT]);
            DataImporterPublisher::addEvent(ProductEvents::PRODUCT_ABSTRACT_PUBLISH, $columns[ProductPriceHydratorStep::KEY_ID_PRODUCT_ABSTRACT]);
        }
    }

    /**
     * @return void
     */
    protected function addPriceProductConcreteEvents(): void
    {
        $result = $this->addPriceProductEvents(
            ProductPriceHydratorStep::KEY_ID_PRODUCT,
            ProductPriceHydratorStep::KEY_FK_PRODUCT
        );

        foreach ($result as $columns) {
            static::$priceProductIds[][ProductPriceHydratorStep::KEY_ID_PRICE_PRODUCT] = $columns[ProductPriceHydratorStep::KEY_ID_PRICE_PRODUCT];
            DataImporterPublisher::addEvent(PriceProductEvents::PRICE_CONCRETE_PUBLISH, $columns[ProductPriceHydratorStep::KEY_ID_PRODUCT]);
        }
    }

    /**
     * @param string $productIdKey
     * @param string $productFkKey
     *
     * @return array
     */
    protected function addPriceProductEvents(string $productIdKey, string $productFkKey): array
    {
        $productCollection = $this->dataFormatter->getCollectionDataByKey(static::$productIds, $productIdKey);
        $product = $this->dataFormatter->formatPostgresArray($productCollection);
        $orderKey = $this->dataFormatter->formatPostgresArrayString(array_keys($productCollection));
        $priceType = $this->dataFormatter->formatPostgresArrayString(
            $this->dataFormatter->getCollectionDataByKey(static::$productPriceTypeIdsCollection, ProductPriceHydratorStep::KEY_ID_PRICE_TYPE)
        );

        $selectProductPriceSql = $this->productPriceSql->selectProductPriceSQL(
            $productIdKey,
            $productFkKey
        );

        $priceProductAbstractProductParameters = [
            $product,
            $priceType,
            $orderKey,
        ];

        $result = $this->propelExecutor->execute($selectProductPriceSql, $priceProductAbstractProductParameters);

        return $result;
    }

    /**
     * @return void
     */
    protected function persistPriceProductAbstractStoreEntities(): void
    {
        $this->persistPriceProductStoreEntities(
            static::$priceProductAbstractCollection,
            ProductPriceHydratorStep::KEY_SPY_PRODUCT_ABSTRACT,
            ProductPriceHydratorStep::KEY_FK_PRODUCT_ABSTRACT,
            ProductPriceHydratorStep::KEY_ID_PRODUCT_ABSTRACT
        );

        static::$priceProductAbstractCollection = [];
    }

    /**
     * @return void
     */
    protected function persistPriceProductConcreteStoreEntities(): void
    {
        $this->persistPriceProductStoreEntities(
            static::$priceProductConcreteCollection,
            ProductPriceHydratorStep::KEY_SPY_PRODUCT,
            ProductPriceHydratorStep::KEY_FK_PRODUCT,
            ProductPriceHydratorStep::KEY_ID_PRODUCT
        );

        static::$priceProductConcreteCollection = [];
    }

    /**
     * @param array $priceProductCollection
     * @param string $productTableName
     * @param string $productIdKey
     * @param string $productFkKey
     *
     * @return void
     */
    protected function persistPriceProductStoreEntities(
        array $priceProductCollection,
        string $productTableName,
        string $productIdKey,
        string $productFkKey
    ): void {
        $productCollection = $this->dataFormatter->getCollectionDataByKey(static::$productIds, $productIdKey);
        $product = $this->dataFormatter->formatPostgresArray($productCollection);
        $currency = $this->dataFormatter->formatPostgresArrayString(
            $this->dataFormatter->getCollectionDataByKey(static::$productCurrencyIdsCollection, ProductPriceHydratorStep::KEY_ID_CURRENCY)
        );
        $store = $this->dataFormatter->formatPostgresArrayString(
            $this->dataFormatter->getCollectionDataByKey(static::$productStoreIdsCollection, ProductPriceHydratorStep::KEY_ID_STORE)
        );
        $productPrice = $this->dataFormatter->formatPostgresArrayString(
            $this->dataFormatter->getCollectionDataByKey(static::$priceProductIds, ProductPriceHydratorStep::KEY_ID_PRICE_PRODUCT)
        );
        $grossPrice = $this->dataFormatter->formatPostgresArrayString(
            $this->dataFormatter->getCollectionDataByKey($priceProductCollection, ProductPriceHydratorStep::KEY_PRICE_GROSS_DB)
        );
        $netPrice = $this->dataFormatter->formatPostgresArrayString(
            $this->dataFormatter->getCollectionDataByKey($priceProductCollection, ProductPriceHydratorStep::KEY_PRICE_NET_DB)
        );
        $priceData = $this->dataFormatter->formatPostgresPriceDataString(
            $this->dataFormatter->getCollectionDataByKey($priceProductCollection, ProductPriceHydratorStep::KEY_PRICE_DATA)
        );
        $checksum = $this->dataFormatter->formatPostgresArrayString(
            $this->dataFormatter->getCollectionDataByKey($priceProductCollection, ProductPriceHydratorStep::KEY_PRICE_DATA_CHECKSUM)
        );

        $priceProductConcreteStoreParameters = [
            $store,
            $currency,
            $product,
            $productPrice,
            $grossPrice,
            $netPrice,
            $priceData,
            $checksum,
        ];

        $sql = $this->productPriceSql->createPriceProductStoreSql(
            $productTableName,
            $productFkKey,
            $productIdKey
        );

        $priceProductStoreIds = $this->propelExecutor->execute($sql, $priceProductConcreteStoreParameters);

        foreach ($priceProductStoreIds as $idPriceProductStore) {
            static::$priceProductStoreIds[] = $idPriceProductStore;
        }
    }

    /**
     * @return void
     */
    protected function persistPriceProductDefault(): void
    {
        $priceProductStoreIds = $this->dataFormatter->formatPostgresArray(
            $this->dataFormatter->getCollectionDataByKey(static::$priceProductStoreIds, ProductPriceHydratorStep::KEY_ID_PRICE_PRODUCT_STORE)
        );

        $parameters = [
            $priceProductStoreIds,
        ];

        $sql = $this->productPriceSql->createPriceProductDefaultSql();

        $this->propelExecutor->execute($sql, $parameters);
    }

    /**
     * @return void
     */
    public function flush(): void
    {
        $this->flushPriceProductAbstract();
        $this->flushPriceProductConcrete();

        DataImporterPublisher::triggerEvents();
    }

    /**
     * @return void
     */
    protected function flushPriceProductAbstract(): void
    {
        if (!static::$priceProductAbstractCollection) {
            return;
        }

        $this->persistProductAbstractPriceTypeCollection();
        $this->prepareProductAbstractPriceTypeIdsCollection();
        $this->prepareProductAbstractStoreIdsCollection();
        $this->prepareProductAbstractCurrencyIdsCollection();
        $this->prepareProductAbstractIdsCollection();
        $this->persistPriceProductAbstractEntities();
        $this->addPriceProductAbstractEvents();
        $this->persistPriceProductAbstractStoreEntities();
        $this->persistPriceProductDefault();

        $this->flushMemory();
    }

    /**
     * @return void
     */
    protected function flushPriceProductConcrete(): void
    {
        if (!static::$priceProductConcreteCollection) {
            return;
        }

        $this->persistProductConcretePriceTypeCollection();
        $this->prepareProductConcretePriceTypeIdsCollection();
        $this->prepareProductConcreteStoreIdsCollection();
        $this->prepareProductConcreteCurrencyIdsCollection();
        $this->prepareProductConcreteIdsCollection();
        $this->persistPriceProductConcreteEntities();
        $this->addPriceProductConcreteEvents();
        $this->persistPriceProductConcreteStoreEntities();
        $this->persistPriceProductDefault();

        $this->flushMemory();
    }

    /**
     * @return void
     */
    protected function flushMemory(): void
    {
        static::$productCurrencyIdsCollection = [];
        static::$productStoreIdsCollection = [];
        static::$productPriceTypeIdsCollection = [];
        static::$productIds = [];
        static::$priceProductStoreIds = [];
        static::$priceProductIds = [];
    }
}
