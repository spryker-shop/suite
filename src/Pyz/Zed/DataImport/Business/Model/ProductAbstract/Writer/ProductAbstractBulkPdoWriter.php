<?php
/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */
namespace Pyz\Zed\DataImport\Business\Model\ProductAbstract\Writer;

use Pyz\Zed\DataImport\Business\Model\DataFormatter\DataFormatter;
use Pyz\Zed\DataImport\Business\Model\ProductAbstract\ProductAbstractHydratorStep;
use Pyz\Zed\DataImport\Business\Model\PropelExecutor;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\DataImport\Business\Model\Publisher\DataImporterPublisher;
use Spryker\Zed\DataImport\Business\Model\Writer\FlushInterface;
use Spryker\Zed\DataImport\Business\Model\Writer\WriterInterface;
use Spryker\Zed\DataImport\Dependency\Facade\DataImportToEventFacadeInterface;
use Spryker\Zed\Product\Dependency\ProductEvents;
use Spryker\Zed\ProductCategory\Dependency\ProductCategoryEvents;
use Spryker\Zed\Url\Dependency\UrlEvents;

class ProductAbstractBulkPdoWriter extends DataImporterPublisher implements WriterInterface, FlushInterface
{
    use DataFormatter;

    /**
     * @var \Pyz\Zed\DataImport\Business\Model\ProductAbstract\Writer\ProductAbstractSql
     */
    protected $productAbstractSql;

    /**
     * @param \Spryker\Zed\DataImport\Dependency\Facade\DataImportToEventFacadeInterface $eventFacade
     * @param \Pyz\Zed\DataImport\Business\Model\ProductAbstract\Writer\ProductAbstractSql $productAbstractSql
     */
    public function __construct(
        DataImportToEventFacadeInterface $eventFacade,
        ProductAbstractSql $productAbstractSql
    ) {
        parent::__construct($eventFacade);
        $this->productAbstractSql = $productAbstractSql;
    }

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
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function write(DataSetInterface $dataSet): void
    {
        $this->prepareProductAbstractionCollection($dataSet);
        $this->prepareProductAbstractLocalizedAttributesCollection($dataSet);
        $this->prepareProductCategoryCollection($dataSet);
        $this->prepareProductUrlCollection($dataSet);

        if (count(static::$productAbstractCollection) >= ProductAbstractHydratorStep::BULK_SIZE) {
            $this->flush();
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
     * @return void
     */
    protected function persistAbstractProductEntities(): void
    {
        if (!empty(static::$productAbstractCollection)) {
            $abstractSkus = $this->formatPostgresArrayString(
                array_column(static::$productAbstractCollection, ProductAbstractHydratorStep::KEY_SKU)
            );
            $attributes = $this->formatPostgresArrayFromJson(
                array_column(static::$productAbstractCollection, ProductAbstractHydratorStep::KEY_ATTRIBUTES)
            );
            $fkTaxSets = $this->formatPostgresArray(
                array_column(static::$productAbstractCollection, ProductAbstractHydratorStep::KEY_FK_TAX_SET)
            );
            $colorCode = $this->formatPostgresArrayString(
                array_column(static::$productAbstractCollection, ProductAbstractHydratorStep::KEY_COLOR_CODE)
            );
            $newFrom = $this->formatPostgresArrayString(
                array_column(static::$productAbstractCollection, ProductAbstractHydratorStep::KEY_NEW_FROM)
            );
            $newTo = $this->formatPostgresArrayString(
                array_column(static::$productAbstractCollection, ProductAbstractHydratorStep::KEY_NEW_TO)
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

            $result = PropelExecutor::execute($sql, $parameters);

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
                array_column(static::$productAbstractLocalizedAttributesCollection, ProductAbstractHydratorStep::KEY_ABSTRACT_SKU)
            );
            $idLocale = $this->formatPostgresArray(
                array_column(static::$productAbstractLocalizedAttributesCollection, ProductAbstractHydratorStep::KEY_FK_LOCALE)
            );
            $name = $this->formatPostgresArrayString(
                array_column(static::$productAbstractLocalizedAttributesCollection, ProductAbstractHydratorStep::KEY_NAME)
            );
            $description = $this->formatPostgresArrayString(
                array_column(static::$productAbstractLocalizedAttributesCollection, ProductAbstractHydratorStep::KEY_DESCRIPTION)
            );
            $metaTitle = $this->formatPostgresArrayString(
                array_column(static::$productAbstractLocalizedAttributesCollection, ProductAbstractHydratorStep::KEY_META_TITLE)
            );
            $metaDescription = $this->formatPostgresArrayString(
                array_column(static::$productAbstractLocalizedAttributesCollection, ProductAbstractHydratorStep::KEY_META_DESCRIPTION)
            );
            $metaKeywords = $this->formatPostgresArrayString(
                array_column(static::$productAbstractLocalizedAttributesCollection, ProductAbstractHydratorStep::KEY_META_KEYWORDS)
            );
            $attributes = $this->formatPostgresArrayFromJson(
                array_column(static::$productAbstractLocalizedAttributesCollection, ProductAbstractHydratorStep::KEY_ATTRIBUTES)
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

            PropelExecutor::execute($sql, $parameters);
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
                array_column(static::$productCategoryCollection, ProductAbstractHydratorStep::KEY_ABSTRACT_SKU)
            );
            $productOrder = $this->formatPostgresArrayString(
                array_column(static::$productCategoryCollection, ProductAbstractHydratorStep::KEY_PRODUCT_ORDER)
            );
            $idCategory = $this->formatPostgresArrayString(
                array_column(static::$productCategoryCollection, ProductAbstractHydratorStep::KEY_FK_CATEGORY)
            );

            $sql = $this->productAbstractSql->createAbstractProductCategoriesSQL();
            $parameters = [
                $abstractSkus,
                $productOrder,
                $idCategory,
            ];

            $result = PropelExecutor::execute($sql, $parameters);

            foreach ($result as $columns) {
                $this->addEvent(ProductCategoryEvents::PRODUCT_CATEGORY_PUBLISH, $columns[ProductAbstractHydratorStep::KEY_ID_PRODUCT_ABSTRACT]);
                $this->addEvent(ProductEvents::PRODUCT_ABSTRACT_PUBLISH, $columns[ProductAbstractHydratorStep::KEY_ID_PRODUCT_ABSTRACT]);
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
                array_column(static::$productUrlCollection, ProductAbstractHydratorStep::KEY_ABSTRACT_SKU)
            );
            $idLocale = $this->formatPostgresArray(
                array_column(static::$productUrlCollection, ProductAbstractHydratorStep::KEY_FK_LOCALE)
            );
            $url = $this->formatPostgresArrayString(
                array_column(static::$productUrlCollection, ProductAbstractHydratorStep::KEY_URL)
            );

            $sql = $this->productAbstractSql->createAbstractProductUrlsSQL();
            $parameters = [
                $abstractSkus,
                $idLocale,
                $url,
            ];

            $result = PropelExecutor::execute($sql, $parameters);

            foreach ($result as $columns) {
                $this->addEvent(UrlEvents::URL_PUBLISH, $columns[ProductAbstractHydratorStep::KEY_ID_URL]);
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
            $this->addEvent(ProductEvents::PRODUCT_ABSTRACT_PUBLISH, $abstractProductId);
        }

        $this->triggerEvents();
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
