<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\ProductConcrete\Writer;

use Pyz\Zed\DataImport\Business\Model\ProductConcrete\ProductConcreteHydratorStep;
use Pyz\Zed\DataImport\Business\Model\PropelMariaDbVersionConstraintTrait;
use Spryker\Zed\DataImport\Business\Model\ApplicableDatabaseEngineAwareInterface;
use Spryker\Zed\DataImport\Business\Model\Publisher\DataImporterPublisher;
use Spryker\Zed\Product\Dependency\ProductEvents;
use Spryker\Zed\Propel\PropelConfig;

class ProductConcreteBulkPdoMariaDbDataSetWriter extends AbstractProductConcreteBulkDataSetWriter implements ApplicableDatabaseEngineAwareInterface
{
    use PropelMariaDbVersionConstraintTrait;

    /**
     * @return bool
     */
    public function isApplicable(): bool
    {
        return $this->dataImportConfig->getCurrentDatabaseEngine() === PropelConfig::DB_ENGINE_MYSQL
            && $this->checkIsMariaDBSupportsBulkImport($this->propelExecutor);
    }

    /**
     * @return void
     */
    protected function persistConcreteProductEntities(): void
    {
        $rawSku = $this->dataFormatter->getCollectionDataByKey(static::$productConcreteCollection, ProductConcreteHydratorStep::KEY_SKU);

        $rowsCount = count($rawSku);
        $sku = $this->dataFormatter->formatStringList($rawSku, $rowsCount);

        $attributes = $this->dataFormatter->formatPriceStringList(
            $this->dataFormatter->getCollectionDataByKey(static::$productConcreteCollection, ProductConcreteHydratorStep::KEY_ATTRIBUTES),
            $rowsCount
        );
        $discount = $this->dataFormatter->formatStringList(
            $this->dataFormatter->getCollectionDataByKey(static::$productConcreteCollection, ProductConcreteHydratorStep::KEY_DISCOUNT),
            $rowsCount
        );
        $warehouses = $this->dataFormatter->formatStringList(
            $this->dataFormatter->getCollectionDataByKey(static::$productConcreteCollection, ProductConcreteHydratorStep::KEY_WAREHOUSES),
            $rowsCount
        );
        $isActive = $this->dataFormatter->formatStringList(
            $this->dataFormatter->getCollectionDataByKey(static::$productConcreteCollection, ProductConcreteHydratorStep::KEY_IS_ACTIVE),
            $rowsCount
        );
        $skuProductAbstract = $this->dataFormatter->formatStringList(
            $this->dataFormatter->getCollectionDataByKey(static::$productConcreteCollection, static::COLUMN_ABSTRACT_SKU),
            $rowsCount
        );

        $sql = $this->productConcreteSql->createConcreteProductSQL();
        $parameters = [
            $rowsCount,
            $discount,
            $warehouses,
            $sku,
            $isActive,
            $attributes,
            $skuProductAbstract,
        ];
        $result = $this->propelExecutor->execute($sql, $parameters);
        foreach ($result as $columns) {
            DataImporterPublisher::addEvent(ProductEvents::PRODUCT_CONCRETE_PUBLISH, $columns[ProductConcreteHydratorStep::KEY_ID_PRODUCT]);
        }
    }

    /**
     * @return void
     */
    protected function persistConcreteProductLocalizedAttributesEntities(): void
    {
        if (!empty(static::$productLocalizedAttributesCollection)) {
            $rawSku = $this->dataFormatter->getCollectionDataByKey(static::$productLocalizedAttributesCollection, ProductConcreteHydratorStep::KEY_SKU);

            $sku = $this->dataFormatter->formatStringList($rawSku);

            $rowsCount = count($rawSku);

            $idLocale = $this->dataFormatter->formatStringList(
                $this->dataFormatter->getCollectionDataByKey(static::$productLocalizedAttributesCollection, ProductConcreteHydratorStep::KEY_FK_LOCALE),
                $rowsCount
            );
            $name = $this->dataFormatter->formatStringList(
                $this->dataFormatter->getCollectionDataByKey(static::$productLocalizedAttributesCollection, static::COLUMN_NAME),
                $rowsCount
            );
            $isComplete = $this->dataFormatter->formatStringList(
                $this->dataFormatter->getCollectionDataByKey(static::$productLocalizedAttributesCollection, ProductConcreteHydratorStep::KEY_IS_COMPLETE),
                $rowsCount
            );
            $description = $this->dataFormatter->formatStringList(
                $this->dataFormatter->getCollectionDataByKey(static::$productLocalizedAttributesCollection, static::COLUMN_DESCRIPTION),
                $rowsCount
            );
            $attributes = $this->dataFormatter->formatStringList(
                $this->dataFormatter->getCollectionDataByKey(static::$productLocalizedAttributesCollection, ProductConcreteHydratorStep::KEY_ATTRIBUTES),
                $rowsCount
            );

            $sql = $this->productConcreteSql->createConcreteProductLocalizedAttributesSQL();
            $parameters = [
                $rowsCount,
                $sku,
                $name,
                $description,
                $attributes,
                $isComplete,
                $idLocale,
            ];
            $this->propelExecutor->execute($sql, $parameters);
        }
    }

    /**
     * @return void
     */
    protected function persistConcreteProductSearchEntities(): void
    {
        if (!empty(static::$productSearchCollection)) {
            $rawIdLocale = $this->dataFormatter->getCollectionDataByKey(static::$productSearchCollection, ProductConcreteHydratorStep::KEY_FK_LOCALE);

            $idLocale = $this->dataFormatter->formatStringList($rawIdLocale);

            $rowsCount = count($rawIdLocale);

            $isSearchable = $this->dataFormatter->formatStringList(
                $this->dataFormatter->getCollectionDataByKey(static::$productSearchCollection, static::COLUMN_IS_SEARCHABLE),
                $rowsCount
            );
            $sku = $this->dataFormatter->formatStringList(
                $this->dataFormatter->getCollectionDataByKey(static::$productSearchCollection, ProductConcreteHydratorStep::KEY_SKU),
                $rowsCount
            );

            $sql = $this->productConcreteSql->createConcreteProductSearchSQL();
            $parameters = [
                $rowsCount,
                $idLocale,
                $sku,
                $isSearchable,
            ];
            $this->propelExecutor->execute($sql, $parameters);
        }
    }

    /**
     * @return void
     */
    protected function persistConcreteProductBundleEntities(): void
    {
        if (!empty(static::$productBundleCollection)) {
            $rawBundledProductSku = $this->dataFormatter->getCollectionDataByKey(static::$productBundleCollection, ProductConcreteHydratorStep::KEY_PRODUCT_BUNDLE_SKU);

            $bundledProductSku = $this->dataFormatter->formatPostgresArrayString($rawBundledProductSku);

            $rowsCount = count($rawBundledProductSku);

            $sku = $this->dataFormatter->formatStringList(
                $this->dataFormatter->getCollectionDataByKey(static::$productBundleCollection, ProductConcreteHydratorStep::KEY_SKU),
                $rowsCount
            );
            $quantity = $this->dataFormatter->formatStringList(
                $this->dataFormatter->getCollectionDataByKey(static::$productBundleCollection, ProductConcreteHydratorStep::KEY_QUANTITY),
                $rowsCount
            );

            $sql = $this->productConcreteSql->createConcreteProductBundleSQL();
            $parameters = [
                $rowsCount,
                $bundledProductSku,
                $sku,
                $quantity,
            ];
            $this->propelExecutor->execute($sql, $parameters);
        }
    }
}
