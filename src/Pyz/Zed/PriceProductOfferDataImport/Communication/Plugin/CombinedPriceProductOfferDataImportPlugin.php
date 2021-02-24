<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\PriceProductOfferDataImport\Communication\Plugin;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Pyz\Zed\PriceProductOfferDataImport\PriceProductOfferDataImportConfig;
use Spryker\Zed\DataImport\Dependency\Plugin\DataImportPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Pyz\Zed\PriceProductOfferDataImport\Business\PriceProductOfferDataImportFacadeInterface getFacade()
 * @method \Pyz\Zed\PriceProductOfferDataImport\PriceProductOfferDataImportConfig getConfig()
 */
class CombinedPriceProductOfferDataImportPlugin extends AbstractPlugin implements DataImportPluginInterface
{
    /**
     * @return string
     */
    public function getImportType(): string
    {
        return PriceProductOfferDataImportConfig::IMPORT_TYPE_COMBINED_PRICE_PRODUCT_OFFER;
    }

    /**
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer
     */
    public function import(?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null): DataImporterReportTransfer
    {
        return $this->getFacade()->importCombinedPriceProductOfferData($dataImporterConfigurationTransfer);
    }
}
