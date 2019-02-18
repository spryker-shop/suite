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

        if (count(static::$priceProductAbstractCollection) >= ProductPriceHydratorStep::BULK_SIZE ||
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
        } else {
            static::$priceProductConcreteCollection[] = array_merge(
                $productPriceItem[ProductPriceHydratorStep::KEY_PRICE_TYPE],
                $productPriceItem[ProductPriceHydratorStep::KEY_PRODUCT],
                $productPriceItem[ProductPriceHydratorStep::KEY_PRICE_PRODUCT_STORES][0]
            );
        }
    }

    /**
     * @return void
     */
    protected function persistProductAbstractPriceTypeCollection(): void
    {
        $priceTypeCollection = $this->dataFormatter->getCollectionDataByKey(static::$priceProductAbstractCollection, ProductPriceHydratorStep::KEY_PRICE_TYPE_NAME);
        $priceTypeModeCollection = $this->dataFormatter->getCollectionDataByKey(static::$priceProductAbstractCollection, ProductPriceHydratorStep::KEY_PRICE_MODE_CONFIGURATION);
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
    protected function persistProductConcretePriceTypeCollection(): void
    {
        $priceTypeCollection = $this->dataFormatter->getCollectionDataByKey(static::$priceProductConcreteCollection, ProductPriceHydratorStep::KEY_PRICE_TYPE_NAME);
        $priceTypeModeCollection = $this->dataFormatter->getCollectionDataByKey(static::$priceProductConcreteCollection, ProductPriceHydratorStep::KEY_PRICE_MODE_CONFIGURATION);
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
        $priceTypeCollection = $this->dataFormatter->getCollectionDataByKey(static::$priceProductAbstractCollection, ProductPriceHydratorStep::KEY_PRICE_TYPE_NAME);
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
    protected function prepareProductConcretePriceTypeIdsCollection(): void
    {
        $priceTypeCollection = $this->dataFormatter->getCollectionDataByKey(static::$priceProductConcreteCollection, ProductPriceHydratorStep::KEY_PRICE_TYPE_NAME);
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
        $storeCollection = $this->dataFormatter->getCollectionDataByKey(static::$priceProductAbstractCollection, ProductPriceHydratorStep::KEY_STORE);
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
    protected function prepareProductConcreteStoreIdsCollection(): void
    {
        $storeCollection = $this->dataFormatter->getCollectionDataByKey(static::$priceProductConcreteCollection, ProductPriceHydratorStep::KEY_STORE);
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
        $currencyCollection = $this->dataFormatter->getCollectionDataByKey(static::$priceProductAbstractCollection, ProductPriceHydratorStep::KEY_CURRENCY);
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
    protected function prepareProductConcreteCurrencyIdsCollection(): void
    {
        $currencyCollection = $this->dataFormatter->getCollectionDataByKey(static::$priceProductConcreteCollection, ProductPriceHydratorStep::KEY_CURRENCY);
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
        $productAbstractSkuCollection = $this->dataFormatter->getCollectionDataByKey(static::$priceProductAbstractCollection, ProductPriceHydratorStep::KEY_SKU);
        $productSku = $this->dataFormatter->formatPostgresArray($productAbstractSkuCollection);
        $orderKey = $this->dataFormatter->formatPostgresArrayString(array_keys($productAbstractSkuCollection));

        $sql = $this->productPriceSql->convertProductSkuToId(
            SpyProductAbstractTableMap::TABLE_NAME,
            ProductPriceHydratorStep::KEY_ID_PRODUCT_ABSTRACT
        );

        $parameters = [
            $orderKey,
            $productSku,
        ];

        $result = $this->propelExecutor->execute($sql, $parameters);

        foreach ($result as $idProductAbstract) {
            static::$productIds[] = $idProductAbstract;
        }
    }

    /**
     * @return void
     */
    protected function prepareProductConcreteIdsCollection(): void
    {
        $productConcreteSkuCollection = $this->dataFormatter->getCollectionDataByKey(static::$priceProductConcreteCollection, ProductPriceHydratorStep::KEY_SKU);
        $productSku = $this->dataFormatter->formatPostgresArray($productConcreteSkuCollection);
        $orderKey = $this->dataFormatter->formatPostgresArrayString(array_keys($productConcreteSkuCollection));

        $sql = $this->productPriceSql->convertProductSkuToId(
            SpyProductTableMap::TABLE_NAME,
            ProductPriceHydratorStep::KEY_ID_PRODUCT
        );

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
        if (empty(static::$productIds)) {
            return;
        }

        $productCollection = $this->dataFormatter->getCollectionDataByKey(static::$productIds, ProductPriceHydratorStep::KEY_ID_PRODUCT_ABSTRACT);
        $product = $this->dataFormatter->formatPostgresArray($productCollection);
        $priceType = $this->dataFormatter->formatPostgresArrayString(
            $this->dataFormatter->getCollectionDataByKey(static::$productPriceTypeIdsCollection, ProductPriceHydratorStep::KEY_ID_PRICE_TYPE)
        );

        $priceProductAbstractParameters = [
            $product,
            $priceType,
        ];

        $sql = $this->productPriceSql->createPriceProductSQL(
            ProductPriceHydratorStep::KEY_ID_PRODUCT_ABSTRACT,
            ProductPriceHydratorStep::KEY_SPY_PRODUCT_ABSTRACT,
            ProductPriceHydratorStep::KEY_FK_PRODUCT_ABSTRACT
        );

        $this->propelExecutor->execute($sql, $priceProductAbstractParameters);
    }

    /**
     * @return void
     */
    protected function persistPriceProductConcreteEntities(): void
    {
        if (empty(static::$productIds)) {
            return;
        }

        $productCollection = $this->dataFormatter->getCollectionDataByKey(static::$productIds, ProductPriceHydratorStep::KEY_ID_PRODUCT);
        $product = $this->dataFormatter->formatPostgresArray($productCollection);
        $priceType = $this->dataFormatter->formatPostgresArrayString(
            $this->dataFormatter->getCollectionDataByKey(static::$productPriceTypeIdsCollection, ProductPriceHydratorStep::KEY_ID_PRICE_TYPE)
        );

        $priceProductConcreteParameters = [
            $product,
            $priceType,
        ];

        $sql = $this->productPriceSql->createPriceProductSQL(
            ProductPriceHydratorStep::KEY_ID_PRODUCT,
            ProductPriceHydratorStep::KEY_SPY_PRODUCT,
            ProductPriceHydratorStep::KEY_FK_PRODUCT
        );

        $this->propelExecutor->execute($sql, $priceProductConcreteParameters);
    }

    /**
     * @return void
     */
    protected function addPriceProductAbstractEvents(): void
    {
        $productCollection = $this->dataFormatter->getCollectionDataByKey(static::$productIds, ProductPriceHydratorStep::KEY_ID_PRODUCT_ABSTRACT);
        $product = $this->dataFormatter->formatPostgresArray($productCollection);
        $orderKey = $this->dataFormatter->formatPostgresArrayString(array_keys($productCollection));
        $priceType = $this->dataFormatter->formatPostgresArrayString(
            $this->dataFormatter->getCollectionDataByKey(static::$productPriceTypeIdsCollection, ProductPriceHydratorStep::KEY_ID_PRICE_TYPE)
        );

        $selectProductPriceSql = $this->productPriceSql->selectProductPriceSQL(
            ProductPriceHydratorStep::KEY_ID_PRODUCT_ABSTRACT,
            ProductPriceHydratorStep::KEY_FK_PRODUCT_ABSTRACT
        );

        $priceProductAbstractProductParameters = [
            $product,
            $priceType,
            $orderKey,
        ];

        $result = $this->propelExecutor->execute($selectProductPriceSql, $priceProductAbstractProductParameters);

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
        $productCollection = $this->dataFormatter->getCollectionDataByKey(static::$productIds, ProductPriceHydratorStep::KEY_ID_PRODUCT);
        $product = $this->dataFormatter->formatPostgresArray($productCollection);
        $orderKey = $this->dataFormatter->formatPostgresArrayString(array_keys($productCollection));
        $priceType = $this->dataFormatter->formatPostgresArrayString(
            $this->dataFormatter->getCollectionDataByKey(static::$productPriceTypeIdsCollection, ProductPriceHydratorStep::KEY_ID_PRICE_TYPE)
        );

        $selectProductPriceSql = $this->productPriceSql->selectProductPriceSQL(
            ProductPriceHydratorStep::KEY_ID_PRODUCT,
            ProductPriceHydratorStep::KEY_FK_PRODUCT
        );

        $priceProductAbstractProductParameters = [
            $product,
            $priceType,
            $orderKey,
        ];

        $result = $this->propelExecutor->execute($selectProductPriceSql, $priceProductAbstractProductParameters);

        foreach ($result as $columns) {
            static::$priceProductIds[][ProductPriceHydratorStep::KEY_ID_PRICE_PRODUCT] = $columns[ProductPriceHydratorStep::KEY_ID_PRICE_PRODUCT];
            DataImporterPublisher::addEvent(PriceProductEvents::PRICE_CONCRETE_PUBLISH, $columns[ProductPriceHydratorStep::KEY_ID_PRODUCT]);
        }
    }

    /**
     * @return void
     */
    protected function persistPriceProductAbstractStoreEntities(): void
    {
        $productCollection = $this->dataFormatter->getCollectionDataByKey(static::$productIds, ProductPriceHydratorStep::KEY_ID_PRODUCT_ABSTRACT);
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
            $this->dataFormatter->getCollectionDataByKey(static::$priceProductAbstractCollection, ProductPriceHydratorStep::KEY_PRICE_GROSS_DB)
        );
        $netPrice = $this->dataFormatter->formatPostgresArrayString(
            $this->dataFormatter->getCollectionDataByKey(static::$priceProductAbstractCollection, ProductPriceHydratorStep::KEY_PRICE_NET_DB)
        );
        $priceData = $this->dataFormatter->formatPostgresPriceDataString(
            $this->dataFormatter->getCollectionDataByKey(static::$priceProductAbstractCollection, ProductPriceHydratorStep::KEY_PRICE_DATA)
        );
        $checksum = $this->dataFormatter->formatPostgresArrayString(
            $this->dataFormatter->getCollectionDataByKey(static::$priceProductAbstractCollection, ProductPriceHydratorStep::KEY_PRICE_DATA_CHECKSUM)
        );

        static::$priceProductAbstractCollection = [];

        $priceProductAbstractStoreParameters = [
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
            ProductPriceHydratorStep::KEY_SPY_PRODUCT_ABSTRACT,
            ProductPriceHydratorStep::KEY_FK_PRODUCT_ABSTRACT,
            ProductPriceHydratorStep::KEY_ID_PRODUCT_ABSTRACT
        );

        $priceProductStoreIds = $this->propelExecutor->execute($sql, $priceProductAbstractStoreParameters);

        foreach ($priceProductStoreIds as $idPriceProductStore) {
            static::$priceProductStoreIds[] = $idPriceProductStore;
        }
    }

    /**
     * @return void
     */
    protected function persistPriceProductConcreteStoreEntities(): void
    {
        $productCollection = $this->dataFormatter->getCollectionDataByKey(static::$productIds, ProductPriceHydratorStep::KEY_ID_PRODUCT);
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
            $this->dataFormatter->getCollectionDataByKey(static::$priceProductConcreteCollection, ProductPriceHydratorStep::KEY_PRICE_GROSS_DB)
        );
        $netPrice = $this->dataFormatter->formatPostgresArrayString(
            $this->dataFormatter->getCollectionDataByKey(static::$priceProductConcreteCollection, ProductPriceHydratorStep::KEY_PRICE_NET_DB)
        );
        $priceData = $this->dataFormatter->formatPostgresPriceDataString(
            $this->dataFormatter->getCollectionDataByKey(static::$priceProductConcreteCollection, ProductPriceHydratorStep::KEY_PRICE_DATA)
        );
        $checksum = $this->dataFormatter->formatPostgresArrayString(
            $this->dataFormatter->getCollectionDataByKey(static::$priceProductConcreteCollection, ProductPriceHydratorStep::KEY_PRICE_DATA_CHECKSUM)
        );

        static::$priceProductConcreteCollection = [];

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
            ProductPriceHydratorStep::KEY_SPY_PRODUCT,
            ProductPriceHydratorStep::KEY_FK_PRODUCT,
            ProductPriceHydratorStep::KEY_ID_PRODUCT
        );

        $priceProductStoreIds = $this->propelExecutor->execute($sql, $priceProductConcreteStoreParameters);

        foreach ($priceProductStoreIds as $idPriceProductStore) {
            static::$priceProductStoreIds[] = $idPriceProductStore;
        }
    }

    /**
     * @return void
     */
    protected function persistPriceProductAbstractDefault(): void
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
    protected function persistPriceProductConcreteDefault(): void
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
        $this->persistProductAbstractPriceTypeCollection();
        $this->prepareProductAbstractPriceTypeIdsCollection();
        $this->prepareProductAbstractStoreIdsCollection();
        $this->prepareProductAbstractCurrencyIdsCollection();
        $this->prepareProductAbstractIdsCollection();
        $this->persistPriceProductAbstractEntities();
        $this->addPriceProductAbstractEvents();
        $this->persistPriceProductAbstractStoreEntities();
        $this->persistPriceProductAbstractDefault();

        $this->flushMemory();
    }

    /**
     * @return void
     */
    protected function flushPriceProductConcrete(): void
    {
        $this->persistProductConcretePriceTypeCollection();
        $this->prepareProductConcretePriceTypeIdsCollection();
        $this->prepareProductConcreteStoreIdsCollection();
        $this->prepareProductConcreteCurrencyIdsCollection();
        $this->prepareProductConcreteIdsCollection();
        $this->persistPriceProductConcreteEntities();
        $this->addPriceProductConcreteEvents();
        $this->persistPriceProductConcreteStoreEntities();
        $this->persistPriceProductConcreteDefault();

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
