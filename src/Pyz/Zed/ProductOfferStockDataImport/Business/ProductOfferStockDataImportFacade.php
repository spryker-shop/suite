<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductOfferStockDataImport\Business;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\ProductOfferStockDataImport\Business\ProductOfferStockDataImportFacade as SprykerProductOfferStockDataImportFacade;

/**
 * @method \Pyz\Zed\ProductOfferStockDataImport\Business\ProductOfferStockDataImportBusinessFactory getFactory()
 */
class ProductOfferStockDataImportFacade extends SprykerProductOfferStockDataImportFacade implements ProductOfferStockDataImportFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer
     */
    public function importCombinedProductOfferStock(
        ?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer
    ): DataImporterReportTransfer {
        return $this->getFactory()
            ->getCombinedProductOfferStockDataImporter()
            ->import($dataImporterConfigurationTransfer);
    }
}
