<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductOfferStockDataImport\Communication\Plugin;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Pyz\Zed\ProductOfferStockDataImport\ProductOfferStockDataImportConfig;
use Spryker\Zed\DataImport\Dependency\Plugin\DataImportPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Pyz\Zed\ProductOfferStockDataImport\ProductOfferStockDataImportConfig getConfig()
 * @method \Pyz\Zed\ProductOfferStockDataImport\Business\ProductOfferStockDataImportFacade getFacade()
 */
class CombinedProductOfferStockDataImportPlugin extends AbstractPlugin implements DataImportPluginInterface
{
    /**
     * @return string
     */
    public function getImportType(): string
    {
        return ProductOfferStockDataImportConfig::IMPORT_TYPE_COMBINED_PRODUCT_OFFER_STOCK;
    }

    /**
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer
     */
    public function import(
        ?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null
    ): DataImporterReportTransfer {
        return $this->getFacade()->importCombinedProductOfferStock($dataImporterConfigurationTransfer);
    }
}
