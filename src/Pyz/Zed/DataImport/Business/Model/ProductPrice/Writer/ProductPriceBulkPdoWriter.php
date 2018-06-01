<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\ProductPrice\Writer;

use Propel\Runtime\Propel;
use Pyz\Zed\DataImport\Business\Model\AbstractBulkPdoWriter\AbstractBulkPdoWriterTrait;
use Pyz\Zed\DataImport\Business\Model\ProductPrice\ProductPriceHydratorStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\DataImport\Business\Model\Publisher\DataImporterPublisher;
use Spryker\Zed\DataImport\Business\Model\Writer\FlushInterface;
use Spryker\Zed\DataImport\Business\Model\Writer\WriterInterface;
use Spryker\Zed\PriceProduct\Dependency\PriceProductEvents;
use Spryker\Zed\Product\Dependency\ProductEvents;

class ProductPriceBulkPdoWriter extends DataImporterPublisher implements WriterInterface, FlushInterface
{
    use AbstractBulkPdoWriterTrait;

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
        $priceTypeName = $this->formatPostgresArrayString(
            array_column(static::$priceTypeCollection, ProductPriceHydratorStep::KEY_PRICE_TYPE_NAME)
        );
        $priceModeConfiguration = $this->formatPostgresArrayString(
            [ProductPriceHydratorStep::KEY_DEFAULT_PRICE_MODE_CONFIGURATION]
        );

        $sql = $this->createPriceTypeSQL();

        $con = Propel::getConnection();
        $stmt = $con->prepare($sql);
        $stmt->execute([
            $priceTypeName,
            $priceModeConfiguration,
        ]);
    }

    /**
     * @return void
     */
    protected function persistProductAbstractEntities(): void
    {
        if (!empty(static::$productAbstractPriceCollection)) {
            $product = array_column(static::$productAbstractPriceCollection, ProductPriceHydratorStep::KEY_SPY_PRODUCT_ABSTRACT);
            $sku = $this->formatPostgresArrayString(array_column($product, ProductPriceHydratorStep::KEY_SKU));
            $priceType = array_column(static::$productAbstractPriceCollection, ProductPriceHydratorStep::KEY_PRICE_TYPE);
            $priceTypeName = $this->formatPostgresArrayString(array_column($priceType, ProductPriceHydratorStep::KEY_PRICE_TYPE_NAME));
            $priceProductStore = array_column(array_column(static::$productAbstractPriceCollection, ProductPriceHydratorStep::KEY_PRICE_PRODUCT_STORES), '0');
            $grossPrice = $this->formatPostgresArrayString(array_column($priceProductStore, ProductPriceHydratorStep::KEY_PRICE_GROSS_DB));
            $netPrice = $this->formatPostgresArrayString(array_column($priceProductStore, ProductPriceHydratorStep::KEY_PRICE_NET_DB));
            $currency = array_column($priceProductStore, ProductPriceHydratorStep::KEY_CURRENCY);
            $store = array_column($priceProductStore, ProductPriceHydratorStep::KEY_STORE);
            $currencyName = $this->formatPostgresArrayString(array_column($currency, ProductPriceHydratorStep::KEY_CURRENCY_NAME));
            $storeName = $this->formatPostgresArrayString(array_column($store, ProductPriceHydratorStep::KEY_STORE_NAME));

            $priceProductAbstractProductParameters = [$sku, $priceTypeName];
            $result = $this->persistPriceProductAbstractProductEntities($priceProductAbstractProductParameters);

            foreach ($result as $columns) {
                $this->addEvent(PriceProductEvents::PRICE_ABSTRACT_PUBLISH, $columns[ProductPriceHydratorStep::KEY_ID_PRODUCT_ABSTRACT]);
                $this->addEvent(ProductEvents::PRODUCT_ABSTRACT_PUBLISH, $columns[ProductPriceHydratorStep::KEY_ID_PRODUCT_ABSTRACT]);
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
        $sql = $this->createProductPriceSQL(
            ProductPriceHydratorStep::KEY_ID_PRODUCT_ABSTRACT,
            ProductPriceHydratorStep::KEY_SPY_PRODUCT_ABSTRACT,
            ProductPriceHydratorStep::KEY_FK_PRODUCT_ABSTRACT
        );

        $con = Propel::getConnection();
        $stmt = $con->prepare($sql);
        $stmt->execute($priceProductAbstractProductParameters);

        return $stmt->fetchAll();
    }

    /**
     * @param array $priceProductAbstractProductParameters
     *
     * @return void
     */
    protected function persistPriceProductStoreProductAbstractEntities(array $priceProductAbstractProductParameters): void
    {
        $sql = $this->createPriceProductStoreSql(
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
            $product = array_column(static::$productConcretePriceCollection, ProductPriceHydratorStep::KEY_PRODUCT);
            $sku = $this->formatPostgresArrayString(array_column($product, ProductPriceHydratorStep::KEY_SKU));
            $priceType = array_column(static::$productConcretePriceCollection, ProductPriceHydratorStep::KEY_PRICE_TYPE);
            $priceTypeName = $this->formatPostgresArrayString(array_column($priceType, ProductPriceHydratorStep::KEY_PRICE_TYPE_NAME));
            $priceProductStore = array_column(array_column(static::$productConcretePriceCollection, ProductPriceHydratorStep::KEY_PRICE_PRODUCT_STORES), '0');
            $grossPrice = $this->formatPostgresArrayString(array_column($priceProductStore, ProductPriceHydratorStep::KEY_PRICE_GROSS_DB));
            $netPrice = $this->formatPostgresArrayString(array_column($priceProductStore, ProductPriceHydratorStep::KEY_PRICE_NET_DB));
            $currency = array_column($priceProductStore, ProductPriceHydratorStep::KEY_CURRENCY);
            $store = array_column($priceProductStore, ProductPriceHydratorStep::KEY_STORE);
            $currencyName = $this->formatPostgresArrayString(array_column($currency, ProductPriceHydratorStep::KEY_CURRENCY_NAME));
            $storeName = $this->formatPostgresArrayString(array_column($store, ProductPriceHydratorStep::KEY_STORE_NAME));

            $priceProductConcreteParameters = [$sku, $priceTypeName];
            $result = $this->persistPriceProductConcreteProductEntities($priceProductConcreteParameters);

            foreach ($result as $columns) {
                $this->addEvent(PriceProductEvents::PRICE_CONCRETE_PUBLISH, $columns[ProductPriceHydratorStep::KEY_ID_PRODUCT]);
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
        $sql = $this->createProductPriceSQL(
            ProductPriceHydratorStep::KEY_ID_PRODUCT,
            ProductPriceHydratorStep::KEY_SPY_PRODUCT,
            ProductPriceHydratorStep::KEY_FK_PRODUCT
        );

        $con = Propel::getConnection();
        $stmt = $con->prepare($sql);
        $stmt->execute($priceProductConcreteParameters);

        return $stmt->fetchAll();
    }

    /**
     * @param array $priceProductConcreteProductParameters
     *
     * @return void
     */
    protected function persistPriceProductStoreProductConcreteEntities(array $priceProductConcreteProductParameters): void
    {
        $sql = $this->createPriceProductStoreSql(
            ProductPriceHydratorStep::KEY_SPY_PRODUCT_ABSTRACT,
            ProductPriceHydratorStep::KEY_FK_PRODUCT_ABSTRACT,
            ProductPriceHydratorStep::KEY_ID_PRODUCT_ABSTRACT
        );

        $this->persistPriceProductStore($sql, $priceProductConcreteProductParameters);
    }

    /**
     * @param string $sql
     * @param array $attributes
     *
     * @return array
     */
    protected function persistProductPriceEntities(string $sql, array $attributes): array
    {
        $con = Propel::getConnection();
        $stmt = $con->prepare($sql);
        $stmt->execute($attributes);

        return $stmt->fetchAll();
    }

    /**
     * @param string $sql
     * @param array $attributes
     *
     * @return void
     */
    protected function persistPriceProductStore(string $sql, array $attributes): void
    {
        $con = Propel::getConnection();
        $stmt = $con->prepare($sql);
        $stmt->execute($attributes);
    }

    /**
     * @param string $idProduct
     * @param string $productTable
     * @param string $productFkKey
     *
     * @return string
     */
    protected function createProductPriceSQL(string $idProduct, string $productTable, string $productFkKey): string
    {
        $sql = sprintf(
            "WITH records AS (
    SELECT
      input.sku,
      input.price_type_name,
      %1\$s,
      id_price_type,
      id_price_product as idPriceProduct
    FROM (
           SELECT
             unnest(?::VARCHAR []) AS sku,
             unnest(?::VARCHAR[]) AS price_type_name
         ) input
      INNER JOIN %2\$s ON %2\$s.sku = input.sku    
      INNER JOIN spy_price_type ON spy_price_type.name = input.price_type_name
      LEFT JOIN spy_price_product ON (spy_price_product.fk_price_type = id_price_type AND spy_price_product.%3\$s = %1\$s)
),
    updated AS (
    UPDATE spy_price_product
    SET
      %3\$s = records.%1\$s,
      fk_price_type = records.id_price_type
    FROM records
    WHERE records.%1\$s = spy_price_product.%3\$s AND records.id_price_type = spy_price_product.fk_price_type
    RETURNING id_price_product, %3\$s as %1\$s
  ),
    inserted AS(
    INSERT INTO spy_price_product (
      id_price_product,
      fk_price_type,
      %3\$s
    ) (
      SELECT
        nextval('spy_price_product_pk_seq'),
        id_price_type,
        %1\$s
    FROM records
    WHERE idPriceProduct is null
  ) ON CONFLICT (fk_price_type, %3\$s) DO NOTHING 
     RETURNING id_price_product, %3\$s as %1\$s
  )
SELECT updated.id_price_product,%1\$s FROM updated UNION ALL SELECT inserted.id_price_product,%1\$s FROM inserted;",
            $idProduct,
            $productTable,
            $productFkKey
        );

        return $sql;
    }

    /**
     * @return string
     */
    protected function createPriceTypeSQL(): string
    {
        $sql = "WITH records AS (
    SELECT
      input.name,
      input.price_mode_configuration,
      id_price_type as idPriceType
    FROM (
           SELECT
             unnest(?::VARCHAR []) AS name,
             unnest(?::INTEGER[]) AS price_mode_configuration
         ) input    
      LEFT JOIN spy_price_type ON (spy_price_type.name = input.name AND spy_price_type.price_mode_configuration = input.price_mode_configuration)
),
    updated AS (
    UPDATE spy_price_type
    SET
      name = records.name,
      price_mode_configuration = records.price_mode_configuration
    FROM records
    WHERE records.name = spy_price_type.name AND records.price_mode_configuration = spy_price_type.price_mode_configuration
  ),
    inserted AS(
    INSERT INTO spy_price_type (
      id_price_type,
      name,
      price_mode_configuration
    ) (
      SELECT
        nextval('spy_price_type_pk_seq'),
        name,
        price_mode_configuration
    FROM records
    WHERE idPriceType is null
  ) ON CONFLICT (name) DO NOTHING
  )
SELECT 1;";

        return $sql;
    }

    /**
     * @param string $tableName
     * @param string $foreignKey
     * @param string $idProduct
     *
     * @return string
     */
    protected function createPriceProductStoreSql(string $tableName, string $foreignKey, string $idProduct): string
    {
        $sql = sprintf("WITH records AS (
    SELECT
      input.gross_price,
      input.net_price,
      input.currency,
      input.store,
      input.sku,
      input.price_type,
      id_currency,
      id_store,
      %3\$s,
      id_price_type,
      id_price_product,
      id_price_product_store as idProductStore
      FROM (
           SELECT
             unnest(?::INTEGER []) AS gross_price,
             unnest(?::INTEGER[]) AS net_price,
             unnest(?::VARCHAR[]) AS currency,
             unnest(?::VARCHAR[]) AS store,
             unnest(?::VARCHAR[]) AS sku,
             unnest(?::VARCHAR[]) AS price_type
         ) input  
      INNER JOIN %1\$s ON %1\$s.sku = input.sku
      INNER JOIN spy_price_type ON spy_price_type.name = input.price_type
      INNER JOIN spy_price_product ON (spy_price_product.%2\$s = %3\$s AND spy_price_product.fk_price_type = id_price_type) 
      INNER JOIN spy_currency ON spy_currency.code = input.currency
      INNER JOIN spy_store ON spy_store.name = input.store
      LEFT JOIN spy_price_product_store ON (spy_price_product_store.fk_price_product = id_price_product AND spy_price_product_store.fk_currency = id_currency AND spy_price_product_store.fk_store = id_store)
),
 updated AS (
    UPDATE spy_price_product_store
    SET
      gross_price = records.gross_price,
      net_price = records.net_price
    FROM records
    WHERE spy_price_product_store.fk_price_product = records.id_price_product AND 
    spy_price_product_store.fk_store = records.id_store AND
    spy_price_product_store.fk_currency = records.id_currency
  ),
    inserted AS(
    INSERT INTO spy_price_product_store (
      id_price_product_store,
      fk_currency,
      fk_store,
      gross_price,
      net_price,
      fk_price_product
    ) (
      SELECT
        nextval('spy_price_product_store_pk_seq'),
        id_currency,
        id_store,
        gross_price,
        net_price,
        id_price_product
    FROM records
    WHERE idProductStore is null
  ) ON CONFLICT (fk_currency, fk_price_product, fk_store) DO NOTHING
  )
SELECT 1;", $tableName, $foreignKey, $idProduct);

        return $sql;
    }

    /**
     * @return void
     */
    public function flush(): void
    {
        $this->persistPriceTypeEntities();
        $this->persistProductAbstractEntities();
        $this->persistProductConcreteEntities();
        $this->triggerEvents();
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
