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
use Spryker\Zed\DataImport\DataImportConfig;
use Spryker\Zed\PriceProduct\Dependency\PriceProductEvents;
use Spryker\Zed\Product\Dependency\ProductEvents;

abstract class AbstractProductPriceBulkDataSetWriter implements DataSetWriterInterface
{
    protected const BULK_SIZE = ProductPriceHydratorStep::BULK_SIZE;

    protected const COLUMN_PRICE_TYPE = ProductPriceHydratorStep::COLUMN_PRICE_TYPE;
    protected const COLUMN_PRICE_DATA = ProductPriceHydratorStep::COLUMN_PRICE_DATA;
    protected const COLUMN_PRICE_DATA_CHECKSUM = ProductPriceHydratorStep::COLUMN_PRICE_DATA_CHECKSUM;
    protected const COLUMN_STORE = ProductPriceHydratorStep::COLUMN_STORE;
    protected const COLUMN_CURRENCY = ProductPriceHydratorStep::COLUMN_CURRENCY;

    /**
     * @var array
     */
    protected static $priceProductAbstractCollection = [];

    /**
     * @var array
     */
    protected static $priceProductConcreteCollection = [];

    /**
     * @var array<int>
     */
    protected static $productCurrencyIdsCollection = [];

    /**
     * @var array<int>
     */
    protected static $productStoreIdsCollection = [];

    /**
     * @var array<int>
     */
    protected static $productPriceTypeIdsCollection = [];

    /**
     * @var array<int>
     */
    protected static $productIds = [];

    /**
     * @var array<int>
     */
    protected static $priceProductStoreIds = [];

    /**
     * @var array<int>
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
     * @var \Spryker\Zed\DataImport\DataImportConfig
     */
    protected $dataImportConfig;

    /**
     * @param \Pyz\Zed\DataImport\Business\Model\ProductPrice\Writer\Sql\ProductPriceSqlInterface $productPriceSql
     * @param \Pyz\Zed\DataImport\Business\Model\PropelExecutorInterface $propelExecutor
     * @param \Pyz\Zed\DataImport\Business\Model\DataFormatter\DataImportDataFormatterInterface $dataFormatter
     * @param \Spryker\Zed\DataImport\DataImportConfig $dataImportConfig
     */
    public function __construct(
        ProductPriceSqlInterface $productPriceSql,
        PropelExecutorInterface $propelExecutor,
        DataImportDataFormatterInterface $dataFormatter,
        DataImportConfig $dataImportConfig
    ) {
        $this->productPriceSql = $productPriceSql;
        $this->propelExecutor = $propelExecutor;
        $this->dataFormatter = $dataFormatter;
        $this->dataImportConfig = $dataImportConfig;
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
            count(static::$priceProductAbstractCollection) >= static::BULK_SIZE ||
            count(static::$priceProductConcreteCollection) >= static::BULK_SIZE
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
                $productPriceItem[static::COLUMN_PRICE_TYPE],
                $productPriceItem[ProductPriceHydratorStep::KEY_SPY_PRODUCT_ABSTRACT],
                $productPriceItem[ProductPriceHydratorStep::KEY_PRICE_PRODUCT_STORES][0]
            );

            return;
        }

        static::$priceProductConcreteCollection[] = array_merge(
            $productPriceItem[static::COLUMN_PRICE_TYPE],
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
    abstract protected function persistProductPriceTypeCollection(array $priceProductCollection): void;

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
    abstract protected function prepareProductPriceTypeIdsCollection(array $priceProductConcreteCollection): void;

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
    abstract protected function prepareProductStoreIdsCollection(array $priceProductCollection): void;

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
    abstract protected function prepareProductCurrencyIdsCollection(array $priceProductCollection): void;

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
    abstract protected function prepareProductIdsCollection(
        array $priceProductCollection,
        string $tableName,
        string $productKey
    ): void;

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
    abstract protected function persistPriceProductEntities(
        string $productIdKey,
        string $productTable,
        string $productFkKey
    ): void;

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
    abstract protected function addPriceProductEvents(string $productIdKey, string $productFkKey): array;

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
    abstract protected function persistPriceProductStoreEntities(
        array $priceProductCollection,
        string $productTableName,
        string $productIdKey,
        string $productFkKey
    ): void;

    /**
     * @return void
     */
    abstract protected function persistPriceProductDefault(): void;

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
