<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\ProductPrice\Writer;

use Propel\Runtime\Propel;
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
     * @var array
     */
    protected static $priceTypeCollection = [];

    /**
     * @var array
     */
    protected static $productAbstractPriceCollection = [];

    /**
     * @var array
     */
    protected static $productConcretePriceCollection = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function write(DataSetInterface $dataSet): void
    {
        $this->collectProductPriceTypeCollection($dataSet);
        $this->collectProductPriceCollection($dataSet);

        if (count(static::$productAbstractPriceCollection) >= ProductPriceHydratorStep::BULK_SIZE ||
            count(static::$productConcretePriceCollection) >= ProductPriceHydratorStep::BULK_SIZE
        ) {
            $this->flush();
        }
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    protected function collectProductPriceTypeCollection(DataSetInterface $dataSet): void
    {
        static::$priceTypeCollection[] = $dataSet[ProductPriceHydratorStep::PRICE_TYPE_TRANSFER]->modifiedToArray();
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
            static::$productAbstractPriceCollection[] = $productPriceItem;
        } else {
            static::$productConcretePriceCollection[] = $productPriceItem;
        }
    }

    /**
     * @return void
     */
    protected function persistPriceTypeEntities(): void
    {
        $priceTypeName = $this->dataFormatter->formatPostgresArrayString(
            $this->dataFormatter->getCollectionDataByKey(static::$priceTypeCollection, ProductPriceHydratorStep::KEY_PRICE_TYPE_NAME)
        );
        $priceModeConfiguration = $this->dataFormatter->formatPostgresArrayString(
            [ProductPriceHydratorStep::KEY_DEFAULT_PRICE_MODE_CONFIGURATION]
        );

        $sql = $this->productPriceSql->createPriceTypeSQL();
        $parameters = [
            $priceTypeName,
            $priceModeConfiguration,
        ];

        $this->propelExecutor->execute($sql, $parameters);
    }

    /**
     * @return void
     */
    protected function persistProductAbstractEntities(): void
    {
        if (!empty(static::$productAbstractPriceCollection)) {
            $productsCollection = $this->dataFormatter->getCollectionDataByKey(
                static::$productAbstractPriceCollection,
                ProductPriceHydratorStep::KEY_SPY_PRODUCT_ABSTRACT
            );
            $priceProductStoreCollection = array_column(
                $this->dataFormatter->getCollectionDataByKey(static::$productAbstractPriceCollection, ProductPriceHydratorStep::KEY_PRICE_PRODUCT_STORES),
                '0'
            );
            $currencyCollection = $this->dataFormatter->getCollectionDataByKey($priceProductStoreCollection, ProductPriceHydratorStep::KEY_CURRENCY);
            $storeCollection = $this->dataFormatter->getCollectionDataByKey($priceProductStoreCollection, ProductPriceHydratorStep::KEY_STORE);
            $priceTypesCollection = $this->dataFormatter->getCollectionDataByKey(
                static::$productAbstractPriceCollection,
                ProductPriceHydratorStep::KEY_PRICE_TYPE
            );

            $sku = $this->dataFormatter->formatPostgresArrayString(
                $this->dataFormatter->getCollectionDataByKey($productsCollection, ProductPriceHydratorStep::KEY_SKU)
            );
            $priceTypeName = $this->dataFormatter->formatPostgresArrayString(
                $this->dataFormatter->getCollectionDataByKey($priceTypesCollection, ProductPriceHydratorStep::KEY_PRICE_TYPE_NAME)
            );
            $grossPrice = $this->dataFormatter->formatPostgresArrayString(
                $this->dataFormatter->getCollectionDataByKey($priceProductStoreCollection, ProductPriceHydratorStep::KEY_PRICE_GROSS_DB)
            );
            $netPrice = $this->dataFormatter->formatPostgresArrayString(
                $this->dataFormatter->getCollectionDataByKey($priceProductStoreCollection, ProductPriceHydratorStep::KEY_PRICE_NET_DB)
            );
            $currencyName = $this->dataFormatter->formatPostgresArrayString(
                $this->dataFormatter->getCollectionDataByKey($currencyCollection, ProductPriceHydratorStep::KEY_CURRENCY_NAME)
            );
            $storeName = $this->dataFormatter->formatPostgresArrayString(
                $this->dataFormatter->getCollectionDataByKey($storeCollection, ProductPriceHydratorStep::KEY_STORE_NAME)
            );

            $priceProductAbstractProductParameters = [$sku, $priceTypeName];
            $result = $this->persistPriceProductAbstractProductEntities($priceProductAbstractProductParameters);

            foreach ($result as $columns) {
                DataImporterPublisher::addEvent(PriceProductEvents::PRICE_ABSTRACT_PUBLISH, $columns[ProductPriceHydratorStep::KEY_ID_PRODUCT_ABSTRACT]);
                DataImporterPublisher::addEvent(ProductEvents::PRODUCT_ABSTRACT_PUBLISH, $columns[ProductPriceHydratorStep::KEY_ID_PRODUCT_ABSTRACT]);
            }

            $priceProductAbstractProductParameters = [$grossPrice, $netPrice, $currencyName, $storeName, $sku, $priceTypeName];
            $this->persistPriceProductStoreProductAbstractEntities($priceProductAbstractProductParameters);
        }
    }

    /**
     * @param array $priceProductAbstractProductParameters
     *
     * @return array
     */
    protected function persistPriceProductAbstractProductEntities(array $priceProductAbstractProductParameters): array
    {
        $sql = $this->productPriceSql->createProductPriceSQL(
            ProductPriceHydratorStep::KEY_ID_PRODUCT_ABSTRACT,
            ProductPriceHydratorStep::KEY_SPY_PRODUCT_ABSTRACT,
            ProductPriceHydratorStep::KEY_FK_PRODUCT_ABSTRACT
        );

        return $this->propelExecutor->execute($sql, $priceProductAbstractProductParameters);
    }

    /**
     * @param array $priceProductAbstractProductParameters
     *
     * @return void
     */
    protected function persistPriceProductStoreProductAbstractEntities(array $priceProductAbstractProductParameters): void
    {
        $sql = $this->productPriceSql->createPriceProductStoreSql(
            ProductPriceHydratorStep::KEY_SPY_PRODUCT_ABSTRACT,
            ProductPriceHydratorStep::KEY_FK_PRODUCT_ABSTRACT,
            ProductPriceHydratorStep::KEY_ID_PRODUCT_ABSTRACT
        );

        $this->persistPriceProductStore($sql, $priceProductAbstractProductParameters);
    }

    /**
     * @return void
     */
    protected function persistProductConcreteEntities(): void
    {
        if (!empty(static::$productConcretePriceCollection)) {
            $productCollection = $this->dataFormatter->getCollectionDataByKey(static::$productConcretePriceCollection, ProductPriceHydratorStep::KEY_PRODUCT);
            $priceProductStoreCollection = array_column(
                $this->dataFormatter->getCollectionDataByKey(static::$productConcretePriceCollection, ProductPriceHydratorStep::KEY_PRICE_PRODUCT_STORES),
                '0'
            );
            $priceTypeCollection = $this->dataFormatter->getCollectionDataByKey(static::$productConcretePriceCollection, ProductPriceHydratorStep::KEY_PRICE_TYPE);
            $currencyCollection = $this->dataFormatter->getCollectionDataByKey($priceProductStoreCollection, ProductPriceHydratorStep::KEY_CURRENCY);
            $storeCollection = $this->dataFormatter->getCollectionDataByKey($priceProductStoreCollection, ProductPriceHydratorStep::KEY_STORE);

            $sku = $this->dataFormatter->formatPostgresArrayString(
                $this->dataFormatter->getCollectionDataByKey($productCollection, ProductPriceHydratorStep::KEY_SKU)
            );
            $priceTypeName = $this->dataFormatter->formatPostgresArrayString(
                $this->dataFormatter->getCollectionDataByKey($priceTypeCollection, ProductPriceHydratorStep::KEY_PRICE_TYPE_NAME)
            );
            $grossPrice = $this->dataFormatter->formatPostgresArrayString(
                $this->dataFormatter->getCollectionDataByKey($priceProductStoreCollection, ProductPriceHydratorStep::KEY_PRICE_GROSS_DB)
            );
            $netPrice = $this->dataFormatter->formatPostgresArrayString(
                $this->dataFormatter->getCollectionDataByKey($priceProductStoreCollection, ProductPriceHydratorStep::KEY_PRICE_NET_DB)
            );
            $currencyName = $this->dataFormatter->formatPostgresArrayString(
                $this->dataFormatter->getCollectionDataByKey($currencyCollection, ProductPriceHydratorStep::KEY_CURRENCY_NAME)
            );
            $storeName = $this->dataFormatter->formatPostgresArrayString(
                $this->dataFormatter->getCollectionDataByKey($storeCollection, ProductPriceHydratorStep::KEY_STORE_NAME)
            );

            $priceProductConcreteParameters = [$sku, $priceTypeName];
            $result = $this->persistPriceProductConcreteProductEntities($priceProductConcreteParameters);

            foreach ($result as $columns) {
                DataImporterPublisher::addEvent(PriceProductEvents::PRICE_CONCRETE_PUBLISH, $columns[ProductPriceHydratorStep::KEY_ID_PRODUCT]);
            }

            $priceProductConcreteProductParameters = [$grossPrice, $netPrice, $currencyName, $storeName, $sku, $priceTypeName];
            $this->persistPriceProductStoreProductConcreteEntities($priceProductConcreteProductParameters);
        }
    }

    /**
     * @param array $priceProductConcreteParameters
     *
     * @return array
     */
    protected function persistPriceProductConcreteProductEntities(array $priceProductConcreteParameters): array
    {
        $sql = $this->productPriceSql->createProductPriceSQL(
            ProductPriceHydratorStep::KEY_ID_PRODUCT,
            ProductPriceHydratorStep::KEY_SPY_PRODUCT,
            ProductPriceHydratorStep::KEY_FK_PRODUCT
        );

        $con = Propel::getConnection();
        $stmt = $con->prepare($sql);
        $stmt->execute($priceProductConcreteParameters);

        return $this->propelExecutor->execute($sql, $priceProductConcreteParameters);
    }

    /**
     * @param array $priceProductConcreteProductParameters
     *
     * @return void
     */
    protected function persistPriceProductStoreProductConcreteEntities(array $priceProductConcreteProductParameters): void
    {
        $sql = $this->productPriceSql->createPriceProductStoreSql(
            ProductPriceHydratorStep::KEY_SPY_PRODUCT_ABSTRACT,
            ProductPriceHydratorStep::KEY_FK_PRODUCT_ABSTRACT,
            ProductPriceHydratorStep::KEY_ID_PRODUCT_ABSTRACT
        );

        $this->persistPriceProductStore($sql, $priceProductConcreteProductParameters);
    }

    /**
     * @param string $sql
     * @param array $parameters
     *
     * @return array
     */
    protected function persistProductPriceEntities(string $sql, array $parameters): array
    {
        return $this->propelExecutor->execute($sql, $parameters);
    }

    /**
     * @param string $sql
     * @param array $parameters
     *
     * @return void
     */
    protected function persistPriceProductStore(string $sql, array $parameters): void
    {
        $this->propelExecutor->execute($sql, $parameters);
    }

    /**
     * @return void
     */
    protected function persistPriceProductDefault(): void
    {
        $sql = $this->productPriceSql->createPriceProductDefaultSql();
        $this->propelExecutor->execute($sql, []);
    }

    /**
     * @return void
     */
    public function flush(): void
    {
        $this->persistPriceTypeEntities();
        $this->persistProductAbstractEntities();
        $this->persistProductConcreteEntities();
        $this->persistPriceProductDefault();

        DataImporterPublisher::triggerEvents();
        $this->flushMemory();
    }

    /**
     * @return void
     */
    protected function flushMemory(): void
    {
        static::$priceTypeCollection = [];
        static::$productAbstractPriceCollection = [];
        static::$productConcretePriceCollection = [];
    }
}
