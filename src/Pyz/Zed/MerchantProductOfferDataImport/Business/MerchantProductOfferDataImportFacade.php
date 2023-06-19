<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MerchantProductOfferDataImport\Business;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\MerchantProductOfferDataImport\Business\MerchantProductOfferDataImportFacade as SprykerMerchantProductOfferDataImportFacade;

/**
 * @method \Pyz\Zed\MerchantProductOfferDataImport\Business\MerchantProductOfferDataImportBusinessFactory getFactory()
 */
class MerchantProductOfferDataImportFacade extends SprykerMerchantProductOfferDataImportFacade implements MerchantProductOfferDataImportFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer
     */
    public function importCombinedMerchantProductOfferData(
        ?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null
    ): DataImporterReportTransfer {
        return $this->getFactory()
            ->getCombinedMerchantProductOfferDataImporter()
            ->import($dataImporterConfigurationTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer
     */
    public function importCombinedMerchantProductOfferStoreData(
        ?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null
    ): DataImporterReportTransfer {
        return $this->getFactory()
            ->getCombinedMerchantProductOfferStoreDataImporter()
            ->import($dataImporterConfigurationTransfer);
    }
}
