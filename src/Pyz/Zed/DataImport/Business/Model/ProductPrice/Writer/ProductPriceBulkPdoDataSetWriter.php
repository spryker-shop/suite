<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\ProductPrice\Writer;

use Pyz\Zed\DataImport\Business\Model\ProductPrice\ProductPriceHydratorStep;
use Spryker\Zed\DataImport\Business\Model\ApplicableDatabaseEngineAwareInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface;
use Spryker\Zed\Propel\PropelConfig;

class ProductPriceBulkPdoDataSetWriter extends AbstractProductPriceBulkDataSetWriter implements DataSetWriterInterface, ApplicableDatabaseEngineAwareInterface
{
    /**
     * @return bool
     */
    public function isApplicable(): bool
    {
        return $this->dataImportConfig->getCurrentDatabaseEngine() === PropelConfig::DB_ENGINE_PGSQL;
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
     * @param array $priceProductCollection
     *
     * @return void
     */
    protected function prepareProductStoreIdsCollection(array $priceProductCollection): void
    {
        $storeCollection = $this->dataFormatter->getCollectionDataByKey($priceProductCollection, static::COLUMN_STORE);
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
     * @param array $priceProductCollection
     *
     * @return void
     */
    protected function prepareProductCurrencyIdsCollection(array $priceProductCollection): void
    {
        $currencyCollection = $this->dataFormatter->getCollectionDataByKey($priceProductCollection, static::COLUMN_CURRENCY);
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
            $this->dataFormatter->getCollectionDataByKey(static::$productPriceTypeIdsCollection, ProductPriceHydratorStep::KEY_ID_PRICE_TYPE),
        );

        $priceProductAbstractParameters = [
            $product,
            $priceType,
        ];

        $sql = $this->productPriceSql->createPriceProductSQL(
            $productIdKey,
            $productTable,
            $productFkKey,
        );

        $this->propelExecutor->execute($sql, $priceProductAbstractParameters);
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
            $this->dataFormatter->getCollectionDataByKey(static::$productPriceTypeIdsCollection, ProductPriceHydratorStep::KEY_ID_PRICE_TYPE),
        );

        $selectProductPriceSql = $this->productPriceSql->selectProductPriceSQL(
            $productIdKey,
            $productFkKey,
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
            $this->dataFormatter->getCollectionDataByKey(static::$productCurrencyIdsCollection, ProductPriceHydratorStep::KEY_ID_CURRENCY),
        );
        $store = $this->dataFormatter->formatPostgresArrayString(
            $this->dataFormatter->getCollectionDataByKey(static::$productStoreIdsCollection, ProductPriceHydratorStep::KEY_ID_STORE),
        );
        $productPrice = $this->dataFormatter->formatPostgresArrayString(
            $this->dataFormatter->getCollectionDataByKey(static::$priceProductIds, ProductPriceHydratorStep::KEY_ID_PRICE_PRODUCT),
        );
        $grossPrice = $this->dataFormatter->formatPostgresArrayString(
            $this->dataFormatter->getCollectionDataByKey($priceProductCollection, ProductPriceHydratorStep::KEY_PRICE_GROSS_DB),
        );
        $netPrice = $this->dataFormatter->formatPostgresArrayString(
            $this->dataFormatter->getCollectionDataByKey($priceProductCollection, ProductPriceHydratorStep::KEY_PRICE_NET_DB),
        );
        $priceData = $this->dataFormatter->formatPostgresPriceDataString(
            $this->dataFormatter->getCollectionDataByKey($priceProductCollection, static::COLUMN_PRICE_DATA),
        );
        $checksum = $this->dataFormatter->formatPostgresArrayString(
            $this->dataFormatter->getCollectionDataByKey($priceProductCollection, static::COLUMN_PRICE_DATA_CHECKSUM),
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
            $productIdKey,
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
            $this->dataFormatter->getCollectionDataByKey(static::$priceProductStoreIds, ProductPriceHydratorStep::KEY_ID_PRICE_PRODUCT_STORE),
        );

        $parameters = [
            $priceProductStoreIds,
        ];

        $sql = $this->productPriceSql->createPriceProductDefaultSql();

        $this->propelExecutor->execute($sql, $parameters);
    }
}
