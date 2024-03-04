<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\PriceProductOfferDataImport\Business;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\PriceProductOfferDataImport\Business\PriceProductOfferDataImportFacade as SprykerPriceProductOfferDataImportFacade;

/**
 * @method \Pyz\Zed\PriceProductOfferDataImport\Business\PriceProductOfferDataImportBusinessFactory getFactory()
 */
class PriceProductOfferDataImportFacade extends SprykerPriceProductOfferDataImportFacade implements PriceProductOfferDataImportFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer
     */
    public function importCombinedPriceProductOfferData(
        ?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null,
    ): DataImporterReportTransfer {
        return $this->getFactory()
            ->getCombinedPriceProductOfferDataImport()
            ->import($dataImporterConfigurationTransfer);
    }
}
