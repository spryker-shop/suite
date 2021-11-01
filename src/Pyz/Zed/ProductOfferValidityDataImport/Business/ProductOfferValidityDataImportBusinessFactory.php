<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductOfferValidityDataImport\Business;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Pyz\Zed\DataImport\Business\Model\DataImporterConditional;
use Pyz\Zed\DataImport\Business\Model\DataSet\DataSetConditionInterface;
use Pyz\Zed\ProductOfferValidityDataImport\Business\Model\Condition\CombinedProductOfferValidityMandatoryColumnCondition;
use Pyz\Zed\ProductOfferValidityDataImport\Business\Model\Step\CombinedProductOfferReferenceToIdProductOfferStep;
use Pyz\Zed\ProductOfferValidityDataImport\Business\Model\Step\CombinedProductOfferValidityWriterStep;
use Spryker\Zed\DataImport\Business\Model\DataImporterInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataReader\DataReaderInterface;
use Spryker\Zed\ProductOfferValidityDataImport\Business\ProductOfferValidityDataImportBusinessFactory as SprykerProductOfferValidityDataImportBusinessFactory;

/**
 * @method \Pyz\Zed\ProductOfferValidityDataImport\ProductOfferValidityDataImportConfig getConfig()
 */
class ProductOfferValidityDataImportBusinessFactory extends SprykerProductOfferValidityDataImportBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function getCombinedProductOfferValidityDataImporter(): DataImporterInterface
    {
        $dataImporter = $this->getConditionalCsvDataImporterFromConfig(
            $this->getConfig()->getCombinedProductOfferValidityDataImporterConfiguration(),
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker
            ->addStep($this->createCombinedProductOfferReferenceToIdProductOfferStep())
            ->addStep($this->createCombinedProductOfferValidityWriterStep());

        $dataImporter
            ->setDataSetCondition($this->createCombinedProductOfferValidityMandatoryColumnCondition())
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
        return new DataImporterConditional($importType, $reader, $this->getGracefulRunnerFacade());
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createCombinedProductOfferReferenceToIdProductOfferStep(): DataImportStepInterface
    {
        return new CombinedProductOfferReferenceToIdProductOfferStep(
            $this->getProductOfferFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createCombinedProductOfferValidityWriterStep(): DataImportStepInterface
    {
        return new CombinedProductOfferValidityWriterStep();
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\DataSet\DataSetConditionInterface
     */
    public function createCombinedProductOfferValidityMandatoryColumnCondition(): DataSetConditionInterface
    {
        return new CombinedProductOfferValidityMandatoryColumnCondition();
    }
}
