<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\ProductAbstract\Writer;

use Pyz\Zed\DataImport\Business\Model\ProductAbstract\ProductAbstractHydratorStep;
use Pyz\Zed\DataImport\Business\Model\PropelMariaDbVersionConstraintTrait;
use Spryker\Zed\DataImport\Business\Model\ApplicableDatabaseEngineAwareInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface;
use Spryker\Zed\DataImport\Business\Model\Publisher\DataImporterPublisher;
use Spryker\Zed\Product\Dependency\ProductEvents;
use Spryker\Zed\ProductCategory\Dependency\ProductCategoryEvents;
use Spryker\Zed\Propel\PropelConfig;
use Spryker\Zed\Url\Dependency\UrlEvents;

class ProductAbstractBulkPdoMariaDbDataSetWriter extends AbstractProductAbstractBulkPdoDataSetWriter implements DataSetWriterInterface, ApplicableDatabaseEngineAwareInterface
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
    protected function persistAbstractProductEntities(): void
    {
        if (!empty(static::$productAbstractCollection)) {
            $rawAbstractSkus = $this->dataFormatter->getCollectionDataByKey(static::$productAbstractCollection, ProductAbstractHydratorStep::KEY_SKU);
            $abstractSkus = $this->dataFormatter->formatStringList($rawAbstractSkus);
            $rowsCount = count($rawAbstractSkus);

            $attributes = $this->dataFormatter->formatPriceStringList(
                $this->dataFormatter->getCollectionDataByKey(static::$productAbstractCollection, ProductAbstractHydratorStep::KEY_ATTRIBUTES),
                $rowsCount
            );
            $fkTaxSets = $this->dataFormatter->formatStringList(
                $this->dataFormatter->getCollectionDataByKey(static::$productAbstractCollection, ProductAbstractHydratorStep::KEY_FK_TAX_SET),
                $rowsCount
            );
            $colorCode = $this->dataFormatter->formatStringList(
                $this->dataFormatter->getCollectionDataByKey(static::$productAbstractCollection, static::COLUMN_COLOR_CODE),
                $rowsCount
            );
            $newFrom = $this->dataFormatter->formatStringList(
                $this->dataFormatter->getCollectionDataByKey(static::$productAbstractCollection, static::COLUMN_NEW_FROM),
                $rowsCount
            );
            $newTo = $this->dataFormatter->formatStringList(
                $this->dataFormatter->getCollectionDataByKey(static::$productAbstractCollection, static::COLUMN_NEW_TO),
                $rowsCount
            );

            $sql = $this->productAbstractSql->createAbstractProductSQL();

            $parameters = [
                $rowsCount,
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
            $rawAbstractSkus = $this->dataFormatter->getCollectionDataByKey(static::$productAbstractLocalizedAttributesCollection, static::COLUMN_ABSTRACT_SKU);
            $abstractSkus = $this->dataFormatter->formatStringList($rawAbstractSkus);
            $rowsCount = count($rawAbstractSkus);
            $idLocale = $this->dataFormatter->formatStringList(
                $this->dataFormatter->getCollectionDataByKey(static::$productAbstractLocalizedAttributesCollection, ProductAbstractHydratorStep::KEY_FK_LOCALE),
                $rowsCount
            );
            $name = $this->dataFormatter->formatStringList(
                $this->dataFormatter->getCollectionDataByKey(static::$productAbstractLocalizedAttributesCollection, static::COLUMN_NAME),
                $rowsCount
            );
            $description = $this->dataFormatter->formatStringList(
                $this->dataFormatter->getCollectionDataByKey(static::$productAbstractLocalizedAttributesCollection, static::COLUMN_DESCRIPTION),
                $rowsCount
            );
            $metaTitle = $this->dataFormatter->formatStringList(
                $this->dataFormatter->getCollectionDataByKey(static::$productAbstractLocalizedAttributesCollection, static::COLUMN_META_TITLE),
                $rowsCount
            );
            $metaDescription = $this->dataFormatter->formatStringList(
                $this->dataFormatter->getCollectionDataByKey(static::$productAbstractLocalizedAttributesCollection, static::COLUMN_META_DESCRIPTION),
                $rowsCount
            );
            $metaKeywords = $this->dataFormatter->formatStringList(
                $this->dataFormatter->getCollectionDataByKey(static::$productAbstractLocalizedAttributesCollection, static::COLUMN_META_KEYWORDS),
                $rowsCount
            );

            $attributes = $this->dataFormatter->formatPriceStringList(
                $this->dataFormatter->getCollectionDataByKey(static::$productAbstractLocalizedAttributesCollection, ProductAbstractHydratorStep::KEY_ATTRIBUTES),
                $rowsCount
            );

            $sql = $this->productAbstractSql->createAbstractProductLocalizedAttributesSQL();
            $parameters = [
                $rowsCount,
                $abstractSkus,
                $name,
                $description,
                $metaTitle,
                $metaDescription,
                $metaKeywords,
                $idLocale,
                $attributes,
            ];

            $this->propelExecutor->execute($sql, $parameters, false);
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
            $rawAbstractSkus = $this->dataFormatter->getCollectionDataByKey(static::$productCategoryCollection, static::COLUMN_ABSTRACT_SKU);
            $abstractSkus = $this->dataFormatter->formatStringList($rawAbstractSkus);
            $rowsCount = count($rawAbstractSkus);
            $productOrder = $this->dataFormatter->formatStringList(
                $this->dataFormatter->getCollectionDataByKey(static::$productCategoryCollection, ProductAbstractHydratorStep::KEY_PRODUCT_ORDER),
                $rowsCount
            );
            $idCategory = $this->dataFormatter->formatStringList(
                $this->dataFormatter->getCollectionDataByKey(static::$productCategoryCollection, ProductAbstractHydratorStep::KEY_FK_CATEGORY),
                $rowsCount
            );

            $sql = $this->productAbstractSql->createAbstractProductCategoriesSQL();
            $parameters = [
                $rowsCount,
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
            $rawAbstractSkus = $this->dataFormatter->getCollectionDataByKey(static::$productUrlCollection, static::COLUMN_ABSTRACT_SKU);
            $abstractSkus = $this->dataFormatter->formatStringList($rawAbstractSkus);
            $rowsCount = count($rawAbstractSkus);
            $idLocale = $this->dataFormatter->formatPriceStringList(
                $this->dataFormatter->getCollectionDataByKey(static::$productUrlCollection, ProductAbstractHydratorStep::KEY_FK_LOCALE),
                $rowsCount
            );
            $url = $this->dataFormatter->formatStringList(
                $this->dataFormatter->getCollectionDataByKey(static::$productUrlCollection, static::COLUMN_URL),
                $rowsCount
            );

            $sql = $this->productAbstractSql->createAbstractProductUrlsSQL();
            $parameters = [
                $rowsCount,
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
