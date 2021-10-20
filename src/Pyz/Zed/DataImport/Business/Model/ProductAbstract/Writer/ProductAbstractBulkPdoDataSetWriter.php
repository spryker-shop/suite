<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\ProductAbstract\Writer;

use Pyz\Zed\DataImport\Business\Model\ProductAbstract\ProductAbstractHydratorStep;
use Spryker\Zed\DataImport\Business\Model\ApplicableDatabaseEngineAwareInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface;
use Spryker\Zed\DataImport\Business\Model\Publisher\DataImporterPublisher;
use Spryker\Zed\Product\Dependency\ProductEvents;
use Spryker\Zed\ProductCategory\Dependency\ProductCategoryEvents;
use Spryker\Zed\Propel\PropelConfig;
use Spryker\Zed\Url\Dependency\UrlEvents;

class ProductAbstractBulkPdoDataSetWriter extends AbstractProductAbstractBulkPdoDataSetWriter implements DataSetWriterInterface, ApplicableDatabaseEngineAwareInterface
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
    protected function persistAbstractProductEntities(): void
    {
        if (!empty(static::$productAbstractCollection)) {
            $abstractSkus = $this->dataFormatter->formatPostgresArrayString(
                $this->dataFormatter->getCollectionDataByKey(static::$productAbstractCollection, ProductAbstractHydratorStep::KEY_SKU),
            );
            $attributes = $this->dataFormatter->formatPostgresArrayFromJson(
                $this->dataFormatter->getCollectionDataByKey(static::$productAbstractCollection, ProductAbstractHydratorStep::KEY_ATTRIBUTES),
            );
            $fkTaxSets = $this->dataFormatter->formatPostgresArray(
                $this->dataFormatter->getCollectionDataByKey(static::$productAbstractCollection, ProductAbstractHydratorStep::KEY_FK_TAX_SET),
            );
            $colorCode = $this->dataFormatter->formatPostgresArrayString(
                $this->dataFormatter->getCollectionDataByKey(static::$productAbstractCollection, static::COLUMN_COLOR_CODE),
            );
            $newFrom = $this->dataFormatter->formatPostgresArray(
                $this->dataFormatter->getCollectionDataByKey(static::$productAbstractCollection, static::COLUMN_NEW_FROM),
            );
            $newTo = $this->dataFormatter->formatPostgresArray(
                $this->dataFormatter->getCollectionDataByKey(static::$productAbstractCollection, static::COLUMN_NEW_TO),
            );

            $sql = $this->productAbstractSql->createAbstractProductSQL();

            $parameters = [
                $abstractSkus,
                $attributes,
                $fkTaxSets,
                $colorCode,
                $newFrom,
                $newTo,
            ];

            $result = $this->propelExecutor->execute($sql, $parameters);

            foreach ($result as $columns) {
                static::$productAbstractUpdated[] = $columns[ProductAbstractHydratorStep::KEY_ID_PRODUCT_ABSTRACT];
            }
        }
    }

    /**
     * @return void
     */
    protected function persistAbstractProductLocalizedAttributesEntities(): void
    {
        if (!empty(static::$productAbstractLocalizedAttributesCollection)) {
            $abstractSkus = $this->dataFormatter->formatPostgresArrayString(
                $this->dataFormatter->getCollectionDataByKey(static::$productAbstractLocalizedAttributesCollection, static::COLUMN_ABSTRACT_SKU),
            );
            $idLocale = $this->dataFormatter->formatPostgresArray(
                $this->dataFormatter->getCollectionDataByKey(static::$productAbstractLocalizedAttributesCollection, ProductAbstractHydratorStep::KEY_FK_LOCALE),
            );
            $name = $this->dataFormatter->formatPostgresArrayString(
                $this->dataFormatter->getCollectionDataByKey(static::$productAbstractLocalizedAttributesCollection, static::COLUMN_NAME),
            );
            $description = $this->dataFormatter->formatPostgresArrayString(
                $this->dataFormatter->getCollectionDataByKey(static::$productAbstractLocalizedAttributesCollection, static::COLUMN_DESCRIPTION),
            );
            $metaTitle = $this->dataFormatter->formatPostgresArrayString(
                $this->dataFormatter->getCollectionDataByKey(static::$productAbstractLocalizedAttributesCollection, static::COLUMN_META_TITLE),
            );
            $metaDescription = $this->dataFormatter->formatPostgresArrayString(
                $this->dataFormatter->getCollectionDataByKey(static::$productAbstractLocalizedAttributesCollection, static::COLUMN_META_DESCRIPTION),
            );
            $metaKeywords = $this->dataFormatter->formatPostgresArrayString(
                $this->dataFormatter->getCollectionDataByKey(static::$productAbstractLocalizedAttributesCollection, static::COLUMN_META_KEYWORDS),
            );
            $attributes = $this->dataFormatter->formatPostgresArrayFromJson(
                $this->dataFormatter->getCollectionDataByKey(static::$productAbstractLocalizedAttributesCollection, ProductAbstractHydratorStep::KEY_ATTRIBUTES),
            );

            $sql = $this->productAbstractSql->createAbstractProductLocalizedAttributesSQL();
            $parameters = [
                $abstractSkus,
                $name,
                $description,
                $metaTitle,
                $metaDescription,
                $metaKeywords,
                $idLocale,
                $attributes,
            ];

            $this->propelExecutor->execute($sql, $parameters);
        }
    }

    /**
     * return void
     *
     * @return void
     */
    protected function persistAbstractProductCategoryEntities(): void
    {
        if (!empty(static::$productCategoryCollection)) {
            $abstractSkus = $this->dataFormatter->formatPostgresArrayString(
                $this->dataFormatter->getCollectionDataByKey(static::$productCategoryCollection, static::COLUMN_ABSTRACT_SKU),
            );
            $productOrder = $this->dataFormatter->formatPostgresArrayString(
                $this->dataFormatter->getCollectionDataByKey(static::$productCategoryCollection, ProductAbstractHydratorStep::KEY_PRODUCT_ORDER),
            );
            $idCategory = $this->dataFormatter->formatPostgresArrayString(
                $this->dataFormatter->getCollectionDataByKey(static::$productCategoryCollection, ProductAbstractHydratorStep::KEY_FK_CATEGORY),
            );

            $sql = $this->productAbstractSql->createAbstractProductCategoriesSQL();
            $parameters = [
                $abstractSkus,
                $productOrder,
                $idCategory,
            ];

            $result = $this->propelExecutor->execute($sql, $parameters);

            foreach ($result as $columns) {
                DataImporterPublisher::addEvent(ProductCategoryEvents::PRODUCT_CATEGORY_PUBLISH, $columns[ProductAbstractHydratorStep::KEY_ID_PRODUCT_ABSTRACT]);
                DataImporterPublisher::addEvent(ProductEvents::PRODUCT_ABSTRACT_PUBLISH, $columns[ProductAbstractHydratorStep::KEY_ID_PRODUCT_ABSTRACT]);
            }
        }
    }

    /**
     * @return void
     */
    protected function persistAbstractProductUrlEntities(): void
    {
        if (!empty(static::$productUrlCollection)) {
            $abstractSkus = $this->dataFormatter->formatPostgresArrayString(
                $this->dataFormatter->getCollectionDataByKey(static::$productUrlCollection, static::COLUMN_ABSTRACT_SKU),
            );
            $idLocale = $this->dataFormatter->formatPostgresArray(
                $this->dataFormatter->getCollectionDataByKey(static::$productUrlCollection, ProductAbstractHydratorStep::KEY_FK_LOCALE),
            );
            $url = $this->dataFormatter->formatPostgresArrayString(
                $this->dataFormatter->getCollectionDataByKey(static::$productUrlCollection, static::COLUMN_URL),
            );

            $sql = $this->productAbstractSql->createAbstractProductUrlsSQL();
            $parameters = [
                $abstractSkus,
                $idLocale,
                $url,
            ];

            $result = $this->propelExecutor->execute($sql, $parameters);

            foreach ($result as $columns) {
                DataImporterPublisher::addEvent(UrlEvents::URL_PUBLISH, $columns[ProductAbstractHydratorStep::KEY_ID_URL]);
            }
        }
    }
}
