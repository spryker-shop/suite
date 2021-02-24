<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductOfferStockDataImport\Business;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Pyz\Zed\DataImport\Business\Model\DataImporterConditional;
use Pyz\Zed\DataImport\Business\Model\DataSet\DataSetConditionInterface;
use Pyz\Zed\ProductOfferStockDataImport\Business\Model\Condition\CombinedProductOfferStockMandatoryColumnCondition;
use Pyz\Zed\ProductOfferStockDataImport\Business\Model\Step\CombinedProductOfferReferenceToIdProductOfferStep;
use Pyz\Zed\ProductOfferStockDataImport\Business\Model\Step\CombinedProductOfferStockWriterStep;
use Pyz\Zed\ProductOfferStockDataImport\Business\Model\Step\CombinedStockNameToIdStockStep;
use Spryker\Zed\DataImport\Business\Model\DataImporterInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataReader\DataReaderInterface;
use Spryker\Zed\ProductOfferStockDataImport\Business\ProductOfferStockDataImportBusinessFactory as SprykerProductOfferStockDataImportBusinessFactory;

/**
 * @method \Pyz\Zed\ProductOfferStockDataImport\ProductOfferStockDataImportConfig getConfig()
 */
class ProductOfferStockDataImportBusinessFactory extends SprykerProductOfferStockDataImportBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function getCombinedProductOfferStockDataImporter(): DataImporterInterface
    {
        $dataImporter = $this->getConditionalCsvDataImporterFromConfig(
            $this->getConfig()->getCombinedProductOfferStockDataImporterConfiguration()
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker
            ->addStep($this->createCombinedProductOfferReferenceToIdProductOfferStep())
            ->addStep($this->createCombinedStockNameToIdStockStep())
            ->addStep($this->createCombinedProductOfferStockWriterStep());

        $dataImporter
            ->setDataSetCondition($this->createCombinedProductOfferStockMandatoryColumnCondition())
            ->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer $dataImporterConfigurationTransfer
     *
     * @return \Pyz\Zed\DataImport\Business\Model\DataImporterConditional
     */
    public function getConditionalCsvDataImporterFromConfig(
        DataImporterConfigurationTransfer $dataImporterConfigurationTransfer
    ): DataImporterConditional {
        $csvReader = $this->createCsvReaderFromConfig($dataImporterConfigurationTransfer->getReaderConfiguration());

        return $this->createDataImporterConditional($dataImporterConfigurationTransfer->getImportType(), $csvReader);
    }

    /**
     * @param string $importType
     * @param \Spryker\Zed\DataImport\Business\Model\DataReader\DataReaderInterface $reader
     *
     * @return \Pyz\Zed\DataImport\Business\Model\DataImporterConditional
     */
    public function createDataImporterConditional(
        string $importType,
        DataReaderInterface $reader
    ): DataImporterConditional {
        return new DataImporterConditional($importType, $reader);
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createCombinedProductOfferReferenceToIdProductOfferStep(): DataImportStepInterface
    {
        return new CombinedProductOfferReferenceToIdProductOfferStep(
            $this->getProductOfferFacade()
        );
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createCombinedStockNameToIdStockStep(): DataImportStepInterface
    {
        return new CombinedStockNameToIdStockStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createCombinedProductOfferStockWriterStep(): DataImportStepInterface
    {
        return new CombinedProductOfferStockWriterStep();
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\DataSet\DataSetConditionInterface
     */
    public function createCombinedProductOfferStockMandatoryColumnCondition(): DataSetConditionInterface
    {
        return new CombinedProductOfferStockMandatoryColumnCondition();
    }
}
