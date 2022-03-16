<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductOfferValidityDataImport;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Spryker\Zed\ProductOfferValidityDataImport\ProductOfferValidityDataImportConfig as SprykerProductOfferValidityDataImportConfig;

class ProductOfferValidityDataImportConfig extends SprykerProductOfferValidityDataImportConfig
{
    /**
     * @var string
     */
    public const IMPORT_TYPE_COMBINED_PRODUCT_OFFER_VALIDITY = 'combined-product-offer-validity';

    /**
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getCombinedProductOfferValidityDataImporterConfiguration(): DataImporterConfigurationTransfer
    {
        $moduleDataImportDirectory = $this->getDataImportRootPath() . 'common' . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR;

        return $this->buildImporterConfiguration(
            $moduleDataImportDirectory . 'combined_merchant_product_offer.csv',
            static::IMPORT_TYPE_COMBINED_PRODUCT_OFFER_VALIDITY,
        );
    }
}
