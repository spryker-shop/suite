<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MerchantProductOfferDataImport;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Spryker\Zed\MerchantProductOfferDataImport\MerchantProductOfferDataImportConfig as SprykerMerchantProductOfferDataImportConfig;

class MerchantProductOfferDataImportConfig extends SprykerMerchantProductOfferDataImportConfig
{
    /**
     * @var string
     */
    public const IMPORT_TYPE_COMBINED_MERCHANT_PRODUCT_OFFER = 'combined-merchant-product-offer';

    /**
     * @var string
     */
    public const IMPORT_TYPE_COMBINED_MERCHANT_PRODUCT_OFFER_STORE = 'combined-merchant-product-offer-store';

    /**
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getCombinedMerchantProductOfferDataImporterConfiguration(): DataImporterConfigurationTransfer
    {
        return $this->buildImporterConfiguration(
            $this->getCombinedMerchantProductOfferFilePath(),
            static::IMPORT_TYPE_COMBINED_MERCHANT_PRODUCT_OFFER
        );
    }

    /**
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getCombinedMerchantProductOfferStoreDataImporterConfiguration(): DataImporterConfigurationTransfer
    {
        return $this->buildImporterConfiguration(
            $this->getCombinedMerchantProductOfferFilePath(),
            static::IMPORT_TYPE_COMBINED_MERCHANT_PRODUCT_OFFER_STORE
        );
    }

    /**
     * @return string
     */
    public function getCombinedMerchantProductOfferFilePath(): string
    {
        $moduleDataImportDirectory = $this->getDataImportRootPath() . 'common' . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR;

        return $moduleDataImportDirectory . 'combined_merchant_product_offer.csv';
    }
}
