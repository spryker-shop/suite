<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MerchantProductOfferDataImport\Business;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Pyz\Zed\DataImport\Business\Model\DataImporterConditional;
use Pyz\Zed\DataImport\Business\Model\DataSet\DataSetConditionInterface;
use Pyz\Zed\MerchantProductOfferDataImport\Business\Model\Condition\CombinedMerchantProductOfferMandatoryColumnCondition;
use Pyz\Zed\MerchantProductOfferDataImport\Business\Model\Condition\CombinedMerchantProductOfferStoreMandatoryColumnCondition;
use Pyz\Zed\MerchantProductOfferDataImport\Business\Model\Step\CombinedApprovalStatusValidationStep;
use Pyz\Zed\MerchantProductOfferDataImport\Business\Model\Step\CombinedConcreteSkuValidationStep;
use Pyz\Zed\MerchantProductOfferDataImport\Business\Model\Step\CombinedMerchantProductOfferWriterStep;
use Pyz\Zed\MerchantProductOfferDataImport\Business\Model\Step\CombinedMerchantReferenceToIdMerchantStep;
use Pyz\Zed\MerchantProductOfferDataImport\Business\Model\Step\CombinedMerchantSkuValidationStep;
use Pyz\Zed\MerchantProductOfferDataImport\Business\Model\Step\CombinedStoreNameToIdStoreStep;
use Spryker\Zed\DataImport\Business\Model\DataImporterInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataReader\DataReaderInterface;
use Spryker\Zed\MerchantProductOfferDataImport\Business\MerchantProductOfferDataImportBusinessFactory as SprykerMerchantProductOfferDataImportBusinessFactory;

/**
 * @method \Pyz\Zed\MerchantProductOfferDataImport\MerchantProductOfferDataImportConfig getConfig()
 */
class MerchantProductOfferDataImportBusinessFactory extends SprykerMerchantProductOfferDataImportBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function getCombinedMerchantProductOfferDataImporter(): DataImporterInterface
    {
        $dataImporter = $this->getConditionalCsvDataImporterFromConfig(
            $this->getConfig()->getCombinedMerchantProductOfferDataImporterConfiguration()
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker
            ->addStep($this->createCombinedMerchantReferenceToIdMerchantStep())
            ->addStep($this->createCombinedConcreteSkuValidationStep())
            ->addStep($this->createCombinedMerchantSkuValidationStep())
            ->addStep($this->createCombinedApprovalStatusValidationStep())
            ->addStep($this->createCombinedMerchantProductOfferWriterStep());

        $dataImporter
            ->setDataSetCondition($this->createCombinedMerchantProductOfferMandatoryColumnCondition())
            ->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function getCombinedMerchantProductOfferStoreDataImporter(): DataImporterInterface
    {
        $dataImporter = $this->getConditionalCsvDataImporterFromConfig(
            $this->getConfig()->getCombinedMerchantProductOfferStoreDataImporterConfiguration()
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker
            ->addStep($this->createProductOfferReferenceToIdProductOfferStep())
            ->addStep($this->createCombinedStoreNameToIdStoreStep())
            ->addStep($this->createMerchantProductOfferStoreWriterStep());

        $dataImporter
            ->setDataSetCondition($this->createCombinedMerchantProductOfferStoreMandatoryColumnCondition())
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
    public function createCombinedMerchantReferenceToIdMerchantStep(): DataImportStepInterface
    {
        return new CombinedMerchantReferenceToIdMerchantStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createCombinedConcreteSkuValidationStep(): DataImportStepInterface
    {
        return new CombinedConcreteSkuValidationStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createCombinedMerchantSkuValidationStep(): DataImportStepInterface
    {
        return new CombinedMerchantSkuValidationStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createCombinedMerchantProductOfferWriterStep(): DataImportStepInterface
    {
        return new CombinedMerchantProductOfferWriterStep($this->getEventFacade());
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createCombinedStoreNameToIdStoreStep(): DataImportStepInterface
    {
        return new CombinedStoreNameToIdStoreStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createCombinedApprovalStatusValidationStep(): DataImportStepInterface
    {
        return new CombinedApprovalStatusValidationStep();
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\DataSet\DataSetConditionInterface
     */
    public function createCombinedMerchantProductOfferStoreMandatoryColumnCondition(): DataSetConditionInterface
    {
        return new CombinedMerchantProductOfferStoreMandatoryColumnCondition();
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\DataSet\DataSetConditionInterface
     */
    public function createCombinedMerchantProductOfferMandatoryColumnCondition(): DataSetConditionInterface
    {
        return new CombinedMerchantProductOfferMandatoryColumnCondition();
    }
}
