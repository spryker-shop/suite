<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductRelationDataImport\Business;

use Pyz\Zed\ProductRelationDataImport\Business\Hook\ProductRelationAfterImportHook;
use Pyz\Zed\ProductRelationDataImport\ProductRelationDataImportDependencyProvider;
use Spryker\Zed\DataImport\Business\Model\DataImporterAfterImportInterface;
use Spryker\Zed\ProductRelation\Business\ProductRelationFacadeInterface;
use Spryker\Zed\ProductRelationDataImport\Business\ProductRelationDataImportBusinessFactory as SprykerProductRelationDataImportBusinessFactory;

class ProductRelationDataImportBusinessFactory extends SprykerProductRelationDataImportBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterAfterImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterBeforeImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    public function getProductRelationDataImporter()
    {
        $dataImporter = parent::getProductRelationDataImporter();

        $dataImporter->addAfterImportHook($this->createProductRelationAfterImportHook());

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterAfterImportInterface
     */
    protected function createProductRelationAfterImportHook(): DataImporterAfterImportInterface
    {
        return new ProductRelationAfterImportHook(
            $this->getProductRelationFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductRelation\Business\ProductRelationFacadeInterface
     */
    protected function getProductRelationFacade(): ProductRelationFacadeInterface
    {
        return $this->getProvidedDependency(ProductRelationDataImportDependencyProvider::FACADE_PRODUCT_RELATION);
    }
}
