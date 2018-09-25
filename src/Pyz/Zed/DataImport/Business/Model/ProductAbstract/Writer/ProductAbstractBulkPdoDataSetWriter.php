<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */
namespace Pyz\Zed\DataImport\Business\Model\ProductAbstract\Writer;

use Pyz\Zed\DataImport\Business\Model\DataFormatter\DataFormatter;
use Pyz\Zed\DataImport\Business\Model\ProductAbstract\ProductAbstractHydratorStep;
use Pyz\Zed\DataImport\Business\Model\ProductAbstract\Writer\Sql\ProductAbstractSqlInterface;
use Pyz\Zed\DataImport\Business\Model\PropelExecutorInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface;
use Spryker\Zed\DataImport\Business\Model\Publisher\DataImporterPublisher;
use Spryker\Zed\Product\Dependency\ProductEvents;
use Spryker\Zed\ProductCategory\Dependency\ProductCategoryEvents;
use Spryker\Zed\Url\Dependency\UrlEvents;

class ProductAbstractBulkPdoDataSetWriter implements DataSetWriterInterface
{
    use DataFormatter;

    /**
     * @var array
     */
    protected static $productAbstractCollection = [];

    /**
     * @var array
     */
    protected static $productAbstractLocalizedAttributesCollection = [];

    /**
     * @var array
     */
    protected static $productCategoryCollection = [];

    /**
     * @var array
     */
    protected static $productUrlCollection = [];

    /**
     * @var array
     */
    protected static $productAbstractUpdated = [];

    /**
     * @var bool
     */
    protected $isDuplicatedSku = false;

    /**
     * @var \Pyz\Zed\DataImport\Business\Model\PropelExecutorInterface
     */
    protected $propelExecutor;

    /**
     * @var \Pyz\Zed\DataImport\Business\Model\ProductAbstract\Writer\Sql\ProductAbstractSqlInterface
     */
    protected $productAbstractSql;

    /**
     * @param \Pyz\Zed\DataImport\Business\Model\ProductAbstract\Writer\Sql\ProductAbstractSqlInterface $productAbstractSql
     * @param \Pyz\Zed\DataImport\Business\Model\PropelExecutorInterface $propelExecutor
     */
    public function __construct(
        ProductAbstractSqlInterface $productAbstractSql,
        PropelExecutorInterface $propelExecutor
    ) {
        $this->productAbstractSql = $productAbstractSql;
        $this->propelExecutor = $propelExecutor;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function write(DataSetInterface $dataSet): void
    {
        if (!$this->isSkuAlreadyCollected($dataSet)) {
            $this->prepareProductAbstractionCollection($dataSet);
            $this->prepareProductAbstractLocalizedAttributesCollection($dataSet);
            $this->prepareProductCategoryCollection($dataSet);
            $this->prepareProductUrlCollection($dataSet);

            if (count(static::$productAbstractCollection) >= ProductAbstractHydratorStep::BULK_SIZE) {
                $this->flush();
            }
        }
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    protected function prepareProductAbstractionCollection(DataSetInterface $dataSet): void
    {
        static::$productAbstractCollection[] = $dataSet[ProductAbstractHydratorStep::PRODUCT_ABSTRACT_TRANSFER]->modifiedToArray();
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    protected function prepareProductAbstractLocalizedAttributesCollection(DataSetInterface $dataSet): void
    {
        foreach ($dataSet[ProductAbstractHydratorStep::PRODUCT_ABSTRACT_LOCALIZED_TRANSFER] as $productAbstractLocalizedTransfer) {
            $localizedAttributeArray = $productAbstractLocalizedTransfer[ProductAbstractHydratorStep::KEY_PRODUCT_ABSTRACT_LOCALIZED_TRANSFER]->modifiedToArray();
            $localizedAttributeArray[ProductAbstractHydratorStep::KEY_ABSTRACT_SKU] = $productAbstractLocalizedTransfer[ProductAbstractHydratorStep::KEY_ABSTRACT_SKU];
            $localizedAttributeArray[ProductAbstractHydratorStep::KEY_META_DESCRIPTION] = $this->replaceDoubleQuotes($localizedAttributeArray[ProductAbstractHydratorStep::KEY_META_DESCRIPTION]);
            $localizedAttributeArray[ProductAbstractHydratorStep::KEY_DESCRIPTION] = $this->replaceDoubleQuotes($localizedAttributeArray[ProductAbstractHydratorStep::KEY_DESCRIPTION]);
            static::$productAbstractLocalizedAttributesCollection[] = $localizedAttributeArray;
        }
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    protected function prepareProductCategoryCollection(DataSetInterface $dataSet): void
    {
        foreach ($dataSet[ProductAbstractHydratorStep::PRODUCT_CATEGORY_TRANSFER] as $productCategoryTransfer) {
            $productCategoryArray = $productCategoryTransfer[ProductAbstractHydratorStep::KEY_PRODUCT_CATEGORY_TRANSFER]->modifiedToArray();
            $productCategoryArray[ProductAbstractHydratorStep::KEY_ABSTRACT_SKU] = $productCategoryTransfer[ProductAbstractHydratorStep::KEY_ABSTRACT_SKU];
            static::$productCategoryCollection[] = $productCategoryArray;
        }
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    protected function prepareProductUrlCollection(DataSetInterface $dataSet): void
    {
        foreach ($dataSet[ProductAbstractHydratorStep::PRODUCT_URL_TRANSFER] as $productUrlTransfer) {
            $productUrlArray = $productUrlTransfer[ProductAbstractHydratorStep::KEY_PRODUCT_URL_TRASNFER]->modifiedToArray();
            $productUrlArray[ProductAbstractHydratorStep::KEY_ABSTRACT_SKU] = $productUrlTransfer[ProductAbstractHydratorStep::KEY_ABSTRACT_SKU];
            static::$productUrlCollection[] = $productUrlArray;
        }
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return bool
     */
    protected function isSkuAlreadyCollected(DataSetInterface $dataSet)
    {
        $collectedSkus = array_column(static::$productAbstractCollection, ProductAbstractHydratorStep::KEY_SKU);
        $dataSetSku = $dataSet[ProductAbstractHydratorStep::PRODUCT_ABSTRACT_TRANSFER]->getSku();

        return in_array($dataSetSku, $collectedSkus);
    }

    /**
     * @return void
     */
    protected function persistAbstractProductEntities(): void
    {
        if (!empty(static::$productAbstractCollection)) {
            $abstractSkus = $this->formatPostgresArrayString(
                $this->getCollectionDataByKey(static::$productAbstractCollection, ProductAbstractHydratorStep::KEY_SKU)
            );
            $attributes = $this->formatPostgresArrayFromJson(
                $this->getCollectionDataByKey(static::$productAbstractCollection, ProductAbstractHydratorStep::KEY_ATTRIBUTES)
            );
            $fkTaxSets = $this->formatPostgresArray(
                $this->getCollectionDataByKey(static::$productAbstractCollection, ProductAbstractHydratorStep::KEY_FK_TAX_SET)
            );
            $colorCode = $this->formatPostgresArrayString(
                $this->getCollectionDataByKey(static::$productAbstractCollection, ProductAbstractHydratorStep::KEY_COLOR_CODE)
            );
            $newFrom = $this->formatPostgresArrayString(
                $this->getCollectionDataByKey(static::$productAbstractCollection, ProductAbstractHydratorStep::KEY_NEW_FROM)
            );
            $newTo = $this->formatPostgresArrayString(
                $this->getCollectionDataByKey(static::$productAbstractCollection, ProductAbstractHydratorStep::KEY_NEW_TO)
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
            $abstractSkus = $this->formatPostgresArrayString(
                $this->getCollectionDataByKey(static::$productAbstractLocalizedAttributesCollection, ProductAbstractHydratorStep::KEY_ABSTRACT_SKU)
            );
            $idLocale = $this->formatPostgresArray(
                $this->getCollectionDataByKey(static::$productAbstractLocalizedAttributesCollection, ProductAbstractHydratorStep::KEY_FK_LOCALE)
            );
            $name = $this->formatPostgresArrayString(
                $this->getCollectionDataByKey(static::$productAbstractLocalizedAttributesCollection, ProductAbstractHydratorStep::KEY_NAME)
            );
            $description = $this->formatPostgresArrayString(
                $this->getCollectionDataByKey(static::$productAbstractLocalizedAttributesCollection, ProductAbstractHydratorStep::KEY_DESCRIPTION)
            );
            $metaTitle = $this->formatPostgresArrayString(
                $this->getCollectionDataByKey(static::$productAbstractLocalizedAttributesCollection, ProductAbstractHydratorStep::KEY_META_TITLE)
            );
            $metaDescription = $this->formatPostgresArrayString(
                $this->getCollectionDataByKey(static::$productAbstractLocalizedAttributesCollection, ProductAbstractHydratorStep::KEY_META_DESCRIPTION)
            );
            $metaKeywords = $this->formatPostgresArrayString(
                $this->getCollectionDataByKey(static::$productAbstractLocalizedAttributesCollection, ProductAbstractHydratorStep::KEY_META_KEYWORDS)
            );
            $attributes = $this->formatPostgresArrayFromJson(
                $this->getCollectionDataByKey(static::$productAbstractLocalizedAttributesCollection, ProductAbstractHydratorStep::KEY_ATTRIBUTES)
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
            $abstractSkus = $this->formatPostgresArrayString(
                $this->getCollectionDataByKey(static::$productCategoryCollection, ProductAbstractHydratorStep::KEY_ABSTRACT_SKU)
            );
            $productOrder = $this->formatPostgresArrayString(
                $this->getCollectionDataByKey(static::$productCategoryCollection, ProductAbstractHydratorStep::KEY_PRODUCT_ORDER)
            );
            $idCategory = $this->formatPostgresArrayString(
                $this->getCollectionDataByKey(static::$productCategoryCollection, ProductAbstractHydratorStep::KEY_FK_CATEGORY)
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
            $abstractSkus = $this->formatPostgresArrayString(
                $this->getCollectionDataByKey(static::$productUrlCollection, ProductAbstractHydratorStep::KEY_ABSTRACT_SKU)
            );
            $idLocale = $this->formatPostgresArray(
                $this->getCollectionDataByKey(static::$productUrlCollection, ProductAbstractHydratorStep::KEY_FK_LOCALE)
            );
            $url = $this->formatPostgresArrayString(
                $this->getCollectionDataByKey(static::$productUrlCollection, ProductAbstractHydratorStep::KEY_URL)
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

    /**
     * @return void
     */
    public function flush(): void
    {
        $this->persistAbstractProductEntities();
        $this->persistAbstractProductLocalizedAttributesEntities();
        $this->persistAbstractProductCategoryEntities();
        $this->persistAbstractProductUrlEntities();

        foreach (static::$productAbstractUpdated as $abstractProductId) {
            DataImporterPublisher::addEvent(ProductEvents::PRODUCT_ABSTRACT_PUBLISH, $abstractProductId);
        }

        DataImporterPublisher::triggerEvents();
        $this->flushMemory();
    }

    /**
     * @return void
     */
    protected function flushMemory(): void
    {
        static::$productAbstractCollection = [];
        static::$productAbstractLocalizedAttributesCollection = [];
        static::$productCategoryCollection = [];
        static::$productUrlCollection = [];
        static::$productAbstractUpdated = [];
    }
}
