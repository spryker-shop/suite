<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductOfferStockDataImport;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Spryker\Zed\ProductOfferStockDataImport\ProductOfferStockDataImportConfig as SprykerProductOfferStockDataImportConfig;

class ProductOfferStockDataImportConfig extends SprykerProductOfferStockDataImportConfig
{
    /**
     * @var string
     */
    public const IMPORT_TYPE_COMBINED_PRODUCT_OFFER_STOCK = 'combined-product-offer-stock';

    /**
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getCombinedProductOfferStockDataImporterConfiguration(): DataImporterConfigurationTransfer
    {
        $moduleDataImportDirectory = $this->getDataImportRootPath() . 'common' . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR;

        return $this->buildImporterConfiguration(
            $moduleDataImportDirectory . 'combined_merchant_product_offer.csv',
            static::IMPORT_TYPE_COMBINED_PRODUCT_OFFER_STOCK
        );
    }
}
