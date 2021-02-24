<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\PriceProductOfferDataImport\Business;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Pyz\Zed\DataImport\Business\Model\DataImporterConditional;
use Pyz\Zed\DataImport\Business\Model\DataSet\DataSetConditionInterface;
use Pyz\Zed\PriceProductOfferDataImport\Business\Model\Condition\CombinedPriceProductOfferMandatoryColumnCondition;
use Pyz\Zed\PriceProductOfferDataImport\Business\Model\Step\CombinedCurrencyToIdCurrencyStep;
use Pyz\Zed\PriceProductOfferDataImport\Business\Model\Step\CombinedPreparePriceDataStep;
use Pyz\Zed\PriceProductOfferDataImport\Business\Model\Step\CombinedPriceProductStoreWriterStep;
use Pyz\Zed\PriceProductOfferDataImport\Business\Model\Step\CombinedPriceProductWriterStep;
use Pyz\Zed\PriceProductOfferDataImport\Business\Model\Step\CombinedPriceTypeToIdPriceTypeStep;
use Pyz\Zed\PriceProductOfferDataImport\Business\Model\Step\CombinedProductOfferReferenceToProductOfferDataStep;
use Pyz\Zed\PriceProductOfferDataImport\Business\Model\Step\CombinedProductOfferToIdProductStep;
use Pyz\Zed\PriceProductOfferDataImport\Business\Model\Step\CombinedStoreToIdStoreStep;
use Spryker\Zed\DataImport\Business\Model\DataImporterInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataReader\DataReaderInterface;
use Spryker\Zed\PriceProductOfferDataImport\Business\PriceProductOfferDataImportBusinessFactory as SprykerPriceProductOfferDataImportBusinessFactory;

/**
 * @method \Pyz\Zed\PriceProductOfferDataImport\PriceProductOfferDataImportConfig getConfig()
 */
class PriceProductOfferDataImportBusinessFactory extends SprykerPriceProductOfferDataImportBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function getCombinedPriceProductOfferDataImport(): DataImporterInterface
    {
        $dataImporter = $this->getConditionalCsvDataImporterFromConfig(
            $this->getConfig()->getCombinedPriceProductOfferDataImporterConfiguration()
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker
            ->addStep($this->createCombinedProductOfferReferenceToProductOfferDataStep())
            ->addStep($this->createCombinedProductOfferToIdProductStep())
            ->addStep($this->createCombinedPriceTypeToIdPriceTypeStep())
            ->addStep($this->createCombinedPriceProductWriterStep())
            ->addStep($this->createCombinedStoreToIdStoreStep())
            ->addStep($this->createCombinedCurrencyToIdCurrencyStep())
            ->addStep($this->createCombinedPreparePriceDataStep())
            ->addStep($this->createCombinedPriceProductStoreWriterStep())
            ->addStep($this->createPriceProductOfferWriterStep());

        $dataImporter
            ->setDataSetCondition($this->createCombinedPriceProductOfferMandatoryColumnCondition())
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
    public function createCombinedProductOfferReferenceToProductOfferDataStep(): DataImportStepInterface
    {
        return new CombinedProductOfferReferenceToProductOfferDataStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createCombinedProductOfferToIdProductStep(): DataImportStepInterface
    {
        return new CombinedProductOfferToIdProductStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createCombinedPriceTypeToIdPriceTypeStep(): DataImportStepInterface
    {
        return new CombinedPriceTypeToIdPriceTypeStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createCombinedPriceProductWriterStep(): DataImportStepInterface
    {
        return new CombinedPriceProductWriterStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createCombinedStoreToIdStoreStep(): DataImportStepInterface
    {
        return new CombinedStoreToIdStoreStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createCombinedCurrencyToIdCurrencyStep(): DataImportStepInterface
    {
        return new CombinedCurrencyToIdCurrencyStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createCombinedPriceProductStoreWriterStep(): DataImportStepInterface
    {
        return new CombinedPriceProductStoreWriterStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createCombinedPreparePriceDataStep(): DataImportStepInterface
    {
        return new CombinedPreparePriceDataStep($this->getPriceProductFacade(), $this->getUtilEncodingService());
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\DataSet\DataSetConditionInterface
     */
    public function createCombinedPriceProductOfferMandatoryColumnCondition(): DataSetConditionInterface
    {
        return new CombinedPriceProductOfferMandatoryColumnCondition();
    }
}
