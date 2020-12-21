<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\ProductConcrete\Writer;

use Pyz\Zed\DataImport\Business\Model\ProductConcrete\ProductConcreteHydratorStep;
use Spryker\Zed\DataImport\Business\Model\ApplicableDatabaseEngineAwareInterface;
use Spryker\Zed\DataImport\Business\Model\Publisher\DataImporterPublisher;
use Spryker\Zed\Product\Dependency\ProductEvents;
use Spryker\Zed\Propel\PropelConfig;

class ProductConcreteBulkPdoDataSetWriter extends AbstractProductConcreteBulkDataSetWriter implements ApplicableDatabaseEngineAwareInterface
{
    /**
     * @return bool
     */
    public function isApplicable(): bool
    {
        return $this->dataImportConfig->getCurrentDatabaseEngine() === PropelConfig::DB_ENGINE_PGSQL;
    }

    /**
     * @return void
     */
    protected function persistConcreteProductEntities(): void
    {
        $sku = $this->dataFormatter->formatPostgresArrayString(
            $this->dataFormatter->getCollectionDataByKey(static::$productConcreteCollection, ProductConcreteHydratorStep::KEY_SKU)
        );
        $attributes = $this->dataFormatter->formatPostgresArrayFromJson(
            $this->dataFormatter->getCollectionDataByKey(static::$productConcreteCollection, ProductConcreteHydratorStep::KEY_ATTRIBUTES)
        );
        $discount = $this->dataFormatter->formatPostgresArray(
            $this->dataFormatter->getCollectionDataByKey(static::$productConcreteCollection, ProductConcreteHydratorStep::KEY_DISCOUNT)
        );
        $warehouses = $this->dataFormatter->formatPostgresArrayString(
            $this->dataFormatter->getCollectionDataByKey(static::$productConcreteCollection, ProductConcreteHydratorStep::KEY_WAREHOUSES)
        );
        $isActive = $this->dataFormatter->formatPostgresArray(
            $this->dataFormatter->getCollectionDataByKey(static::$productConcreteCollection, ProductConcreteHydratorStep::KEY_IS_ACTIVE)
        );
        $skuProductAbstract = $this->dataFormatter->formatPostgresArrayString(
            $this->dataFormatter->getCollectionDataByKey(static::$productConcreteCollection, static::COLUMN_ABSTRACT_SKU)
        );

        $sql = $this->productConcreteSql->createConcreteProductSQL();
        $parameters = [
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
            $sku = $this->dataFormatter->formatPostgresArrayString(
                $this->dataFormatter->getCollectionDataByKey(static::$productLocalizedAttributesCollection, ProductConcreteHydratorStep::KEY_SKU)
            );
            $idLocale = $this->dataFormatter->formatPostgresArray(
                $this->dataFormatter->getCollectionDataByKey(static::$productLocalizedAttributesCollection, ProductConcreteHydratorStep::KEY_FK_LOCALE)
            );
            $name = $this->dataFormatter->formatPostgresArrayString(
                $this->dataFormatter->getCollectionDataByKey(static::$productLocalizedAttributesCollection, static::COLUMN_NAME)
            );
            $isComplete = $this->dataFormatter->formatPostgresArray(
                $this->dataFormatter->getCollectionDataByKey(static::$productLocalizedAttributesCollection, ProductConcreteHydratorStep::KEY_IS_COMPLETE)
            );
            $description = $this->dataFormatter->formatPostgresArrayString(
                $this->dataFormatter->getCollectionDataByKey(static::$productLocalizedAttributesCollection, static::COLUMN_DESCRIPTION)
            );
            $attributes = $this->dataFormatter->formatPostgresArrayFromJson(
                $this->dataFormatter->getCollectionDataByKey(static::$productLocalizedAttributesCollection, ProductConcreteHydratorStep::KEY_ATTRIBUTES)
            );

            $sql = $this->productConcreteSql->createConcreteProductLocalizedAttributesSQL();
            $parameters = [
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
            $idLocale = $this->dataFormatter->formatPostgresArray(
                $this->dataFormatter->getCollectionDataByKey(static::$productSearchCollection, ProductConcreteHydratorStep::KEY_FK_LOCALE)
            );
            $isSearchable = $this->dataFormatter->formatPostgresArray(
                $this->dataFormatter->getCollectionDataByKey(static::$productSearchCollection, static::COLUMN_IS_SEARCHABLE)
            );
            $sku = $this->dataFormatter->formatPostgresArray(
                $this->dataFormatter->getCollectionDataByKey(static::$productSearchCollection, ProductConcreteHydratorStep::KEY_SKU)
            );

            $sql = $this->productConcreteSql->createConcreteProductSearchSQL();
            $parameters = [
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
            $bundledProductSku = $this->dataFormatter->formatPostgresArrayString(
                $this->dataFormatter->getCollectionDataByKey(static::$productBundleCollection, ProductConcreteHydratorStep::KEY_PRODUCT_BUNDLE_SKU)
            );
            $sku = $this->dataFormatter->formatPostgresArrayString(
                $this->dataFormatter->getCollectionDataByKey(static::$productBundleCollection, ProductConcreteHydratorStep::KEY_SKU)
            );
            $quantity = $this->dataFormatter->formatPostgresArray(
                $this->dataFormatter->getCollectionDataByKey(static::$productBundleCollection, ProductConcreteHydratorStep::KEY_QUANTITY)
            );

            $sql = $this->productConcreteSql->createConcreteProductBundleSQL();
            $parameters = [
                $bundledProductSku,
                $sku,
                $quantity,
            ];
            $this->propelExecutor->execute($sql, $parameters);
        }
    }
}
