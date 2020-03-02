<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductLabelDataImport\Business;

use Pyz\Zed\ProductLabelDataImport\Business\Hook\ProductLabelAfterImportPublishHook;
use Spryker\Zed\DataImport\Business\Model\DataImporterAfterImportInterface;
use Spryker\Zed\ProductLabelDataImport\Business\ProductLabelDataImportBusinessFactory as SprykerProductLabelDataImportBusinessFactory;

class ProductLabelDataImportBusinessFactory extends SprykerProductLabelDataImportBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterAfterImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterBeforeImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    public function getProductLabelImporter()
    {
        $dataImporter = parent::getProductLabelImporter();

        return $dataImporter->addAfterImportHook($this->createProductLabelAfterImportPublishHook());
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterAfterImportInterface
     */
    public function createProductLabelAfterImportPublishHook(): DataImporterAfterImportInterface
    {
        return new ProductLabelAfterImportPublishHook();
    }
}
