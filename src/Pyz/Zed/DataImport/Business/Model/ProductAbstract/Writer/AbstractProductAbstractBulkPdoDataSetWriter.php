<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\ProductAbstract\Writer;

use Pyz\Zed\DataImport\Business\Model\DataFormatter\DataImportDataFormatterInterface;
use Pyz\Zed\DataImport\Business\Model\ProductAbstract\ProductAbstractHydratorStep;
use Pyz\Zed\DataImport\Business\Model\ProductAbstract\Writer\Sql\ProductAbstractSqlInterface;
use Pyz\Zed\DataImport\Business\Model\PropelExecutorInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface;
use Spryker\Zed\DataImport\Business\Model\Publisher\DataImporterPublisher;
use Spryker\Zed\DataImport\DataImportConfig;
use Spryker\Zed\Product\Dependency\ProductEvents;

abstract class AbstractProductAbstractBulkPdoDataSetWriter implements DataSetWriterInterface
{
    protected const COLUMN_ABSTRACT_SKU = ProductAbstractHydratorStep::COLUMN_ABSTRACT_SKU;

    protected const COLUMN_NEW_FROM = ProductAbstractHydratorStep::COLUMN_NEW_FROM;

    protected const COLUMN_COLOR_CODE = ProductAbstractHydratorStep::COLUMN_COLOR_CODE;

    protected const COLUMN_META_KEYWORDS = ProductAbstractHydratorStep::COLUMN_META_KEYWORDS;

    protected const COLUMN_META_DESCRIPTION = ProductAbstractHydratorStep::COLUMN_META_DESCRIPTION;

    protected const COLUMN_META_TITLE = ProductAbstractHydratorStep::COLUMN_META_TITLE;

    protected const COLUMN_DESCRIPTION = ProductAbstractHydratorStep::COLUMN_DESCRIPTION;

    protected const COLUMN_NAME = ProductAbstractHydratorStep::COLUMN_NAME;

    protected const COLUMN_URL = ProductAbstractHydratorStep::COLUMN_URL;

    protected const COLUMN_NEW_TO = ProductAbstractHydratorStep::COLUMN_NEW_TO;

    /**
     * @var array<array<string, mixed>>
     */
    protected static $productAbstractCollection = [];

    /**
     * @var array<array<string, mixed>>
     */
    protected static $productAbstractLocalizedAttributesCollection = [];

    /**
     * @var array<array<string, mixed>>
     */
    protected static $productCategoryCollection = [];

    /**
     * @var array<array<string, mixed>>
     */
    protected static $productUrlCollection = [];

    /**
     * @var array<int>
     */
    protected static $productAbstractUpdated = [];

    /**
     * @var \Pyz\Zed\DataImport\Business\Model\PropelExecutorInterface
     */
    protected $propelExecutor;

    /**
     * @var \Pyz\Zed\DataImport\Business\Model\ProductAbstract\Writer\Sql\ProductAbstractSqlInterface
     */
    protected $productAbstractSql;

    /**
     * @var \Pyz\Zed\DataImport\Business\Model\DataFormatter\DataImportDataFormatterInterface
     */
    protected $dataFormatter;

    /**
     * @var \Spryker\Zed\DataImport\DataImportConfig
     */
    protected $dataImportConfig;

    /**
     * @param \Pyz\Zed\DataImport\Business\Model\ProductAbstract\Writer\Sql\ProductAbstractSqlInterface $productAbstractSql
     * @param \Pyz\Zed\DataImport\Business\Model\PropelExecutorInterface $propelExecutor
     * @param \Pyz\Zed\DataImport\Business\Model\DataFormatter\DataImportDataFormatterInterface $dataFormatter
     * @param \Spryker\Zed\DataImport\DataImportConfig $dataImportConfig
     */
    public function __construct(
        ProductAbstractSqlInterface $productAbstractSql,
        PropelExecutorInterface $propelExecutor,
        DataImportDataFormatterInterface $dataFormatter,
        DataImportConfig $dataImportConfig,
    ) {
        $this->productAbstractSql = $productAbstractSql;
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
        static::$productAbstractCollection[$dataSet[ProductAbstractHydratorStep::DATA_PRODUCT_ABSTRACT_TRANSFER]->getSku()] = $dataSet[ProductAbstractHydratorStep::DATA_PRODUCT_ABSTRACT_TRANSFER]->modifiedToArray();
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    protected function prepareProductAbstractLocalizedAttributesCollection(DataSetInterface $dataSet): void
    {
        foreach ($dataSet[ProductAbstractHydratorStep::DATA_PRODUCT_ABSTRACT_LOCALIZED_TRANSFER] as $productAbstractLocalizedTransfer) {
            $localizedAttributeArray = $productAbstractLocalizedTransfer[ProductAbstractHydratorStep::KEY_PRODUCT_ABSTRACT_LOCALIZED_TRANSFER]->modifiedToArray();
            $localizedAttributeArray[static::COLUMN_ABSTRACT_SKU] = $productAbstractLocalizedTransfer[static::COLUMN_ABSTRACT_SKU];
            $localizedAttributeArray[static::COLUMN_META_DESCRIPTION] = $this->dataFormatter->replaceDoubleQuotes($localizedAttributeArray[static::COLUMN_META_DESCRIPTION]);
            $localizedAttributeArray[static::COLUMN_DESCRIPTION] = $this->dataFormatter->replaceDoubleQuotes($localizedAttributeArray[static::COLUMN_DESCRIPTION]);
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
        foreach ($dataSet[ProductAbstractHydratorStep::DATA_PRODUCT_CATEGORY_TRANSFER] as $productCategoryTransfer) {
            $productCategoryArray = $productCategoryTransfer[ProductAbstractHydratorStep::KEY_PRODUCT_CATEGORY_TRANSFER]->modifiedToArray();
            $productCategoryArray[static::COLUMN_ABSTRACT_SKU] = $productCategoryTransfer[static::COLUMN_ABSTRACT_SKU];
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
        foreach ($dataSet[ProductAbstractHydratorStep::DATA_PRODUCT_URL_TRANSFER] as $productUrlTransfer) {
            $productUrlArray = $productUrlTransfer[ProductAbstractHydratorStep::KEY_PRODUCT_URL_TRASNFER]->modifiedToArray();
            $productUrlArray[static::COLUMN_ABSTRACT_SKU] = $productUrlTransfer[static::COLUMN_ABSTRACT_SKU];
            static::$productUrlCollection[] = $productUrlArray;
        }
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return bool
     */
    protected function isSkuAlreadyCollected(DataSetInterface $dataSet): bool
    {
        $dataSetSku = $dataSet[ProductAbstractHydratorStep::DATA_PRODUCT_ABSTRACT_TRANSFER]->getSku();

        return isset(static::$productAbstractCollection[$dataSetSku]);
    }

    /**
     * @return void
     */
    abstract protected function persistAbstractProductEntities(): void;

    /**
     * @return void
     */
    abstract protected function persistAbstractProductLocalizedAttributesEntities(): void;

    /**
     * return void
     *
     * @return void
     */
    abstract protected function persistAbstractProductCategoryEntities(): void;

    /**
     * @return void
     */
    abstract protected function persistAbstractProductUrlEntities(): void;

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
