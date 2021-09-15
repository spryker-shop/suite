<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\ProductConcrete\Writer;

use Pyz\Zed\DataImport\Business\Model\DataFormatter\DataImportDataFormatterInterface;
use Pyz\Zed\DataImport\Business\Model\ProductConcrete\ProductConcreteHydratorStep;
use Pyz\Zed\DataImport\Business\Model\ProductConcrete\Writer\Sql\ProductConcreteSqlInterface;
use Pyz\Zed\DataImport\Business\Model\PropelExecutorInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface;
use Spryker\Zed\DataImport\Business\Model\Publisher\DataImporterPublisher;
use Spryker\Zed\DataImport\DataImportConfig;

abstract class AbstractProductConcreteBulkDataSetWriter implements DataSetWriterInterface
{
    /**
     * @var int
     */
    public const BULK_SIZE = 3000;

    protected const COLUMN_NAME = ProductConcreteHydratorStep::COLUMN_NAME;
    protected const COLUMN_DESCRIPTION = ProductConcreteHydratorStep::COLUMN_DESCRIPTION;
    protected const COLUMN_IS_SEARCHABLE = ProductConcreteHydratorStep::COLUMN_IS_SEARCHABLE;
    protected const COLUMN_ABSTRACT_SKU = ProductConcreteHydratorStep::COLUMN_ABSTRACT_SKU;

    /**
     * @var array
     */
    protected static $productConcreteCollection = [];

    /**
     * @var array
     */
    protected static $productLocalizedAttributesCollection = [];

    /**
     * @var array
     */
    protected static $productBundleCollection = [];

    /**
     * @var array
     */
    protected static $productConcreteUpdated = [];

    /**
     * @var array
     */
    protected static $productSearchCollection = [];

    /**
     * @var \Pyz\Zed\DataImport\Business\Model\ProductConcrete\Writer\Sql\ProductConcreteSqlInterface
     */
    protected $productConcreteSql;

    /**
     * @var \Pyz\Zed\DataImport\Business\Model\PropelExecutorInterface
     */
    protected $propelExecutor;

    /**
     * @var \Pyz\Zed\DataImport\Business\Model\DataFormatter\DataImportDataFormatterInterface
     */
    protected $dataFormatter;

    /**
     * @var \Spryker\Zed\DataImport\DataImportConfig
     */
    protected $dataImportConfig;

    /**
     * @param \Pyz\Zed\DataImport\Business\Model\ProductConcrete\Writer\Sql\ProductConcreteSqlInterface $productConcreteSql
     * @param \Pyz\Zed\DataImport\Business\Model\PropelExecutorInterface $propelExecutor
     * @param \Pyz\Zed\DataImport\Business\Model\DataFormatter\DataImportDataFormatterInterface $dataFormatter
     * @param \Spryker\Zed\DataImport\DataImportConfig $dataImportConfig
     */
    public function __construct(
        ProductConcreteSqlInterface $productConcreteSql,
        PropelExecutorInterface $propelExecutor,
        DataImportDataFormatterInterface $dataFormatter,
        DataImportConfig $dataImportConfig
    ) {
        $this->productConcreteSql = $productConcreteSql;
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
        $this->collectProductConcrete($dataSet);
        $this->collectProductConcreteLocalizedAttributes($dataSet);
        $this->collectProductConcreteBundle($dataSet);

        if (count(static::$productConcreteCollection) >= static::BULK_SIZE) {
            $this->flush();
        }
    }

    /**
     * @return void
     */
    public function flush(): void
    {
        $this->persistConcreteProductEntities();
        $this->persistConcreteProductLocalizedAttributesEntities();
        $this->persistConcreteProductSearchEntities();
        $this->persistConcreteProductBundleEntities();

        DataImporterPublisher::triggerEvents();
        $this->flushMemory();
    }

    /**
     * @return void
     */
    abstract protected function persistConcreteProductEntities(): void;

    /**
     * @return void
     */
    abstract protected function persistConcreteProductLocalizedAttributesEntities(): void;

    /**
     * @return void
     */
    abstract protected function persistConcreteProductSearchEntities(): void;

    /**
     * @return void
     */
    abstract protected function persistConcreteProductBundleEntities(): void;

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    protected function collectProductConcrete(DataSetInterface $dataSet): void
    {
        if (!$this->isSkuAlreadyCollected($dataSet)) {
            $productConcreteTransfer = $dataSet[ProductConcreteHydratorStep::DATA_PRODUCT_CONCRETE_TRANSFER]->modifiedToArray();
            $productConcreteTransfer[static::COLUMN_ABSTRACT_SKU] = $dataSet[static::COLUMN_ABSTRACT_SKU];
            static::$productConcreteCollection[] = $productConcreteTransfer;
        }
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return bool
     */
    protected function isSkuAlreadyCollected(DataSetInterface $dataSet)
    {
        $collectedSkus = array_column(static::$productConcreteCollection, ProductConcreteHydratorStep::KEY_SKU);
        $dataSetSku = $dataSet[ProductConcreteHydratorStep::DATA_PRODUCT_CONCRETE_TRANSFER]->getSku();

        return in_array($dataSetSku, $collectedSkus);
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    protected function collectProductConcreteLocalizedAttributes(DataSetInterface $dataSet): void
    {
        foreach ($dataSet[ProductConcreteHydratorStep::DATA_PRODUCT_CONCRETE_LOCALIZED_TRANSFER] as $productConcreteLocalizedTransfer) {
            $productSearchArray = $productConcreteLocalizedTransfer[ProductConcreteHydratorStep::KEY_PRODUCT_SEARCH_TRANSFER]->modifiedToArray();
            $productSearchArray[ProductConcreteHydratorStep::KEY_SKU] = $productConcreteLocalizedTransfer[ProductConcreteHydratorStep::KEY_SKU];

            $localizedAttributeArray = $productConcreteLocalizedTransfer[ProductConcreteHydratorStep::KEY_PRODUCT_CONCRETE_LOCALIZED_TRANSFER]->modifiedToArray();
            $localizedAttributeArray[ProductConcreteHydratorStep::KEY_SKU] = $productConcreteLocalizedTransfer[ProductConcreteHydratorStep::KEY_SKU];
            $localizedAttributeArray[static::COLUMN_DESCRIPTION] = str_replace(
                '"',
                '',
                $localizedAttributeArray[static::COLUMN_DESCRIPTION]
            );

            static::$productLocalizedAttributesCollection[] = $localizedAttributeArray;
            static::$productSearchCollection[] = $productSearchArray;
        }
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    protected function collectProductConcreteBundle(DataSetInterface $dataSet): void
    {
        foreach ($dataSet[ProductConcreteHydratorStep::DATA_PRODUCT_BUNDLE_TRANSFER] as $productConcreteBundleTransfer) {
            $productConcreteBundleArray = $productConcreteBundleTransfer[ProductConcreteHydratorStep::KEY_PRODUCT_BUNDLE_TRANSFER]->modifiedToArray();
            $productConcreteBundleArray[ProductConcreteHydratorStep::KEY_SKU] = $productConcreteBundleTransfer[ProductConcreteHydratorStep::KEY_SKU];
            $productConcreteBundleArray[ProductConcreteHydratorStep::KEY_PRODUCT_BUNDLE_SKU] = $productConcreteBundleTransfer[ProductConcreteHydratorStep::KEY_PRODUCT_BUNDLE_SKU];

            static::$productBundleCollection[] = $productConcreteBundleArray;
        }
    }

    /**
     * @return void
     */
    protected function flushMemory(): void
    {
        static::$productConcreteCollection = [];
        static::$productLocalizedAttributesCollection = [];
        static::$productSearchCollection = [];
        static::$productBundleCollection = [];
        static::$productConcreteUpdated = [];
    }
}
